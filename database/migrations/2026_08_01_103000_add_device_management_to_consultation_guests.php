<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table(
            'consultation_guests',
            function (Blueprint $table): void {
                if (! Schema::hasColumn(
                    'consultation_guests',
                    'device_label'
                )) {
                    $table->string('device_label', 120)
                        ->nullable()
                        ->after('access_token_hash');
                }

                if (! Schema::hasColumn(
                    'consultation_guests',
                    'first_seen_at'
                )) {
                    $table->timestamp('first_seen_at')
                        ->nullable()
                        ->after('device_label');
                }
            }
        );

        DB::table('consultation_guests')
            ->whereNull('first_seen_at')
            ->update([
                'first_seen_at' => DB::raw('created_at'),
            ]);

        if (! Schema::hasTable(
            'consultation_device_revocations'
        )) {
            Schema::create(
                'consultation_device_revocations',
                function (Blueprint $table): void {
                    $table->id();
                    $table->foreignId('history_owner_id')
                        ->constrained(
                            'consultation_history_owners'
                        )
                        ->cascadeOnDelete();
                    $table->foreignId('target_guest_id')
                        ->nullable()
                        ->constrained('consultation_guests')
                        ->nullOnDelete();
                    $table->foreignId('revoked_by_guest_id')
                        ->nullable()
                        ->constrained('consultation_guests')
                        ->nullOnDelete();
                    $table->string('action', 32);
                    $table->timestamp('revoked_at');
                    $table->timestamps();

                    $table->index(
                        ['history_owner_id', 'revoked_at'],
                        'device_revocation_owner_time_index'
                    );
                }
            );
        }
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'consultation_device_revocations'
        );

        Schema::table(
            'consultation_guests',
            function (Blueprint $table): void {
                $columns = array_values(array_filter([
                    Schema::hasColumn(
                        'consultation_guests',
                        'device_label'
                    ) ? 'device_label' : null,
                    Schema::hasColumn(
                        'consultation_guests',
                        'first_seen_at'
                    ) ? 'first_seen_at' : null,
                ]));

                if ($columns !== []) {
                    $table->dropColumn($columns);
                }
            }
        );
    }
};
