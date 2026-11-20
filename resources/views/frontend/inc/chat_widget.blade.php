@php
    $chatbot_status = get_setting('chatbot_status');
    $chatbot_messenger_link = get_setting('chatbot_messenger_link');
    $chatbot_whatsapp_number = get_setting('chatbot_whatsapp_number');
    $chatbot_whatsapp_clean = ($chatbot_whatsapp_number != null) ? 'https://wa.me/' . preg_replace('/[^0-9]/', '', $chatbot_whatsapp_number) : null;
    $has_messenger = ($chatbot_messenger_link != null && $chatbot_messenger_link != '');
    $has_whatsapp = ($chatbot_whatsapp_clean != null);
@endphp

@if ($chatbot_status == 1 && ($has_messenger || $has_whatsapp))
    <div class="chatbot-widget" id="chatbotWidget">
        <div class="chatbot-backdrop" id="chatbotBackdrop"></div>
        <div class="chatbot-popup" id="chatbotPopup">
            <button type="button" class="chatbot-popup-close" data-chatbot-close aria-label="{{ translate('Close') }}">
                <i class="las la-times"></i>
            </button>
            <div class="chatbot-popup-head">
                <span class="chatbot-popup-badge">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/>
                    </svg>
                </span>
                <div class="chatbot-popup-heading">
                    <strong>{{ translate('Chat With Us') }}</strong>
                    <small>{{ translate('We usually reply within minutes') }}</small>
                </div>
            </div>
            <div class="chatbot-popup-body">
                <p class="chatbot-popup-note">{{ translate('Please select your preferred chat platform') }}</p>
                <div class="chatbot-option-grid">
                    @if ($has_messenger)
                        <a href="{{ $chatbot_messenger_link }}" target="_blank" rel="noopener" class="chatbot-option chatbot-option-messenger">
                            <span class="chatbot-option-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <path d="M12 2C6.36 2 2 6.13 2 11.7c0 2.91 1.19 5.44 3.14 7.17V22l2.88-1.58c.86.24 1.77.36 2.73.36h.25c5.64 0 10-4.13 10-9.7C20.75 6.13 16.36 2 12 2zm4.2 7.7l-2.3 3.46c-.35.53-.94.59-1.32.13l-2.19-2.62-3.87 3.93c-.27.28-.67-.05-.46-.38l2.47-3.73c.36-.53.94-.59 1.32-.13l2.19 2.62 3.87-3.93c.27-.28.67.05.46.38z"/>
                                </svg>
                            </span>
                            <span class="chatbot-option-text">
                                <strong>{{ translate('Messenger') }}</strong>
                                <small>{{ translate('Chat With Us') }}</small>
                            </span>
                        </a>
                    @endif
                    @if ($has_whatsapp)
                        <a href="{{ $chatbot_whatsapp_clean }}" target="_blank" rel="noopener" class="chatbot-option chatbot-option-whatsapp">
                            <span class="chatbot-option-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                </svg>
                            </span>
                            <span class="chatbot-option-text">
                                <strong>{{ translate('WhatsApp') }}</strong>
                                <small>{{ translate('Chat With Us') }}</small>
                            </span>
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <button type="button" class="chatbot-launcher" id="chatbotLauncher" aria-expanded="false" aria-label="{{ translate('Open chat') }}" title="{{ translate('Chat Now') }}">
            <span class="chatbot-launcher-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/>
                </svg>
            </span>
            <span class="sr-only">{{ translate('Chat Now') }}</span>
        </button>
    </div>

    <script>
        (function () {
            var widget = document.getElementById('chatbotWidget');
            if (!widget) return;
            var launcher = document.getElementById('chatbotLauncher');

            function closePopup() {
                widget.classList.remove('open');
                if (launcher) launcher.setAttribute('aria-expanded', 'false');
            }

            function togglePopup(e) {
                e.stopPropagation();
                var willOpen = !widget.classList.contains('open');
                widget.classList.toggle('open', willOpen);
                if (launcher) launcher.setAttribute('aria-expanded', String(willOpen));
            }

            if (launcher) launcher.addEventListener('click', togglePopup);

            widget.querySelectorAll('[data-chatbot-close]').forEach(function (btn) {
                btn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    closePopup();
                });
            });

            widget.querySelectorAll('.chatbot-backdrop').forEach(function (backdrop) {
                backdrop.addEventListener('click', closePopup);
            });

            document.addEventListener('click', function (e) {
                if (!widget.classList.contains('open')) return;
                if (!widget.contains(e.target)) closePopup();
            });
        })();
    </script>
@endif
