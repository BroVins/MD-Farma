<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('consultation_classification_logs')) {
            return;
        }

        Schema::create(
            'consultation_classification_logs',
            function (Blueprint $table): void {
                $table->id();

                $table->foreignId('consultation_id')
                    ->constrained('consultations')
                    ->cascadeOnDelete();

                $table->foreignId('admin_id')
                    ->nullable()
                    ->constrained('admins')
                    ->nullOnDelete();

                $table->string('previous_classification', 40)
                    ->nullable();

                $table->string('new_classification', 40);

                $table->text('reason')->nullable();
                $table->timestamp('created_at')->useCurrent();

                $table->index([
                    'consultation_id',
                    'created_at',
                ]);
            }
        );

        DB::table('consultations')
            ->whereNotNull('service_classification')
            ->orderBy('id')
            ->chunkById(
                200,
                function ($consultations): void {
                    $rows = $consultations->map(
                        fn ($consultation): array => [
                            'consultation_id' => $consultation->id,
                            'admin_id' =>
                                $consultation->classified_by_admin_id,
                            'previous_classification' => null,
                            'new_classification' =>
                                $consultation->service_classification,
                            'reason' => null,
                            'created_at' =>
                                $consultation->classified_at
                                ?? $consultation->updated_at
                                ?? now(),
                        ]
                    )->all();

                    if ($rows !== []) {
                        DB::table(
                            'consultation_classification_logs'
                        )->insert($rows);
                    }
                }
            );
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'consultation_classification_logs'
        );
    }
};
