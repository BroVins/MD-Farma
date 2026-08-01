<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->ensureUniqueAdminUsername();

        if (! Schema::hasTable('consultation_status_logs')) {
            Schema::create(
                'consultation_status_logs',
                function (Blueprint $table): void {
                    $table->id();
                    $table->foreignId('consultation_id')
                        ->constrained('consultations')
                        ->cascadeOnDelete();
                    $table->foreignId('admin_id')
                        ->nullable()
                        ->constrained('admins')
                        ->nullOnDelete();
                    $table->string('previous_status', 20);
                    $table->string('new_status', 20);
                    $table->text('reason')->nullable();
                    $table->timestamp('created_at')->useCurrent();

                    $table->index(
                        ['consultation_id', 'created_at'],
                        'consultation_status_logs_consultation_time_index'
                    );
                }
            );
        }

        if (! Schema::hasTable('consultation_access_logs')) {
            Schema::create(
                'consultation_access_logs',
                function (Blueprint $table): void {
                    $table->id();
                    $table->foreignId('consultation_id')
                        ->constrained('consultations')
                        ->cascadeOnDelete();
                    $table->foreignId('message_id')
                        ->nullable()
                        ->constrained('messages')
                        ->nullOnDelete();
                    $table->foreignId('archive_copy_request_id')
                        ->nullable()
                        ->constrained('consultation_archive_copy_requests')
                        ->nullOnDelete();
                    $table->foreignId('admin_id')
                        ->nullable()
                        ->constrained('admins')
                        ->nullOnDelete();
                    $table->string('action', 50);
                    $table->json('metadata')->nullable();
                    $table->timestamp('created_at')->useCurrent();

                    $table->index(
                        ['consultation_id', 'action', 'created_at'],
                        'consultation_access_logs_action_time_index'
                    );
                    $table->index(
                        ['admin_id', 'created_at'],
                        'consultation_access_logs_admin_time_index'
                    );
                }
            );
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('consultation_access_logs');
        Schema::dropIfExists('consultation_status_logs');

        if (Schema::hasTable('admins')) {
            $indexes = collect(Schema::getIndexes('admins'));
            $hasIndex = $indexes->contains(
                fn (array $index): bool =>
                    ($index['name'] ?? null) === 'admins_username_unique'
            );

            if ($hasIndex) {
                Schema::table('admins', function (Blueprint $table): void {
                    $table->dropUnique('admins_username_unique');
                });
            }
        }
    }

    private function ensureUniqueAdminUsername(): void
    {
        if (! Schema::hasTable('admins')) {
            return;
        }

        $hasUnique = collect(Schema::getIndexes('admins'))
            ->contains(function (array $index): bool {
                $columns = array_values($index['columns'] ?? []);

                return (bool) ($index['unique'] ?? false)
                    && $columns === ['username'];
            });

        if ($hasUnique) {
            return;
        }

        $duplicate = DB::table('admins')
            ->select('username', DB::raw('COUNT(*) AS total'))
            ->groupBy('username')
            ->havingRaw('COUNT(*) > 1')
            ->first();

        if ($duplicate) {
            throw new \RuntimeException(
                'Username admin duplikat ditemukan. Hapus akun duplikat sebelum menjalankan migration.'
            );
        }

        Schema::table('admins', function (Blueprint $table): void {
            $table->unique('username', 'admins_username_unique');
        });
    }
};
