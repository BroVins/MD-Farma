<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn(
            'consultations',
            'service_classification'
        )) {
            Schema::table(
                'consultations',
                function (Blueprint $table): void {
                    $table->string(
                        'service_classification',
                        40
                    )
                        ->nullable()
                        ->after('jenis_konsultasi');
                }
            );
        }

        if (! Schema::hasColumn(
            'consultations',
            'classified_by_admin_id'
        )) {
            Schema::table(
                'consultations',
                function (Blueprint $table): void {
                    $table->foreignId(
                        'classified_by_admin_id'
                    )
                        ->nullable()
                        ->after('service_classification')
                        ->constrained('admins')
                        ->nullOnDelete();
                }
            );
        }

        if (! Schema::hasColumn(
            'consultations',
            'classified_at'
        )) {
            Schema::table(
                'consultations',
                function (Blueprint $table): void {
                    $table->timestamp('classified_at')
                        ->nullable()
                        ->after('classified_by_admin_id');
                }
            );
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn(
            'consultations',
            'classified_by_admin_id'
        )) {
            Schema::table(
                'consultations',
                function (Blueprint $table): void {
                    $table->dropForeign([
                        'classified_by_admin_id',
                    ]);
                }
            );
        }

        Schema::table(
            'consultations',
            function (Blueprint $table): void {
                $columns = array_values(array_filter([
                    Schema::hasColumn(
                        'consultations',
                        'service_classification'
                    ) ? 'service_classification' : null,
                    Schema::hasColumn(
                        'consultations',
                        'classified_by_admin_id'
                    ) ? 'classified_by_admin_id' : null,
                    Schema::hasColumn(
                        'consultations',
                        'classified_at'
                    ) ? 'classified_at' : null,
                ]));

                if ($columns !== []) {
                    $table->dropColumn($columns);
                }
            }
        );
    }
};
