<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('consultation_screenings')) {
            return;
        }

        Schema::create(
            'consultation_screenings',
            function (Blueprint $table): void {
                $table->id();

                $table->foreignId('consultation_id')
                    ->constrained('consultations')
                    ->cascadeOnDelete();

                $table->foreignId('classification_log_id')
                    ->nullable()
                    ->constrained('consultation_classification_logs')
                    ->nullOnDelete();

                $table->foreignId('admin_id')
                    ->nullable()
                    ->constrained('admins')
                    ->nullOnDelete();

                $table->string('service_classification', 40);
                $table->longText('answers');
                $table->text('notes')->nullable();
                $table->unsignedSmallInteger('required_count');
                $table->unsignedSmallInteger('completed_count');
                $table->boolean('is_complete')->default(false);
                $table->timestamp('completed_at')->nullable();
                $table->timestamp('created_at')->useCurrent();

                $table->index([
                    'consultation_id',
                    'classification_log_id',
                    'id',
                ], 'screenings_consultation_log_id_index');

                $table->index([
                    'consultation_id',
                    'service_classification',
                    'id',
                ], 'screenings_consultation_classification_index');
            }
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('consultation_screenings');
    }
};
