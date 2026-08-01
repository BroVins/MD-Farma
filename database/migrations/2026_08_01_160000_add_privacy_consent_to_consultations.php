<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $missing = [
            'privacy_consent_at' => ! Schema::hasColumn(
                'consultations',
                'privacy_consent_at'
            ),
            'privacy_policy_version' => ! Schema::hasColumn(
                'consultations',
                'privacy_policy_version'
            ),
            'privacy_consent_text' => ! Schema::hasColumn(
                'consultations',
                'privacy_consent_text'
            ),
            'privacy_consent_ip_hash' => ! Schema::hasColumn(
                'consultations',
                'privacy_consent_ip_hash'
            ),
            'privacy_consent_user_agent_hash' => ! Schema::hasColumn(
                'consultations',
                'privacy_consent_user_agent_hash'
            ),
        ];

        if (! in_array(true, $missing, true)) {
            return;
        }

        Schema::table(
            'consultations',
            function (Blueprint $table) use ($missing): void {
                if ($missing['privacy_consent_at']) {
                    $table->timestamp('privacy_consent_at')
                        ->nullable()
                        ->after('jenis_konsultasi');
                }

                if ($missing['privacy_policy_version']) {
                    $table->string('privacy_policy_version', 40)
                        ->nullable()
                        ->after('privacy_consent_at');
                }

                if ($missing['privacy_consent_text']) {
                    $table->text('privacy_consent_text')
                        ->nullable()
                        ->after('privacy_policy_version');
                }

                if ($missing['privacy_consent_ip_hash']) {
                    $table->char('privacy_consent_ip_hash', 64)
                        ->nullable()
                        ->after('privacy_consent_text');
                }

                if ($missing['privacy_consent_user_agent_hash']) {
                    $table->char(
                        'privacy_consent_user_agent_hash',
                        64
                    )
                        ->nullable()
                        ->after('privacy_consent_ip_hash');
                }
            }
        );
    }

    public function down(): void
    {
        $existing = collect([
            'privacy_consent_at',
            'privacy_policy_version',
            'privacy_consent_text',
            'privacy_consent_ip_hash',
            'privacy_consent_user_agent_hash',
        ])->filter(
            fn (string $column): bool =>
                Schema::hasColumn('consultations', $column)
        )->values()->all();

        if ($existing === []) {
            return;
        }

        Schema::table(
            'consultations',
            function (Blueprint $table) use ($existing): void {
                $table->dropColumn($existing);
            }
        );
    }
};
