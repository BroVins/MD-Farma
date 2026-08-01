<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Consultation extends Model
{
    public const SERVICE_CLASSIFICATIONS = [
        'pelayanan_resep' => 'Pelayanan resep',
        'informasi_produk' => 'Informasi produk',
        'swamedikasi' => 'Swamedikasi',
        'memerlukan_resep' => 'Memerlukan resep dokter',
        'perlu_rujukan' => 'Perlu pemeriksaan / rujukan',
    ];

    public const CLASSIFICATION_NOTICE_TEMPLATES = [
        'pelayanan_resep' => [
            'code' => 'classification.pelayanan_resep.v1',
            'message' => 'Percakapan ini dilanjutkan sebagai pelayanan resep. Apoteker akan meninjau resep dan dapat meminta informasi tambahan sebelum obat diproses.',
        ],
        'informasi_produk' => [
            'code' => 'classification.informasi_produk.v1',
            'message' => 'Percakapan ini dicatat sebagai layanan informasi produk. Informasi yang diberikan berfokus pada produk, ketersediaan, atau penggunaan umum dan bukan diagnosis.',
        ],
        'swamedikasi' => [
            'code' => 'classification.swamedikasi.v1',
            'message' => 'Percakapan ini dilanjutkan sebagai pelayanan swamedikasi. Apoteker akan menanyakan beberapa informasi tambahan agar pilihan obat tanpa resep lebih sesuai dan aman.',
        ],
        'memerlukan_resep' => [
            'code' => 'classification.memerlukan_resep.v1',
            'message' => 'Berdasarkan informasi yang Anda sampaikan, obat yang diminta memerlukan resep dokter yang sesuai. MD Farma belum dapat memproses permintaan tersebut tanpa resep. Silakan unggah resep yang berlaku atau berkonsultasi dengan dokter.',
        ],
        'perlu_rujukan' => [
            'code' => 'classification.perlu_rujukan.v1',
            'message' => 'Berdasarkan informasi yang Anda sampaikan, kondisi ini sebaiknya diperiksa langsung oleh dokter atau fasilitas pelayanan kesehatan. Konsultasi melalui chat apotek tidak cukup untuk memastikan penyebab dan penanganannya. Jika kondisi terasa berat atau memburuk, segera cari pertolongan medis.',
        ],
    ];

    public const SCREENING_TEMPLATES = [
        'pelayanan_resep' => [
            'title' => 'Skrining pelayanan resep',
            'description' => 'Dokumentasikan pemeriksaan administratif, farmasetik, dan klinis sebelum pelayanan diselesaikan.',
            'notes_required' => false,
            'notes_label' => 'Catatan hasil pengkajian',
            'items' => [
                'identitas_pasien' => 'Identitas pasien telah diperiksa.',
                'resep_dan_dokter' => 'Resep, identitas dokter, dan tanggal resep dapat dibaca serta telah diperiksa.',
                'obat_sediaan_jumlah' => 'Nama obat, kekuatan, bentuk sediaan, dan jumlah telah diperiksa.',
                'dosis_aturan_pakai' => 'Dosis, frekuensi, durasi, dan aturan pakai telah diperiksa.',
                'alergi_efek_samping' => 'Riwayat alergi atau efek samping telah ditanyakan.',
                'duplikasi_interaksi' => 'Duplikasi, interaksi, dan kontraindikasi telah diperiksa.',
                'kesesuaian_tindak_lanjut' => 'Ketidaksesuaian tidak ditemukan atau telah ditindaklanjuti.',
                'edukasi_penggunaan' => 'Informasi penggunaan dan penyimpanan obat telah disampaikan.',
            ],
        ],
        'informasi_produk' => [
            'title' => 'Skrining informasi produk',
            'description' => 'Pastikan percakapan tetap berada pada informasi produk dan tidak berubah menjadi rekomendasi terapi tanpa skrining.',
            'notes_required' => false,
            'notes_label' => 'Catatan informasi yang diberikan',
            'items' => [
                'kebutuhan_dipahami' => 'Produk atau informasi yang diminta telah dipahami.',
                'golongan_produk' => 'Golongan produk dan batas penyerahannya telah diperiksa.',
                'tanpa_diagnosis' => 'Tidak ada diagnosis atau rekomendasi terapi khusus yang diberikan.',
                'informasi_sesuai' => 'Informasi penggunaan, penyimpanan, atau ketersediaan disampaikan secara sesuai.',
            ],
        ],
        'swamedikasi' => [
            'title' => 'Skrining swamedikasi',
            'description' => 'Lengkapi informasi dasar pengguna, keluhan, faktor risiko, dan batas aman swamedikasi.',
            'notes_required' => false,
            'notes_label' => 'Catatan hasil skrining',
            'items' => [
                'pengguna_dan_umur' => 'Pengguna obat dan umurnya telah dipastikan.',
                'keluhan_utama' => 'Keluhan utama dan lokasi keluhan telah dicatat.',
                'durasi_keparahan' => 'Lama, pola, dan tingkat keparahan keluhan telah ditanyakan.',
                'alergi' => 'Riwayat alergi atau reaksi obat telah ditanyakan.',
                'kondisi_khusus' => 'Penyakit penyerta, kehamilan, atau menyusui telah ditanyakan bila relevan.',
                'obat_lain' => 'Obat atau produk lain yang sedang digunakan telah ditanyakan.',
                'riwayat_penggunaan' => 'Riwayat penggunaan produk yang diminta telah ditanyakan.',
                'tanda_bahaya' => 'Tanda bahaya telah disaring dan tidak ditemukan, atau sudah ditindaklanjuti.',
                'boleh_tanpa_resep' => 'Produk dipastikan dapat diberikan tanpa resep sesuai ketentuan.',
                'edukasi_batas_penggunaan' => 'Cara pakai, batas penggunaan, dan kapan harus mencari pertolongan telah disampaikan.',
            ],
        ],
        'memerlukan_resep' => [
            'title' => 'Skrining kebutuhan resep dokter',
            'description' => 'Catat dasar keputusan bahwa produk tidak dapat diproses tanpa resep yang sesuai.',
            'notes_required' => true,
            'notes_label' => 'Alasan produk memerlukan resep',
            'items' => [
                'produk_diidentifikasi' => 'Produk atau zat aktif yang diminta telah diidentifikasi.',
                'status_resep' => 'Ketersediaan dan kesesuaian resep telah diperiksa.',
                'dasar_keputusan' => 'Dasar keputusan bahwa resep diperlukan telah ditentukan.',
                'tidak_diproses' => 'Permintaan tidak diproses tanpa resep yang sesuai.',
                'arahan_diberikan' => 'Pasien telah diarahkan untuk mengunggah resep atau berkonsultasi dengan dokter.',
            ],
        ],
        'perlu_rujukan' => [
            'title' => 'Skrining pemeriksaan atau rujukan',
            'description' => 'Dokumentasikan alasan mengapa konsultasi chat tidak cukup dan pasien perlu pemeriksaan langsung.',
            'notes_required' => true,
            'notes_label' => 'Alasan dan tujuan rujukan',
            'items' => [
                'keluhan_dan_durasi' => 'Keluhan, durasi, dan perkembangan kondisi telah dicatat.',
                'tanda_bahaya' => 'Tanda bahaya atau faktor risiko yang relevan telah diperiksa.',
                'alasan_rujukan' => 'Alasan pemeriksaan langsung atau rujukan telah ditentukan.',
                'tujuan_rujukan' => 'Arahan ke dokter atau fasilitas pelayanan kesehatan telah disampaikan.',
                'instruksi_darurat' => 'Instruksi mencari pertolongan segera telah diberikan bila kondisi berat atau memburuk.',
            ],
        ],
    ];


    public const FINAL_OUTCOME_TEMPLATES = [
        'pelayanan_resep' => [
            'title' => 'Hasil akhir pelayanan resep',
            'description' => 'Tetapkan keputusan utama setelah pengkajian resep dan tindak lanjut selesai.',
            'notes_required' => true,
            'notes_label' => 'Ringkasan keputusan pelayanan resep',
            'options' => [
                'resep_dapat_diproses' => 'Resep dapat diproses sesuai hasil pengkajian',
                'resep_diproses_generik' => 'Resep diproses dengan alternatif generik yang disetujui',
                'resep_perlu_klarifikasi' => 'Resep memerlukan klarifikasi lebih lanjut',
                'resep_tidak_dapat_diproses' => 'Resep tidak dapat diproses',
                'pasien_diarahkan_ke_dokter' => 'Pasien diarahkan kembali ke dokter',
            ],
        ],
        'informasi_produk' => [
            'title' => 'Hasil akhir informasi produk',
            'description' => 'Catat hasil utama dari layanan informasi produk yang diberikan.',
            'notes_required' => false,
            'notes_label' => 'Ringkasan informasi yang diberikan',
            'options' => [
                'informasi_diberikan' => 'Informasi produk telah diberikan',
                'ketersediaan_disampaikan' => 'Informasi dan ketersediaan produk telah disampaikan',
                'produk_tidak_tersedia' => 'Produk tidak tersedia',
                'permintaan_tidak_dapat_dipenuhi' => 'Permintaan informasi atau produk tidak dapat dipenuhi',
            ],
        ],
        'swamedikasi' => [
            'title' => 'Hasil akhir swamedikasi',
            'description' => 'Tetapkan hasil setelah skrining, pemilihan produk, dan edukasi selesai.',
            'notes_required' => true,
            'notes_label' => 'Ringkasan produk, edukasi, dan batas penggunaan',
            'options' => [
                'produk_dapat_diberikan' => 'Produk dapat diberikan untuk swamedikasi',
                'alternatif_produk_ditawarkan' => 'Alternatif produk yang sesuai telah ditawarkan',
                'produk_tidak_diberikan' => 'Produk tidak diberikan setelah hasil skrining',
                'pasien_tidak_melanjutkan' => 'Pasien memilih tidak melanjutkan permintaan',
            ],
        ],
        'memerlukan_resep' => [
            'title' => 'Hasil akhir kebutuhan resep',
            'description' => 'Catat tindak lanjut setelah diputuskan bahwa permintaan memerlukan resep dokter.',
            'notes_required' => true,
            'notes_label' => 'Ringkasan alasan dan arahan yang diberikan',
            'options' => [
                'resep_diminta' => 'Pasien diminta mengunggah resep yang sesuai',
                'permintaan_tidak_diproses' => 'Permintaan tidak diproses tanpa resep',
                'pasien_diarahkan_ke_dokter' => 'Pasien diarahkan berkonsultasi dengan dokter',
                'pasien_tidak_melanjutkan' => 'Pasien memilih tidak melanjutkan permintaan',
            ],
        ],
        'perlu_rujukan' => [
            'title' => 'Hasil akhir pemeriksaan atau rujukan',
            'description' => 'Catat arahan akhir setelah konsultasi dinilai memerlukan pemeriksaan langsung.',
            'notes_required' => true,
            'notes_label' => 'Ringkasan alasan, tujuan, dan instruksi rujukan',
            'options' => [
                'dirujuk_ke_dokter' => 'Pasien diarahkan untuk pemeriksaan dokter',
                'dirujuk_ke_fasilitas' => 'Pasien diarahkan ke fasilitas pelayanan kesehatan',
                'instruksi_darurat_diberikan' => 'Instruksi mencari pertolongan segera telah diberikan',
                'pasien_tidak_melanjutkan' => 'Pasien memilih tidak melanjutkan konsultasi',
            ],
        ],
    ];

    protected $fillable = [
        'patient_profile_id',
        'nama',
        'umur',
        'no_hp',
        'jenis_konsultasi',
        'service_classification',
        'classified_by_admin_id',
        'classified_at',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'first_admin_reply_at' => 'datetime',
            'last_message_at' => 'datetime',
            'closed_at' => 'datetime',
            'classified_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(
            function (Consultation $consultation): void {
                $consultation->public_id ??=
                    (string) Str::uuid();
            }
        );
    }

    public function getRouteKeyName(): string
    {
        return 'public_id';
    }

    /**
     * Batas waktu isi konsultasi selesai masih dapat dibuka oleh pasien.
     * Konsultasi lama yang belum memiliki closed_at memakai updated_at
     * sebagai fallback agar tetap memiliki batas akses yang konsisten.
     */
    public function patientHistoryAvailableUntil(): ?CarbonInterface
    {
        if ($this->status !== 'selesai') {
            return null;
        }

        $anchor = $this->closed_at
            ?? $this->updated_at
            ?? $this->last_message_at
            ?? $this->created_at;

        if (! $anchor) {
            return null;
        }

        return $anchor->copy()->addDays(
            max(
                1,
                (int) config(
                    'consultation.patient_history_days',
                    60
                )
            )
        );
    }

    public function isPatientHistoryArchived(): bool
    {
        $availableUntil = $this
            ->patientHistoryAvailableUntil();

        return $this->status === 'selesai'
            && $availableUntil !== null
            && $availableUntil->lessThanOrEqualTo(now());
    }

    public function isPatientHistoryAccessible(): bool
    {
        return $this->status === 'aktif'
            || ! $this->isPatientHistoryArchived();
    }


    public static function serviceClassificationOptions(): array
    {
        return self::SERVICE_CLASSIFICATIONS;
    }

    public static function classificationNoticeTemplate(
        string $classification
    ): ?array {
        return self::CLASSIFICATION_NOTICE_TEMPLATES[
            $classification
        ] ?? null;
    }

    public function serviceClassificationLabel(): string
    {
        return self::SERVICE_CLASSIFICATIONS[
            $this->service_classification
        ] ?? 'Belum diklasifikasikan';
    }

    public function classifiedBy()
    {
        return $this->belongsTo(
            Admin::class,
            'classified_by_admin_id'
        );
    }

    public function classificationLogs()
    {
        return $this->hasMany(
            ConsultationClassificationLog::class
        )->latest('id');
    }


    public function classificationNotices()
    {
        return $this->hasMany(
            ConsultationClassificationNotice::class
        )->latest('id');
    }

    public static function screeningTemplate(
        ?string $classification
    ): ?array {
        if ($classification === null) {
            return null;
        }

        return self::SCREENING_TEMPLATES[
            $classification
        ] ?? null;
    }

    public function classificationScreenings()
    {
        return $this->hasMany(
            ConsultationScreening::class
        )->latest('id');
    }

    public function currentClassificationLog(): ?ConsultationClassificationLog
    {
        if (! $this->service_classification) {
            return null;
        }

        if ($this->relationLoaded('classificationLogs')) {
            return $this->classificationLogs
                ->first(
                    fn (ConsultationClassificationLog $log): bool =>
                        $log->new_classification
                            === $this->service_classification
                );
        }

        return $this->classificationLogs()
            ->where(
                'new_classification',
                $this->service_classification
            )
            ->first();
    }

    public function currentScreening(): ?ConsultationScreening
    {
        if (! $this->service_classification) {
            return null;
        }

        $classificationLogId =
            $this->currentClassificationLog()?->id;

        if ($this->relationLoaded('classificationScreenings')) {
            return $this->classificationScreenings
                ->first(
                    function (
                        ConsultationScreening $screening
                    ) use ($classificationLogId): bool {
                        if (
                            $screening->service_classification
                                !== $this->service_classification
                        ) {
                            return false;
                        }

                        if ($classificationLogId === null) {
                            return $screening
                                ->classification_log_id === null;
                        }

                        return (int) $screening
                            ->classification_log_id
                            === (int) $classificationLogId;
                    }
                );
        }

        return $this->classificationScreenings()
            ->where(
                'service_classification',
                $this->service_classification
            )
            ->when(
                $classificationLogId !== null,
                fn ($query) => $query->where(
                    'classification_log_id',
                    $classificationLogId
                ),
                fn ($query) => $query->whereNull(
                    'classification_log_id'
                )
            )
            ->first();
    }

    public function screeningProgress(): array
    {
        $template = self::screeningTemplate(
            $this->service_classification
        );

        if ($template === null) {
            return [
                'available' => false,
                'required' => 0,
                'completed' => 0,
                'is_complete' => false,
                'label' => 'Skrining belum tersedia',
                'class' => 'unavailable',
                'notes_required' => false,
                'notes_complete' => false,
                'answers' => [],
                'notes' => '',
                'screening' => null,
            ];
        }

        $screening = $this->currentScreening();
        $answers = is_array($screening?->answers)
            ? $screening->answers
            : [];

        $requiredKeys = array_keys($template['items']);
        $completed = count(
            array_filter(
                $requiredKeys,
                fn (string $key): bool =>
                    (bool) ($answers[$key] ?? false)
            )
        );

        $notes = trim((string) ($screening?->notes ?? ''));
        $notesRequired = (bool) (
            $template['notes_required'] ?? false
        );
        $notesComplete = ! $notesRequired || $notes !== '';
        $required = count($requiredKeys);
        $isComplete = $required > 0
            && $completed === $required
            && $notesComplete;

        return [
            'available' => true,
            'required' => $required,
            'completed' => $completed,
            'is_complete' => $isComplete,
            'label' => $isComplete
                ? 'Skrining lengkap'
                : 'Skrining '.$completed.'/'.$required,
            'class' => $isComplete
                ? 'complete'
                : ($completed > 0 ? 'partial' : 'pending'),
            'notes_required' => $notesRequired,
            'notes_complete' => $notesComplete,
            'answers' => $answers,
            'notes' => $notes,
            'screening' => $screening,
        ];
    }


    public static function finalOutcomeTemplate(
        ?string $classification
    ): ?array {
        if ($classification === null) {
            return null;
        }

        return self::FINAL_OUTCOME_TEMPLATES[
            $classification
        ] ?? null;
    }

    public function consultationOutcomes()
    {
        return $this->hasMany(
            ConsultationOutcome::class
        )->latest('id');
    }

    public function currentOutcome(): ?ConsultationOutcome
    {
        if (! $this->service_classification) {
            return null;
        }

        $classificationLogId =
            $this->currentClassificationLog()?->id;

        $screeningId = $this->currentScreening()?->id;

        if ($screeningId === null) {
            return null;
        }

        if ($this->relationLoaded('consultationOutcomes')) {
            return $this->consultationOutcomes
                ->first(
                    function (
                        ConsultationOutcome $outcome
                    ) use (
                        $classificationLogId,
                        $screeningId
                    ): bool {
                        if (
                            $outcome->service_classification
                                !== $this->service_classification
                        ) {
                            return false;
                        }

                        if (
                            (int) $outcome->screening_id
                                !== (int) $screeningId
                        ) {
                            return false;
                        }

                        if ($classificationLogId === null) {
                            return $outcome
                                ->classification_log_id === null;
                        }

                        return (int) $outcome
                            ->classification_log_id
                            === (int) $classificationLogId;
                    }
                );
        }

        return $this->consultationOutcomes()
            ->where(
                'service_classification',
                $this->service_classification
            )
            ->where('screening_id', $screeningId)
            ->when(
                $classificationLogId !== null,
                fn ($query) => $query->where(
                    'classification_log_id',
                    $classificationLogId
                ),
                fn ($query) => $query->whereNull(
                    'classification_log_id'
                )
            )
            ->first();
    }

    public function outcomeProgress(): array
    {
        $template = self::finalOutcomeTemplate(
            $this->service_classification
        );

        $screeningProgress = $this->screeningProgress();

        if ($template === null) {
            return [
                'available' => false,
                'is_complete' => false,
                'label' => 'Hasil akhir belum tersedia',
                'class' => 'unavailable',
                'notes_required' => false,
                'outcome' => null,
            ];
        }

        if (! $screeningProgress['is_complete']) {
            return [
                'available' => false,
                'is_complete' => false,
                'label' => 'Menunggu skrining',
                'class' => 'pending',
                'notes_required' => (bool) (
                    $template['notes_required'] ?? false
                ),
                'outcome' => null,
            ];
        }

        $outcome = $this->currentOutcome();

        return [
            'available' => true,
            'is_complete' => $outcome !== null,
            'label' => $outcome?->outcome_label
                ?? 'Hasil akhir belum ditetapkan',
            'class' => $outcome !== null
                ? 'complete'
                : 'pending',
            'notes_required' => (bool) (
                $template['notes_required'] ?? false
            ),
            'outcome' => $outcome,
        ];
    }

    public function archiveCopyRequests()
    {
        return $this->hasMany(
            ConsultationArchiveCopyRequest::class
        )->latest('id');
    }

    public function latestArchiveCopyRequest()
    {
        return $this->hasOne(
            ConsultationArchiveCopyRequest::class
        )->latestOfMany('id');
    }

    public function patientProfile()
    {
        return $this->belongsTo(
            ConsultationPatientProfile::class,
            'patient_profile_id'
        );
    }

    public function guest()
    {
        return $this->belongsTo(
            ConsultationGuest::class,
            'guest_id'
        );
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function lastMessage()
    {
        return $this->hasOne(Message::class)
            ->latestOfMany('id');
    }

    public function adminReads()
    {
        return $this->hasMany(
            AdminConsultationRead::class
        );
    }

    public function analyticsEvents()
    {
        return $this->hasMany(AnalyticsEvent::class);
    }
}
