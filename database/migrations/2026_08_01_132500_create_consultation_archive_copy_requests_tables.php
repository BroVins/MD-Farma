<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('consultation_archive_copy_requests')) {
            Schema::create(
                'consultation_archive_copy_requests',
                function (Blueprint $table): void {
                    $table->id();
                    $table->uuid('public_id')->unique();

                    $table->foreignId('consultation_id')
                        ->constrained('consultations')
                        ->cascadeOnDelete();

                    $table->foreignId('history_owner_id')
                        ->constrained('consultation_history_owners')
                        ->cascadeOnDelete();

                    $table->foreignId('patient_profile_id')
                        ->nullable()
                        ->constrained('consultation_patient_profiles')
                        ->nullOnDelete();

                    $table->foreignId('requested_by_guest_id')
                        ->nullable()
                        ->constrained('consultation_guests')
                        ->nullOnDelete();

                    $table->string('status', 30)
                        ->default('pending');
                    $table->text('reason');
                    $table->string('contact_method', 30);
                    $table->string('contact_value', 120);
                    $table->timestamp('patient_confirmed_at');
                    $table->timestamp('submitted_at')->useCurrent();

                    $table->foreignId('processed_by_admin_id')
                        ->nullable()
                        ->constrained('admins')
                        ->nullOnDelete();

                    $table->text('decision_notes')->nullable();
                    $table->timestamp('processed_at')->nullable();
                    $table->timestamp('completed_at')->nullable();
                    $table->timestamps();

                    $table->index([
                        'history_owner_id',
                        'status',
                        'submitted_at',
                    ], 'archive_requests_owner_status_index');

                    $table->index([
                        'consultation_id',
                        'status',
                    ], 'archive_requests_consultation_status_index');
                }
            );
        }

        if (! Schema::hasTable('consultation_archive_copy_request_logs')) {
            Schema::create(
                'consultation_archive_copy_request_logs',
                function (Blueprint $table): void {
                    $table->id();

                    $table->foreignId('archive_copy_request_id')
                        ->constrained('consultation_archive_copy_requests')
                        ->cascadeOnDelete();

                    $table->foreignId('admin_id')
                        ->nullable()
                        ->constrained('admins')
                        ->nullOnDelete();

                    $table->string('actor_type', 20);
                    $table->string('previous_status', 30)
                        ->nullable();
                    $table->string('new_status', 30);
                    $table->text('notes')->nullable();
                    $table->timestamp('created_at')->useCurrent();

                    $table->index([
                        'archive_copy_request_id',
                        'created_at',
                    ], 'archive_request_logs_request_time_index');
                }
            );
        }
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'consultation_archive_copy_request_logs'
        );
        Schema::dropIfExists(
            'consultation_archive_copy_requests'
        );
    }
};
