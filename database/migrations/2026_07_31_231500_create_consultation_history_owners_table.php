<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('consultation_history_owners')) {
            Schema::create(
                'consultation_history_owners',
                function (Blueprint $table): void {
                    $table->id();
                    $table->uuid('public_id')->unique();
                    $table->string('password_hash');
                    $table->timestamp('password_set_at')->nullable();
                    $table->unsignedSmallInteger('failed_attempts')
                        ->default(0);
                    $table->timestamp('locked_until')->nullable();
                    $table->timestamps();
                }
            );
        }

        if (! Schema::hasColumn(
            'consultation_guests',
            'history_owner_id'
        )) {
            Schema::table(
                'consultation_guests',
                function (Blueprint $table): void {
                    $table->foreignId('history_owner_id')
                        ->nullable()
                        ->after('public_id')
                        ->constrained(
                            'consultation_history_owners'
                        )
                        ->nullOnDelete();
                }
            );
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn(
            'consultation_guests',
            'history_owner_id'
        )) {
            Schema::table(
                'consultation_guests',
                function (Blueprint $table): void {
                    $table->dropConstrainedForeignId(
                        'history_owner_id'
                    );
                }
            );
        }

        Schema::dropIfExists(
            'consultation_history_owners'
        );
    }
};
