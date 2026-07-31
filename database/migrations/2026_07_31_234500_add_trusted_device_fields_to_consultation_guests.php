<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
                    'access_token_hash'
                )) {
                    $table->char('access_token_hash', 64)
                        ->nullable()
                        ->unique()
                        ->after('history_owner_id');
                }

                if (! Schema::hasColumn(
                    'consultation_guests',
                    'last_seen_at'
                )) {
                    $table->timestamp('last_seen_at')
                        ->nullable()
                        ->after('access_token_hash');
                }

                if (! Schema::hasColumn(
                    'consultation_guests',
                    'revoked_at'
                )) {
                    $table->timestamp('revoked_at')
                        ->nullable()
                        ->after('last_seen_at');
                }
            }
        );
    }

    public function down(): void
    {
        Schema::table(
            'consultation_guests',
            function (Blueprint $table): void {
                if (Schema::hasColumn(
                    'consultation_guests',
                    'access_token_hash'
                )) {
                    $table->dropUnique(
                        'consultation_guests_access_token_hash_unique'
                    );
                }

                $columns = array_values(array_filter([
                    Schema::hasColumn(
                        'consultation_guests',
                        'access_token_hash'
                    ) ? 'access_token_hash' : null,
                    Schema::hasColumn(
                        'consultation_guests',
                        'last_seen_at'
                    ) ? 'last_seen_at' : null,
                    Schema::hasColumn(
                        'consultation_guests',
                        'revoked_at'
                    ) ? 'revoked_at' : null,
                ]));

                if ($columns !== []) {
                    $table->dropColumn($columns);
                }
            }
        );
    }
};
