@forelse ($consultations as $item)
    <a
        class="conversation-item {{
            $activePublicId === $item->public_id
                ? 'active'
                : ''
        }} {{
            $item->unread_count > 0
                ? 'unread'
                : ''
        }}"
        href="{{
            route(
                'admin.inbox.show',
                array_merge(
                    ['consultation' => $item],
                    request()->except(
                        'inbox_page',
                        'active'
                    )
                )
            )
        }}"
        data-conversation-link
        data-public-id="{{ $item->public_id }}"
        data-fragment-url="{{
            route('admin.inbox.conversation', $item)
        }}"
    >
        <span class="conversation-avatar" aria-hidden="true">
            {{ mb_strtoupper(mb_substr($item->nama, 0, 1)) }}
        </span>

        <span class="conversation-copy">
            <span class="conversation-row">
                <strong class="conversation-name">
                    {{ $item->nama }}
                </strong>

                <time
                    class="conversation-time"
                    title="{{ $item->last_activity_title }}"
                >
                    {{ $item->last_activity_label }}
                </time>
            </span>

            <span class="conversation-meta-row">
                <span
                    class="state-chip state-{{
                        $item->inbox_state
                    }}"
                >
                    {{ $item->inbox_state_label }}
                </span>

                <span class="type-chip">
                    {{
                        $item->jenis_konsultasi === 'resep'
                            ? 'Resep'
                            : 'Non Resep'
                    }}
                </span>
            </span>

            <span class="conversation-row preview-row">
                <span class="conversation-preview">
                    @if ($item->lastMessage)
                        <span class="preview-sender">
                            {{
                                $item->lastMessage->sender === 'admin'
                                    ? 'Anda: '
                                    : ''
                            }}
                        </span>
                    @endif

                    {{ $item->last_message_preview }}
                </span>

                @if ($item->unread_count > 0)
                    <span
                        class="unread-badge"
                        aria-label="{{
                            $item->unread_count
                        }} pesan belum dibaca"
                    >
                        {{
                            $item->unread_count > 99
                                ? '99+'
                                : $item->unread_count
                        }}
                    </span>
                @endif
            </span>
        </span>
    </a>
@empty
    <div class="conversation-empty">
        <span class="empty-icon">💬</span>
        <strong>Tidak ada percakapan</strong>
        <p>
            Belum ada konsultasi yang sesuai dengan
            filter atau pencarian ini.
        </p>
    </div>
@endforelse
