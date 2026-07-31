<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('consultation_classification_notices')) {
            return;
        }

        Schema::create(
            'consultation_classification_notices',
            function (Blueprint $table): void {
                $table->id();

                $table->foreignId('consultation_id')
                    ->constrained('consultations')
                    ->cascadeOnDelete();

                $table->foreignId('classification_log_id')
                    ->nullable()
                    ->unique()
                    ->constrained('consultation_classification_logs')
                    ->nullOnDelete();

                $table->foreignId('message_id')
                    ->unique()
                    ->constrained('messages')
                    ->cascadeOnDelete();

                $table->foreignId('admin_id')
                    ->nullable()
                    ->constrained('admins')
                    ->nullOnDelete();

                $table->string('template_code', 80);
                $table->string('service_classification', 40);
                $table->text('content_snapshot');
                $table->timestamp('sent_at')->useCurrent();
                $table->timestamp('created_at')->useCurrent();

                $table->index([
                    'consultation_id',
                    'sent_at',
                ]);
            }
        );
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'consultation_classification_notices'
        );
    }
};
