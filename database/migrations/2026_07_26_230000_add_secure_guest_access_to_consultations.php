<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'consultation_guests',
            function (Blueprint $table): void {
                $table->id();
                $table->uuid('public_id')->unique();
                $table->timestamp('expires_at')->nullable();
                $table->timestamps();
            }
        );

        Schema::table(
            'consultations',
            function (Blueprint $table): void {
                $table->uuid('public_id')
                    ->nullable()
                    ->unique()
                    ->after('id');

                $table->foreignId('guest_id')
                    ->nullable()
                    ->after('public_id')
                    ->constrained('consultation_guests')
                    ->nullOnDelete();
            }
        );

        DB::table('consultations')
            ->select('id')
            ->orderBy('id')
            ->eachById(
                function (object $consultation): void {
                    DB::table('consultations')
                        ->where('id', $consultation->id)
                        ->update([
                            'public_id' => (string) Str::uuid(),
                        ]);
                }
            );
    }

    public function down(): void
    {
        Schema::table(
            'consultations',
            function (Blueprint $table): void {
                $table->dropConstrainedForeignId('guest_id');
                $table->dropUnique(
                    'consultations_public_id_unique'
                );
                $table->dropColumn('public_id');
            }
        );

        Schema::dropIfExists('consultation_guests');
    }
};
