<?php

use App\Models\Consultation;
use App\Models\ConsultationGuest;
use App\Models\ConsultationPatientProfile;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('consultation_patient_profiles')) {
            Schema::create(
                'consultation_patient_profiles',
                function (Blueprint $table): void {
                    $table->id();
                    $table->foreignId('history_owner_id')
                        ->constrained('consultation_history_owners')
                        ->cascadeOnDelete();
                    $table->uuid('public_id')->unique();
                    $table->string('name', 100);
                    $table->unsignedSmallInteger('age');
                    $table->string('phone', 25);
                    $table->enum('relationship', [
                        'saya',
                        'anak',
                        'pasangan',
                        'orang_tua',
                        'lainnya',
                    ])->default('lainnya');
                    $table->boolean('is_default')->default(false);
                    $table->timestamp('last_used_at')->nullable();
                    $table->timestamps();

                    $table->index(
                        ['history_owner_id', 'is_default'],
                        'patient_profile_owner_default_index'
                    );
                    $table->index(
                        ['history_owner_id', 'last_used_at'],
                        'patient_profile_owner_last_used_index'
                    );
                }
            );
        }

        if (! Schema::hasColumn('consultations', 'patient_profile_id')) {
            Schema::table('consultations', function (Blueprint $table): void {
                $table->foreignId('patient_profile_id')
                    ->nullable()
                    ->after('guest_id')
                    ->constrained('consultation_patient_profiles')
                    ->nullOnDelete();
            });
        }

        $this->backfillLegacyProfiles();
    }

    public function down(): void
    {
        if (Schema::hasColumn('consultations', 'patient_profile_id')) {
            Schema::table('consultations', function (Blueprint $table): void {
                $table->dropConstrainedForeignId('patient_profile_id');
            });
        }

        Schema::dropIfExists('consultation_patient_profiles');
    }

    private function backfillLegacyProfiles(): void
    {
        if (
            ! Schema::hasTable('consultation_patient_profiles')
            || ! Schema::hasColumn('consultations', 'patient_profile_id')
        ) {
            return;
        }

        ConsultationGuest::query()
            ->whereNotNull('history_owner_id')
            ->with(['consultations' => function ($query): void {
                $query->orderBy('id');
            }])
            ->chunkById(100, function ($guests): void {
                foreach ($guests as $guest) {
                    foreach ($guest->consultations as $consultation) {
                        if ($consultation->patient_profile_id) {
                            continue;
                        }

                        $profile = ConsultationPatientProfile::query()
                            ->where(
                                'history_owner_id',
                                $guest->history_owner_id
                            )
                            ->where('name', $consultation->nama)
                            ->where('age', $consultation->umur)
                            ->where('phone', $consultation->no_hp)
                            ->first();

                        if (! $profile) {
                            $profile = ConsultationPatientProfile::create([
                                'history_owner_id' =>
                                    $guest->history_owner_id,
                                'public_id' => (string) Str::uuid(),
                                'name' => $consultation->nama,
                                'age' => $consultation->umur,
                                'phone' => $consultation->no_hp,
                                'relationship' => 'lainnya',
                                'last_used_at' =>
                                    $consultation->last_message_at
                                    ?? $consultation->created_at,
                            ]);
                        }

                        Consultation::query()
                            ->whereKey($consultation->id)
                            ->update([
                                'patient_profile_id' => $profile->id,
                            ]);
                    }
                }
            });

        DB::table('consultation_history_owners')
            ->orderBy('id')
            ->pluck('id')
            ->each(function ($ownerId): void {
                $defaultProfileId = ConsultationPatientProfile::query()
                    ->where('history_owner_id', $ownerId)
                    ->orderByDesc('last_used_at')
                    ->orderByDesc('id')
                    ->value('id');

                if (! $defaultProfileId) {
                    return;
                }

                ConsultationPatientProfile::query()
                    ->where('history_owner_id', $ownerId)
                    ->update(['is_default' => false]);

                ConsultationPatientProfile::query()
                    ->whereKey($defaultProfileId)
                    ->update([
                        'is_default' => true,
                        'relationship' => 'saya',
                    ]);
            });
    }
};
