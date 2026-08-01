<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('consultation_device_recoveries')) {
            return;
        }

        Schema::create(
            'consultation_device_recoveries',
            function (Blueprint $table): void {
                $table->id();
                $table->foreignId('history_owner_id')
                    ->constrained(
                        'consultation_history_owners'
                    )
                    ->cascadeOnDelete();
                $table->foreignId('source_consultation_id')
                    ->nullable()
                    ->constrained('consultations')
                    ->nullOnDelete();
                $table->foreignId('new_guest_id')
                    ->nullable()
                    ->constrained('consultation_guests')
                    ->nullOnDelete();
                $table->string('recovery_method', 50);
                $table->char('phone_hash', 64);
                $table->timestamp('recovered_at');
                $table->timestamps();

                $table->index([
                    'history_owner_id',
                    'recovered_at',
                ], 'device_recovery_owner_time_index');
            }
        );
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'consultation_device_recoveries'
        );
    }
};
