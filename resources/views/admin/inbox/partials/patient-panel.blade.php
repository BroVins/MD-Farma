@php
    $created = $consultation->created_at
        ->copy()
        ->timezone($timezone);

    $lastActivity = ($consultation->last_message_at
        ?? $consultation->created_at)
        ->copy()
        ->timezone($timezone);

    $responseSeconds = $consultation->first_admin_reply_at
        ? (int) $consultation->created_at
            ->diffInSeconds($consultation->first_admin_reply_at)
        : null;

    $responseLabel = $responseSeconds === null
        ? 'Belum ada balasan admin'
        : ($responseSeconds < 60
            ? $responseSeconds.' detik'
            : (intdiv($responseSeconds, 60) < 60
                ? intdiv($responseSeconds, 60).' menit'
                : intdiv(intdiv($responseSeconds, 60), 60)
                    .' jam '
                    .(intdiv($responseSeconds, 60) % 60)
                    .' menit'));
@endphp

<aside class="patient-detail-card">
    <div class="patient-profile">
        <span class="conversation-avatar profile" aria-hidden="true">
            {{ mb_strtoupper(mb_substr($consultation->nama, 0, 1)) }}
        </span>

        <h2>{{ $consultation->nama }}</h2>
        <p>
            {{
                $consultation->jenis_konsultasi === 'resep'
                    ? 'Konsultasi resep dokter'
                    : 'Konsultasi non resep'
            }}
        </p>

        <span
            class="state-chip large state-{{
                $consultation->inbox_state
            }}"
        >
            {{ $consultation->inbox_state_label }}
        </span>
    </div>

    <dl class="patient-facts">
        <div>
            <dt>Umur</dt>
            <dd>{{ $consultation->umur }} tahun</dd>
        </div>

        <div>
            <dt>Nomor HP</dt>
            <dd>{{ $consultation->no_hp }}</dd>
        </div>

        <div>
            <dt>Dibuat</dt>
            <dd
                title="{{
                    $created
                        ->locale('id')
                        ->isoFormat(
                            'dddd, D MMMM YYYY [pukul] HH.mm.ss'
                        )
                }} WIB"
            >
                {{
                    $created
                        ->locale('id')
                        ->isoFormat('D MMM YYYY')
                }}
                · {{ $created->format('H.i') }} WIB
            </dd>
        </div>

        <div>
            <dt>Aktivitas terakhir</dt>
            <dd>
                {{
                    $lastActivity
                        ->locale('id')
                        ->isoFormat('D MMM YYYY')
                }}
                · {{ $lastActivity->format('H.i') }} WIB
            </dd>
        </div>

        <div>
            <dt>Respons pertama</dt>
            <dd>{{ $responseLabel }}</dd>
        </div>

        <div>
            <dt>Total pesan</dt>
            <dd>{{ $consultation->messages->count() }} pesan</dd>
        </div>
    </dl>

    <div class="privacy-note">
        <strong>Data konsultasi</strong>
        <p>
            Informasi pasien hanya ditampilkan kepada admin yang
            sudah terautentikasi. Hindari membagikan tangkapan layar
            atau data pasien di luar kebutuhan akademik.
        </p>
    </div>
</aside>
