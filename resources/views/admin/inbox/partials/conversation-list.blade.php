<style id="mdf-admin-inbox-polished-ui">
    /*
     * MD Farma Admin Inbox — permanent visual system
     * Loaded from the conversation list partial so the same rules apply
     * to every consultation loaded through AJAX or realtime.
     */

    :root {
        --mdf-surface: #ffffff;
        --mdf-canvas: #f7faf9;
        --mdf-canvas-soft: #f1f7f4;
        --mdf-border: #dde8e3;
        --mdf-text: #172033;
        --mdf-muted: #64748b;
        --mdf-green: #047857;
        --mdf-green-dark: #065f46;
        --mdf-green-soft: #e9f8f1;
        --mdf-green-bubble: #dcf7e9;
        --mdf-danger: #b42318;
        --mdf-danger-soft: #fff1f0;
        --mdf-shadow-soft: 0 8px 24px rgba(15, 23, 42, .065);
    }

    /* Top navigation: more consistent and less emoji-like. */
    .brand-mark,
    .nav-link > span:first-child,
    .notification-toggle {
        display: inline-flex;
        align-items: center;
        gap: 7px;
    }

    .brand-mark svg,
    .nav-link svg,
    .notification-toggle svg,
    .header-action svg,
    .image-picker svg,
    .send-reply svg {
        width: 17px;
        height: 17px;
        flex: 0 0 auto;
        stroke: currentColor;
    }

    .brand-mark svg {
        width: 20px;
        height: 20px;
    }

    /* Sidebar: filters no longer get visually cut off. */
    .state-tabs {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 6px;
        overflow: visible;
        padding-bottom: 0;
    }

    .state-tab {
        min-width: 0;
        justify-content: center;
        padding: 7px 6px;
        text-align: center;
        line-height: 1.15;
    }

    .conversation-item {
        min-height: 94px;
        padding: 13px 15px;
    }

    .conversation-item.active {
        background: linear-gradient(90deg, #ecfbf4, #f5fcf8);
    }

    .conversation-item:hover {
        background: #f7faf9;
    }

    /* Main canvas: less green, more neutral and comfortable for long sessions. */
    .conversation-pane,
    .conversation-shell {
        background: var(--mdf-canvas);
    }

    .conversation-shell {
        grid-template-rows: 76px minmax(0, 1fr) auto;
    }

    .conversation-header {
        min-height: 76px;
        padding: 11px 18px;
        gap: 12px;
        border-bottom: 1px solid var(--mdf-border);
        background: rgba(255, 255, 255, .98);
        box-shadow: 0 5px 18px rgba(15, 23, 42, .035);
    }

    .conversation-avatar.large {
        width: 44px;
        height: 44px;
        box-shadow: 0 7px 18px rgba(5, 150, 105, .16);
    }

    .conversation-heading {
        gap: 5px;
    }

    .conversation-heading strong {
        font-size: 15px;
        letter-spacing: -.01em;
    }

    .conversation-heading > span {
        display: none;
    }

    .conversation-heading-meta {
        display: flex;
        min-width: 0;
        flex-wrap: wrap;
        align-items: center;
        gap: 6px;
    }

    .conversation-heading-meta .state-chip,
    .conversation-heading-meta .type-chip {
        min-height: 22px;
        padding: 4px 8px;
        font-size: 9px;
    }

    .conversation-started {
        color: var(--mdf-muted);
        font-size: 9.5px;
        white-space: nowrap;
    }

    .conversation-header-actions {
        align-items: center;
        gap: 8px;
    }

    .header-status-form {
        margin: 0;
    }

    .header-action {
        min-height: 36px;
        gap: 6px;
        padding: 8px 11px;
        border-color: var(--mdf-border);
        border-radius: 10px;
        font-size: 10px;
    }

    .header-action:hover {
        border-color: #b8d9ca;
        background: #f2fbf6;
    }

    .header-action.finish-action {
        border-color: #f3c3bf;
        color: var(--mdf-danger);
        background: var(--mdf-danger-soft);
    }

    .header-action.finish-action:hover {
        border-color: #e89b94;
        background: #ffe8e5;
    }

    /* Permanent bubble system: applies to every user and every conversation. */
    .message-stream {
        padding: 24px clamp(24px, 5vw, 72px);
        background:
            linear-gradient(rgba(248, 251, 250, .965), rgba(248, 251, 250, .965)),
            radial-gradient(
                circle at 16px 16px,
                rgba(5, 150, 105, .055) 1px,
                transparent 1.2px
            );
        background-size: auto, 34px 34px;
    }

    .date-divider {
        margin: 4px 0 19px;
    }

    .date-divider span {
        padding: 7px 11px;
        border-color: #dfe9e4;
        color: #526174;
        background: rgba(255, 255, 255, .94);
        font-size: 9px;
        box-shadow: 0 5px 15px rgba(15, 23, 42, .045);
    }

    .message-bubble {
        width: fit-content;
        min-width: 138px;
        max-width: min(68%, 680px);
        margin-bottom: 11px;
        padding: 11px 13px 9px;
        border: 1px solid transparent;
        border-radius: 18px;
        box-shadow: var(--mdf-shadow-soft);
    }

    .message-bubble.patient {
        margin-right: auto;
        border-color: #e3ebe7;
        border-top-left-radius: 6px;
        background: var(--mdf-surface);
    }

    .message-bubble.admin {
        margin-left: auto;
        border-color: #c7ead9;
        border-top-right-radius: 6px;
        background:
            linear-gradient(145deg, var(--mdf-green-bubble), #e8fbf2);
    }

    .message-sender {
        margin-bottom: 5px;
        color: var(--mdf-green-dark);
        font-size: 9.5px;
        letter-spacing: .01em;
    }

    .message-bubble p {
        color: var(--mdf-text);
        font-size: 13px;
        line-height: 1.55;
    }

    .message-time {
        margin-top: 7px;
        color: #738195;
        font-size: 8.5px;
    }

    /*
     * Consecutive messages from the same sender are grouped automatically.
     * This also covers messages appended by realtime JavaScript.
     */
    .message-bubble.admin + .message-bubble.admin,
    .message-bubble.patient + .message-bubble.patient {
        margin-top: -6px;
    }

    .message-bubble.admin + .message-bubble.admin .message-sender,
    .message-bubble.patient + .message-bubble.patient .message-sender {
        display: none;
    }

    .message-bubble.admin + .message-bubble.admin {
        border-top-right-radius: 18px;
    }

    .message-bubble.patient + .message-bubble.patient {
        border-top-left-radius: 18px;
    }

    .message-attachment img {
        width: min(320px, 100%);
        max-height: 320px;
        border: 1px solid rgba(15, 23, 42, .07);
        border-radius: 13px;
        box-shadow: 0 5px 15px rgba(15, 23, 42, .07);
    }

    .document-attachment {
        display: flex;
        min-width: min(280px, 100%);
        align-items: center;
        gap: 11px;
        margin-top: 8px;
        padding: 11px 12px;
        border: 1px solid rgba(5, 150, 105, .17);
        border-radius: 13px;
        color: inherit;
        background: rgba(255, 255, 255, .76);
        text-decoration: none;
    }

    .document-attachment:hover {
        border-color: rgba(5, 150, 105, .35);
        background: rgba(255, 255, 255, .94);
    }

    .document-icon {
        display: grid;
        width: 40px;
        height: 40px;
        place-items: center;
        flex: 0 0 auto;
        border-radius: 11px;
        color: var(--mdf-green);
        background: #dff7ec;
    }

    .document-icon svg {
        width: 19px;
        height: 19px;
        stroke: currentColor;
    }

    .document-copy {
        min-width: 0;
        flex: 1;
    }

    .document-copy strong {
        display: block;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        font-size: 11px;
    }

    .document-copy small {
        display: block;
        margin-top: 3px;
        color: var(--mdf-muted);
        font-size: 9px;
        text-transform: uppercase;
    }

    /* Composer: one integrated control with clear action hierarchy. */
    .composer-area {
        padding: 11px 15px 13px;
        border-top: 1px solid var(--mdf-border);
        background: rgba(255, 255, 255, .985);
        box-shadow: 0 -8px 24px rgba(15, 23, 42, .035);
    }

    .reply-form {
        grid-template-columns: 44px minmax(0, 1fr) auto;
        gap: 8px;
        align-items: end;
    }

    .image-picker {
        width: 44px;
        height: 46px;
        border: 1px solid var(--mdf-border);
        border-radius: 12px;
        color: var(--mdf-green);
        background: #f8fbfa;
    }

    .image-picker:hover {
        border-color: #a8d7c1;
        background: #eef9f3;
    }

    .reply-form textarea {
        min-height: 46px;
        max-height: 128px;
        padding: 12px 14px;
        border-color: var(--mdf-border);
        border-radius: 12px;
        background: #f8fbfa;
        font-size: 13px;
    }

    .reply-form textarea:focus {
        border-color: #6ac49c;
        box-shadow: 0 0 0 3px rgba(5, 150, 105, .11);
    }

    .send-reply {
        min-width: 94px;
        height: 46px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        border-radius: 12px;
        background: linear-gradient(135deg, #07845e, #059669);
        box-shadow: 0 7px 17px rgba(5, 150, 105, .2);
        font-size: 11px;
    }

    .send-reply:hover {
        background: linear-gradient(135deg, #066f51, #047857);
    }

    /* The legacy finish action under the composer is intentionally hidden. */
    .composer-area > .status-inline-form {
        display: none;
    }

    @media (max-width: 1180px) {
        .message-stream {
            padding-inline: clamp(18px, 3vw, 38px);
        }

        .message-bubble {
            max-width: min(74%, 640px);
        }
    }

    @media (max-width: 820px) {
        .state-tabs {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .conversation-shell {
            grid-template-rows: 70px minmax(0, 1fr) auto;
        }

        .conversation-header {
            min-height: 70px;
            padding: 9px 12px;
        }

        .conversation-started {
            display: none;
        }

        .header-action.finish-action span {
            display: none;
        }

        .message-stream {
            padding: 16px 13px;
        }

        .message-bubble {
            min-width: 112px;
            max-width: 88%;
            padding: 10px 12px 8px;
        }

        .reply-form {
            grid-template-columns: 42px minmax(0, 1fr) 48px;
        }

        .image-picker {
            width: 42px;
            height: 44px;
        }

        .send-reply {
            min-width: 48px;
            width: 48px;
            height: 44px;
            padding: 0;
        }

        .send-reply span {
            display: none;
        }
    }

    @media (max-width: 560px) {
        .conversation-heading-meta .type-chip {
            display: none;
        }

        .conversation-header-actions {
            gap: 5px;
        }

        .header-action {
            padding: 7px 9px;
        }

        .reply-form {
            grid-template-columns: 42px minmax(0, 1fr) 46px !important;
        }

        .send-reply {
            grid-column: auto !important;
            width: 46px;
            min-width: 46px;
        }
    }
</style>

<script id="mdf-admin-inbox-icon-enhancement">
    (() => {
        const applyIcons = () => {
            const svg = {
                brand: `
                    <svg viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2.2"
                        stroke-linecap="round">
                        <path d="M12 5v14M5 12h14"/>
                    </svg>`,
                inbox: `
                    <svg viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15a4 4 0 0 1-4 4H8l-5 3V7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4z"/>
                    </svg>`,
                chart: `
                    <svg viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2"
                        stroke-linecap="round">
                        <path d="M4 20V10M10 20V4M16 20v-7M22 20H2"/>
                    </svg>`,
                bell: `
                    <svg viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"/>
                        <path d="M10 21h4"/>
                    </svg>`,
            };

            const brand = document.querySelector('.brand-mark');
            if (brand && !brand.querySelector('svg')) {
                brand.innerHTML = svg.brand;
            }

            const links = document.querySelectorAll('.topbar-nav .nav-link');
            const inboxLabel = links[0]?.querySelector(':scope > span:first-child');
            const chartLabel = links[1]?.querySelector(':scope > span:first-child');

            if (inboxLabel && !inboxLabel.querySelector('svg')) {
                inboxLabel.innerHTML = `${svg.inbox}<span>Inbox</span>`;
            }

            if (chartLabel && !chartLabel.querySelector('svg')) {
                chartLabel.innerHTML = `${svg.chart}<span>Analitik</span>`;
            }

            const notification = document.querySelector('.notification-toggle');
            if (notification && !notification.querySelector('svg')) {
                const label = notification.querySelector('span')?.textContent
                    ?.trim() || 'Notifikasi';
                notification.innerHTML = `${svg.bell}<span>${label}</span>`;
            }
        };

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', applyIcons, {
                once: true,
            });
        } else {
            applyIcons();
        }
    })();
</script>

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
                ),
                false
            )
        }}"
        data-conversation-link
        data-public-id="{{ $item->public_id }}"
        data-fragment-url="{{
            route(
                'admin.inbox.conversation',
                $item,
                false
            )
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
