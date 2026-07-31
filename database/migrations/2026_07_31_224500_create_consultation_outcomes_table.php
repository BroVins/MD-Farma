<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('consultation_outcomes')) {
            return;
        }

        Schema::create(
            'consultation_outcomes',
            function (Blueprint $table): void {
                $table->id();

                $table->foreignId('consultation_id')
                    ->constrained('consultations')
                    ->cascadeOnDelete();

                $table->foreignId('classification_log_id')
                    ->nullable()
                    ->constrained('consultation_classification_logs')
                    ->nullOnDelete();

                $table->foreignId('screening_id')
                    ->nullable()
                    ->constrained('consultation_screenings')
                    ->nullOnDelete();

                $table->foreignId('admin_id')
                    ->nullable()
                    ->constrained('admins')
                    ->nullOnDelete();

                $table->string('service_classification', 40);
                $table->string('outcome_code', 60);
                $table->string('outcome_label', 160);
                $table->text('notes')->nullable();
                $table->timestamp('created_at')->useCurrent();

                $table->index([
                    'consultation_id',
                    'classification_log_id',
                    'screening_id',
                    'id',
                ], 'outcomes_consultation_context_index');

                $table->index([
                    'consultation_id',
                    'service_classification',
                    'id',
                ], 'outcomes_consultation_classification_index');
            }
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('consultation_outcomes');
    }
};
