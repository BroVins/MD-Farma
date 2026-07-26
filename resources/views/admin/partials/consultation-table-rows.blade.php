@forelse ($consultations as $item)
    @php
        $created = $item->created_at
            ->copy()
            ->timezone($timezone);

        $last = $item->last_message_at
            ?->copy()
            ->timezone($timezone);

        $seconds = $item->first_admin_reply_at
            ? (int) $item->created_at
                ->diffInSeconds(
                    $item->first_admin_reply_at
                )
            : null;

        $response = $seconds === null
            ? 'Belum dibalas'
            : ($seconds < 60
                ? $seconds.' detik'
                : (intdiv($seconds, 60) < 60
                    ? intdiv($seconds, 60).' menit'
                    : intdiv(
                        intdiv($seconds, 60),
                        60
                    ).' jam'));
    @endphp

    <tr
        id="consultation-row-{{ $item->public_id }}"
        data-consultation-row
    >
        <td>
            <span class="patient-name">
                {{ $item->nama }}
            </span>
            <span class="sub">
                {{ $item->no_hp }} ·
                {{ $item->umur }} tahun
            </span>
        </td>
        <td>
            <span
                class="badge {{
                    $item->jenis_konsultasi === 'resep'
                        ? ''
                        : 'gray'
                }}"
            >
                {{
                    $item->jenis_konsultasi === 'resep'
                        ? 'Resep'
                        : 'Non Resep'
                }}
            </span>
        </td>
        <td>
            {{
                $created
                    ->locale('id')
                    ->isoFormat('D MMM YYYY')
            }}
            <span class="sub">
                {{ $created->format('H.i') }} WIB
            </span>
        </td>
        <td>
            @if ($last)
                {{
                    $last
                        ->locale('id')
                        ->isoFormat('D MMM YYYY')
                }}
                <span class="sub">
                    {{ $last->format('H.i') }} WIB
                </span>
            @else
                Belum ada pesan
            @endif
        </td>
        <td>{{ $response }}</td>
        <td>
            <span
                class="badge {{
                    $item->status === 'aktif'
                        ? 'amber'
                        : 'gray'
                }}"
            >
                {{ ucfirst($item->status) }}
            </span>
        </td>
        <td>{{ $item->messages_count }}</td>
        <td>
            <a
                class="chat-link"
                href="{{ route('chat.show', $item) }}"
            >
                Buka Chat →
            </a>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="8" class="empty">
            Tidak ada konsultasi pada filter ini.
        </td>
    </tr>
@endforelse
