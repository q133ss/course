@php
    $videoModalPreorderDiscount = 30;
    $authenticatedUser = auth()->user();
@endphp
<div
    id="video-modal"
    class="fixed inset-0 z-50 hidden items-center justify-center p-4"
    data-authenticated="{{ auth()->check() ? 'true' : 'false' }}"
    data-auth-name="{{ e($authenticatedUser->name ?? '') }}"
    data-preorder-discount="{{ $videoModalPreorderDiscount }}"
>
    <div class="video-modal-backdrop absolute inset-0 bg-gray-900/60"></div>
    <div id="video-modal-card" class="relative bg-white rounded-2xl shadow-2xl w-full max-w-3xl mx-auto overflow-hidden">
        <button type="button" id="video-modal-close" class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 text-2xl leading-none" aria-label="Закрыть">
            &times;
        </button>
        <div class="p-6 md:p-8 space-y-4">
            <div id="video-modal-course" class="text-xs font-semibold uppercase tracking-wide text-blue-600 hidden"></div>
            <h2 id="video-modal-title" class="text-2xl font-semibold text-gray-900"></h2>
            <p id="video-modal-short-description" class="text-sm text-gray-600 hidden whitespace-pre-line"></p>
            <div id="video-modal-player-section" class="video-modal-section hidden space-y-4">
                <div class="aspect-video bg-black/5 rounded-xl overflow-hidden">
                    <video id="video-modal-video" controls class="w-full h-full bg-black text-white rounded-xl">
                        <source id="video-modal-video-source" src="" type="video/mp4">
                        Ваш браузер не поддерживает воспроизведение видео.
                    </video>
                </div>
                <div
                    id="video-modal-player-preorder-cta"
                    class="hidden rounded-xl border border-blue-100 bg-blue-50/70 p-4"
                >
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div class="space-y-1 text-sm text-blue-900">
                            <div>
                                Курс стартует
                                <span id="video-modal-player-preorder-start-date" class="font-semibold"></span>
                                <span id="video-modal-player-preorder-start-diff" class="font-semibold text-blue-700"></span>
                            </div>
                            <div class="text-xs text-blue-800">
                                Оставьте заявку заранее и получите
                                <span id="video-modal-player-preorder-discount" class="font-semibold"></span>% скидки в день запуска.
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <button
                                type="button"
                                id="video-modal-player-preorder-button"
                                class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700"
                            >
                                Оставить бесплатную заявку на предзаказ
                            </button>
                        </div>
                    </div>
                </div>
                <div
                    id="video-modal-full-description"
                    class="text-sm text-gray-700 hidden max-h-80 overflow-y-auto pr-1 space-y-4"
                ></div>
            </div>
            <div id="video-modal-pay-section" class="video-modal-section hidden bg-yellow-50 border border-yellow-200 rounded-xl p-4 space-y-3">
                <div class="text-sm text-yellow-800">
                    <span class="font-semibold block">Доступ ограничен.</span>
                    Оплатите курс <span id="video-modal-pay-course-title" class="font-semibold"></span>, чтобы получить доступ к уроку.
                </div>
                <a id="video-modal-pay-link" href="#" class="inline-flex items-center justify-center px-4 py-2 rounded-lg bg-yellow-500 text-white font-semibold hover:bg-yellow-600">
                    Перейти к оплате
                </a>
            </div>
            <div id="video-modal-preorder-section" class="video-modal-section hidden space-y-4">
                <div class="rounded-xl bg-blue-50 p-4 text-blue-900 space-y-2">
                    <p class="text-base font-semibold">Курс «<span id="video-modal-preorder-course"></span>» скоро стартует!</p>
                    <p class="text-sm">
                        Премьера запланирована на <span id="video-modal-preorder-start-date" class="font-semibold"></span><span id="video-modal-preorder-start-diff" class="text-blue-700"></span>.
                    </p>
                    <p class="text-sm">Оставьте заявку и получите <span id="video-modal-preorder-discount" class="font-semibold">{{ $videoModalPreorderDiscount }}</span>% скидки на оплату в день запуска.</p>
                </div>
                <form id="video-modal-preorder-form" class="space-y-4">
                    <div id="video-modal-preorder-name-field" class="space-y-1">
                        <label for="video-modal-preorder-name" class="text-sm font-semibold text-gray-700">Имя</label>
                        <input
                            id="video-modal-preorder-name"
                            name="name"
                            type="text"
                            class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                            placeholder="Как к вам обращаться"
                        >
                    </div>
                    <div class="space-y-2">
                        <label for="video-modal-preorder-contact" class="text-sm font-semibold text-gray-700">Как с вами связаться</label>
                        <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                            <div class="sm:w-40">
                                <label for="video-modal-preorder-contact-type" class="sr-only">Тип связи</label>
                                <select
                                    id="video-modal-preorder-contact-type"
                                    name="contact_type"
                                    class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                                >
                                    <option value="phone" selected>Телефон</option>
                                    <option value="telegram">Telegram</option>
                                </select>
                            </div>
                            <div class="flex-1">
                                <input
                                    id="video-modal-preorder-contact"
                                    name="contact"
                                    type="text"
                                    required
                                    class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                                    placeholder="+9(999)999-99-99"
                                >
                            </div>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500">Мы напомним о старте и пришлём промокод со скидкой перед открытием доступа.</p>
                    <div id="video-modal-preorder-error" class="hidden rounded-xl border border-rose-200 bg-rose-50 px-3 py-2 text-sm text-rose-700"></div>
                    <div id="video-modal-preorder-success" class="hidden rounded-xl border border-emerald-200 bg-emerald-50 px-3 py-2 text-sm text-emerald-700"></div>
                    <div class="flex flex-wrap items-center gap-3">
                        <button type="submit" id="video-modal-preorder-submit" class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                            Оставить заявку
                        </button>
                        <span class="text-xs text-gray-500">Указанные данные будут доступны в профиле.</span>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        if (window.__courseVideoModalInitialized) {
            return;
        }

        window.__courseVideoModalInitialized = true;

        const initVideoModal = () => {
            const body = document.body;
            const videoItems = Array.from(document.querySelectorAll('.video-item'));
            const videoModal = document.getElementById('video-modal');
            const closeButton = document.getElementById('video-modal-close');
            const courseTitleEl = document.getElementById('video-modal-course');
            const videoTitleEl = document.getElementById('video-modal-title');
            const shortDescriptionEl = document.getElementById('video-modal-short-description');
            const fullDescriptionEl = document.getElementById('video-modal-full-description');
            const playerSection = document.getElementById('video-modal-player-section');
            const playerPreorderCta = document.getElementById('video-modal-player-preorder-cta');
            const playerPreorderCtaStartDateEl = document.getElementById('video-modal-player-preorder-start-date');
            const playerPreorderCtaStartDiffEl = document.getElementById('video-modal-player-preorder-start-diff');
            const playerPreorderCtaDiscountEl = document.getElementById('video-modal-player-preorder-discount');
            const playerPreorderCtaButton = document.getElementById('video-modal-player-preorder-button');
            const paySection = document.getElementById('video-modal-pay-section');
            const payCourseTitleEl = document.getElementById('video-modal-pay-course-title');
            const payLinkEl = document.getElementById('video-modal-pay-link');
            const preorderSection = document.getElementById('video-modal-preorder-section');
            const preorderCourseTitleEl = document.getElementById('video-modal-preorder-course');
            const preorderStartDateEl = document.getElementById('video-modal-preorder-start-date');
            const preorderStartDiffEl = document.getElementById('video-modal-preorder-start-diff');
            const preorderDiscountEl = document.getElementById('video-modal-preorder-discount');
            const preorderForm = document.getElementById('video-modal-preorder-form');
            const preorderNameField = document.getElementById('video-modal-preorder-name-field');
            const preorderNameInput = document.getElementById('video-modal-preorder-name');
            const preorderContactInput = document.getElementById('video-modal-preorder-contact');
            const preorderContactTypeSelect = document.getElementById('video-modal-preorder-contact-type');
            const preorderErrorEl = document.getElementById('video-modal-preorder-error');
            const preorderSuccessEl = document.getElementById('video-modal-preorder-success');
            const preorderSubmitButton = document.getElementById('video-modal-preorder-submit');
            const videoElement = document.getElementById('video-modal-video');
            const videoSource = document.getElementById('video-modal-video-source');
            const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
            const csrfToken = csrfTokenMeta ? csrfTokenMeta.getAttribute('content') : '';
            const isAuthenticated = videoModal?.dataset.authenticated === 'true';
            const authenticatedName = videoModal?.dataset.authName || '';
            const preorderDiscountValue = videoModal?.dataset.preorderDiscount || '30';
            const CONTACT_TYPE_PHONE = 'phone';
            const CONTACT_TYPE_TELEGRAM = 'telegram';
            const DEFAULT_CONTACT_TYPE = CONTACT_TYPE_PHONE;
            let activePreorderUrl = null;
            let activeItem = null;
            let activeCourseId = null;
            let activePreorderId = null;

            const storage = (() => {
                try {
                    const testKey = '__video_modal_preorder_test__';
                    window.localStorage.setItem(testKey, '1');
                    window.localStorage.removeItem(testKey);

                    return window.localStorage;
                } catch (error) {
                    return null;
                }
            })();

            const storageKeyForCourse = (courseId) => {
                if (!courseId) {
                    return null;
                }

                return `course-preorder:${courseId}`;
            };

            const loadStoredPreorder = (courseId) => {
                const key = storageKeyForCourse(courseId);

                if (!storage || !key) {
                    return null;
                }

                try {
                    const raw = storage.getItem(key);

                    if (!raw) {
                        return null;
                    }

                    return JSON.parse(raw);
                } catch (error) {
                    return null;
                }
            };

            const saveStoredPreorder = (courseId, data) => {
                const key = storageKeyForCourse(courseId);

                if (!storage || !key) {
                    return;
                }

                try {
                    const existing = loadStoredPreorder(courseId) || {};
                    storage.setItem(key, JSON.stringify({ ...existing, ...data }));
                } catch (error) {
                    // Игнорируем ошибки записи в localStorage.
                }
            };

            if (!videoModal) {
                return;
            }

            const hideElement = (element) => {
                if (!element) return;
                element.classList.add('hidden');
            };

            const showElement = (element) => {
                if (!element) return;
                element.classList.remove('hidden');
            };

            const copyButtonClassStates = {
                default:
                    'absolute top-3 right-3 inline-flex items-center gap-1 rounded-lg bg-slate-700/80 px-2.5 py-1 text-xs font-semibold text-white transition hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500',
                success:
                    'absolute top-3 right-3 inline-flex items-center gap-1 rounded-lg bg-emerald-600 px-2.5 py-1 text-xs font-semibold text-white transition hover:bg-emerald-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500',
                error:
                    'absolute top-3 right-3 inline-flex items-center gap-1 rounded-lg bg-rose-600 px-2.5 py-1 text-xs font-semibold text-white transition hover:bg-rose-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500',
            };

            const copyTextToClipboard = async (text) => {
                if (!text) {
                    return false;
                }

                if (navigator?.clipboard?.writeText) {
                    try {
                        await navigator.clipboard.writeText(text);
                        return true;
                    } catch (error) {
                        // Попробуем резервный вариант ниже.
                    }
                }

                const textarea = document.createElement('textarea');
                textarea.value = text;
                textarea.setAttribute('readonly', '');
                textarea.style.position = 'fixed';
                textarea.style.top = '-9999px';
                textarea.style.opacity = '0';

                document.body.appendChild(textarea);

                textarea.focus();
                textarea.select();

                let copied = false;

                try {
                    copied = document.execCommand('copy');
                } catch (error) {
                    copied = false;
                }

                document.body.removeChild(textarea);

                return copied;
            };

            const createCopyButton = (textToCopy) => {
                const button = document.createElement('button');
                button.type = 'button';
                button.className = copyButtonClassStates.default;
                button.textContent = 'Копировать';

                button.addEventListener('click', async () => {
                    const resetTimeoutId = button.dataset.resetTimeoutId;

                    if (resetTimeoutId) {
                        window.clearTimeout(Number(resetTimeoutId));
                        delete button.dataset.resetTimeoutId;
                    }

                    const successful = await copyTextToClipboard(textToCopy);

                    if (successful) {
                        button.textContent = 'Скопировано';
                        button.className = copyButtonClassStates.success;
                    } else {
                        button.textContent = 'Не удалось';
                        button.className = copyButtonClassStates.error;
                    }

                    const timeoutId = window.setTimeout(() => {
                        button.textContent = 'Копировать';
                        button.className = copyButtonClassStates.default;
                        delete button.dataset.resetTimeoutId;
                    }, 2000);

                    button.dataset.resetTimeoutId = String(timeoutId);
                });

                return button;
            };

            const createCodeBlock = (code) => {
                const wrapper = document.createElement('div');
                wrapper.className = 'relative';

                const pre = document.createElement('pre');
                pre.className = 'whitespace-pre overflow-x-auto rounded-xl bg-slate-900 px-4 py-4 pr-12 text-xs leading-relaxed text-slate-100 shadow-sm font-mono';
                pre.textContent = code;

                const copyButton = createCopyButton(code);

                wrapper.appendChild(pre);
                wrapper.appendChild(copyButton);

                return wrapper;
            };

            const createTextBlock = (text) => {
                const trimmed = text.replace(/^[\n\r]+|[\n\r]+$/g, '');

                if (!trimmed) {
                    return null;
                }

                const block = document.createElement('div');
                block.className = 'whitespace-pre-wrap leading-relaxed';
                block.textContent = trimmed;
                return block;
            };

            const renderFullDescription = (element, rawValue) => {
                if (!element) {
                    return;
                }

                const value = typeof rawValue === 'string' ? rawValue : String(rawValue ?? '');

                if (!value.trim()) {
                    element.innerHTML = '';
                    hideElement(element);
                    return;
                }

                const normalizedValue = value.replace(/\r\n/g, '\n');
                const fragments = [];
                const codeBlockRegex = /```(?:([^`\n]+)\n)?([\s\S]*?)```/g;
                let lastIndex = 0;
                let match;

                while ((match = codeBlockRegex.exec(normalizedValue)) !== null) {
                    const matchStart = match.index;
                    const textSegment = normalizedValue.slice(lastIndex, matchStart);

                    if (textSegment.trim()) {
                        fragments.push({ type: 'text', content: textSegment });
                    }

                    const codeContent = (match[2] ?? '').replace(/^[\n\r]+|[\n\r]+$/g, '');

                    if (codeContent.trim()) {
                        fragments.push({ type: 'code', content: codeContent });
                    }

                    lastIndex = codeBlockRegex.lastIndex;
                }

                const remainingText = normalizedValue.slice(lastIndex);

                if (remainingText.trim()) {
                    fragments.push({ type: 'text', content: remainingText });
                }

                element.innerHTML = '';

                if (!fragments.length) {
                    const fallbackBlock = createTextBlock(normalizedValue);

                    if (fallbackBlock) {
                        element.appendChild(fallbackBlock);
                        showElement(element);
                        return;
                    }

                    hideElement(element);
                    return;
                }

                fragments.forEach((fragment) => {
                    if (fragment.type === 'code') {
                        element.appendChild(createCodeBlock(fragment.content));
                    } else {
                        const textBlock = createTextBlock(fragment.content);
                        if (textBlock) {
                            element.appendChild(textBlock);
                        }
                    }
                });

                if (element.childElementCount === 0) {
                    hideElement(element);
                    return;
                }

                element.scrollTop = 0;
                showElement(element);
            };

            const resetVideoPlayer = () => {
                if (!videoElement) return;
                videoElement.pause();
                try {
                    videoElement.currentTime = 0;
                } catch (error) {
                    // Игнорируем невозможность сброса времени в некоторых браузерах.
                }
                videoElement.removeAttribute('src');
                videoSource?.removeAttribute('src');
                videoElement.load();
                hideElement(playerPreorderCta);
                if (playerPreorderCtaButton) {
                    playerPreorderCtaButton.dataset.preorderUrl = '';
                    playerPreorderCtaButton.setAttribute('disabled', 'disabled');
                }
            };

            const openVideoModal = () => {
                videoModal.classList.remove('hidden');
                videoModal.classList.add('flex');
                body.classList.add('overflow-hidden');
            };

            const closeVideoModal = () => {
                videoModal.classList.add('hidden');
                videoModal.classList.remove('flex');
                body.classList.remove('overflow-hidden');
                resetVideoPlayer();
                resetPreorderForm();
                activeItem = null;
            };

            const populateText = (element, value) => {
                if (!element) return;
                if (value) {
                    element.textContent = value;
                    showElement(element);
                } else {
                    element.textContent = '';
                    hideElement(element);
                }
            };

            const setStatusMessage = (element, message) => {
                if (!element) return;
                if (message) {
                    element.textContent = message;
                    showElement(element);
                } else {
                    element.textContent = '';
                    hideElement(element);
                }
            };

            const normalizeContactType = (value) =>
                value === CONTACT_TYPE_TELEGRAM ? CONTACT_TYPE_TELEGRAM : CONTACT_TYPE_PHONE;

            const getSelectedContactType = () => normalizeContactType(preorderContactTypeSelect?.value || DEFAULT_CONTACT_TYPE);

            const updateContactInputAttributes = (type) => {
                if (!preorderContactInput) {
                    return;
                }

                if (type === CONTACT_TYPE_TELEGRAM) {
                    preorderContactInput.placeholder = '@username';
                    preorderContactInput.setAttribute('inputmode', 'text');
                } else {
                    preorderContactInput.placeholder = '+9(999)999-99-99';
                    preorderContactInput.setAttribute('inputmode', 'tel');
                }
            };

            const formatPhoneContactValue = (value) => {
                if (typeof value !== 'string') {
                    return '';
                }

                const digitsOnly = value.replace(/\D/g, '').slice(0, 11);

                if (digitsOnly.length === 0) {
                    return '';
                }

                let result = `+${digitsOnly[0]}`;

                if (digitsOnly.length > 1) {
                    const area = digitsOnly.slice(1, Math.min(4, digitsOnly.length));
                    result += `(${area}`;

                    if (digitsOnly.length >= 4) {
                        result += ')';
                    }
                }

                if (digitsOnly.length >= 4) {
                    result += digitsOnly.slice(4, Math.min(7, digitsOnly.length));
                }

                if (digitsOnly.length >= 7) {
                    result += `-${digitsOnly.slice(7, Math.min(9, digitsOnly.length))}`;
                }

                if (digitsOnly.length >= 9) {
                    result += `-${digitsOnly.slice(9, Math.min(11, digitsOnly.length))}`;
                }

                return result;
            };

            const formatTelegramContactValue = (value) => {
                if (typeof value !== 'string') {
                    return '';
                }

                const trimmed = value.trim();

                if (!trimmed) {
                    return '';
                }

                if (trimmed === '@') {
                    return '@';
                }

                const withoutAt = trimmed.replace(/^@+/, '');
                const sanitized = withoutAt.replace(/[^a-zA-Z0-9_]/g, '').slice(0, 32);

                if (sanitized) {
                    return `@${sanitized}`;
                }

                return trimmed.startsWith('@') ? '@' : '';
            };

            const formatContactValueByType = (value, type) =>
                type === CONTACT_TYPE_TELEGRAM ? formatTelegramContactValue(value) : formatPhoneContactValue(value);

            const detectContactTypeFromValue = (value) => {
                if (typeof value !== 'string') {
                    return null;
                }

                const trimmed = value.trim();

                if (trimmed.startsWith('@')) {
                    return CONTACT_TYPE_TELEGRAM;
                }

                if (trimmed.replace(/\D/g, '').length > 0) {
                    return CONTACT_TYPE_PHONE;
                }

                return null;
            };

            const syncContactValueWithType = () => {
                if (!preorderContactInput) {
                    return;
                }

                const type = getSelectedContactType();
                const formatted = formatContactValueByType(preorderContactInput.value, type);

                if (preorderContactInput.value !== formatted) {
                    preorderContactInput.value = formatted;

                    try {
                        const position = formatted.length;
                        preorderContactInput.setSelectionRange(position, position);
                    } catch (error) {
                        // Игнорируем невозможность установить позицию курсора в некоторых браузерах.
                    }
                }
            };

            const setContactType = (type) => {
                const normalized = normalizeContactType(type);

                if (preorderContactTypeSelect) {
                    preorderContactTypeSelect.value = normalized;
                }

                updateContactInputAttributes(normalized);

                return normalized;
            };

            const applyContactType = (type) => {
                const normalized = setContactType(type);
                syncContactValueWithType();
                return normalized;
            };

            applyContactType(preorderContactTypeSelect?.value || DEFAULT_CONTACT_TYPE);

            preorderContactTypeSelect?.addEventListener('change', () => {
                const newType = preorderContactTypeSelect.value;
                applyContactType(newType);
            });

            preorderContactInput?.addEventListener('input', () => {
                syncContactValueWithType();
            });

            preorderContactInput?.addEventListener('blur', () => {
                syncContactValueWithType();
            });

            const resetPreorderForm = () => {
                activePreorderUrl = null;
                activeCourseId = null;
                activePreorderId = null;
                if (preorderForm) {
                    preorderForm.reset();
                }
                applyContactType(preorderContactTypeSelect?.value || DEFAULT_CONTACT_TYPE);
                if (preorderNameInput && isAuthenticated) {
                    preorderNameInput.value = authenticatedName;
                }
                setStatusMessage(preorderErrorEl, '');
                setStatusMessage(preorderSuccessEl, '');
                if (preorderSubmitButton) {
                    preorderSubmitButton.removeAttribute('disabled');
                    preorderSubmitButton.classList.remove('opacity-70', 'cursor-wait');
                }
                hideElement(preorderSection);
            };

            const showPreorderThankYou = (message) => {
                if (!message) {
                    return;
                }

                let banner = document.getElementById('video-modal-preorder-thanks');
                let messageElement;

                if (!banner) {
                    banner = document.createElement('div');
                    banner.id = 'video-modal-preorder-thanks';
                    banner.className = 'fixed inset-x-0 top-4 z-50 flex justify-center px-4';

                    messageElement = document.createElement('div');
                    messageElement.className = 'max-w-md w-full rounded-2xl bg-emerald-500 text-white text-sm font-semibold shadow-lg px-4 py-3 text-center';
                    messageElement.setAttribute('role', 'status');

                    banner.appendChild(messageElement);
                    document.body.appendChild(banner);
                } else {
                    messageElement = banner.firstElementChild;
                }

                if (messageElement) {
                    messageElement.textContent = message;
                }

                banner.classList.remove('hidden');
                banner.style.display = 'flex';

                const previousTimeoutId = banner.dataset.hideTimeoutId;

                if (previousTimeoutId) {
                    window.clearTimeout(Number(previousTimeoutId));
                }

                const timeoutId = window.setTimeout(() => {
                    banner.style.display = 'none';
                }, 4000);

                banner.dataset.hideTimeoutId = String(timeoutId);
            };

            const populateVideoContent = (item) => {
                const dataset = item.dataset;

                populateText(courseTitleEl, dataset.courseTitle);
                populateText(videoTitleEl, dataset.videoTitle);
                populateText(shortDescriptionEl, dataset.videoShortDescription);
                renderFullDescription(fullDescriptionEl, dataset.videoFullDescription);

                if (dataset.videoUrl) {
                    videoSource?.setAttribute('src', dataset.videoUrl);
                    videoElement?.load();
                }

                activePreorderUrl = null;

                if (playerPreorderCta) {
                    const shouldShowPreorderCta = dataset.preorderCta === 'true' && dataset.preorderUrl;

                    if (shouldShowPreorderCta) {
                        populateText(playerPreorderCtaStartDateEl, dataset.courseStartDateReadable);

                        if (playerPreorderCtaStartDiffEl) {
                            if (dataset.courseStartDateDiff) {
                                playerPreorderCtaStartDiffEl.textContent = ` · через ${dataset.courseStartDateDiff}`;
                                showElement(playerPreorderCtaStartDiffEl);
                            } else {
                                playerPreorderCtaStartDiffEl.textContent = '';
                                hideElement(playerPreorderCtaStartDiffEl);
                            }
                        }

                        if (playerPreorderCtaDiscountEl) {
                            playerPreorderCtaDiscountEl.textContent = preorderDiscountValue;
                        }

                        if (playerPreorderCtaButton) {
                            playerPreorderCtaButton.dataset.preorderUrl = dataset.preorderUrl;
                            playerPreorderCtaButton.removeAttribute('disabled');
                        }

                        showElement(playerPreorderCta);
                    } else {
                        hideElement(playerPreorderCta);
                        if (playerPreorderCtaButton) {
                            playerPreorderCtaButton.dataset.preorderUrl = '';
                            playerPreorderCtaButton.setAttribute('disabled', 'disabled');
                        }
                    }
                }
            };

            const populatePayContent = (item) => {
                const dataset = item.dataset;
                populateText(courseTitleEl, dataset.courseTitle);
                populateText(videoTitleEl, dataset.videoTitle);
                populateText(shortDescriptionEl, dataset.videoShortDescription);
                hideElement(fullDescriptionEl);
                hideElement(playerPreorderCta);
                resetVideoPlayer();

                populateText(payCourseTitleEl, dataset.courseTitle);
                if (payLinkEl) {
                    payLinkEl.setAttribute('href', dataset.checkoutUrl || '#');
                }
            };

            const populatePreorderContent = (item) => {
                const dataset = item.dataset;

                resetPreorderForm();

                activeCourseId = dataset.courseId || null;
                const storedPreorder = loadStoredPreorder(activeCourseId);
                activePreorderId = storedPreorder?.preorderId || null;

                if (storedPreorder?.submitted) {
                    setStatusMessage(preorderSuccessEl, 'Вы уже отправили заявку на этот курс.');
                } else {
                    setStatusMessage(preorderSuccessEl, '');
                }

                populateText(courseTitleEl, dataset.courseTitle);
                const preorderTitle = dataset.courseTitle || dataset.videoTitle;
                const preorderDescription = dataset.courseDescription || dataset.videoShortDescription || '';
                populateText(videoTitleEl, preorderTitle);
                populateText(shortDescriptionEl, preorderDescription);
                hideElement(fullDescriptionEl);
                hideElement(playerPreorderCta);
                resetVideoPlayer();

                populateText(preorderCourseTitleEl, dataset.courseTitle);
                populateText(preorderStartDateEl, dataset.courseStartDateReadable);

                if (preorderStartDiffEl) {
                    if (dataset.courseStartDateDiff) {
                        preorderStartDiffEl.textContent = ` · через ${dataset.courseStartDateDiff}`;
                        showElement(preorderStartDiffEl);
                    } else {
                        preorderStartDiffEl.textContent = '';
                        hideElement(preorderStartDiffEl);
                    }
                }

                if (preorderDiscountEl) {
                    preorderDiscountEl.textContent = preorderDiscountValue;
                }

                if (preorderNameField) {
                    if (isAuthenticated) {
                        preorderNameField.classList.add('hidden');
                        if (preorderNameInput) {
                            preorderNameInput.value = authenticatedName;
                            preorderNameInput.removeAttribute('required');
                        }
                    } else {
                        preorderNameField.classList.remove('hidden');
                        if (preorderNameInput) {
                            preorderNameInput.value = storedPreorder?.name || '';
                            preorderNameInput.setAttribute('required', 'required');
                        }
                    }
                }

                const storedContactValue = storedPreorder?.contact || '';

                if (preorderContactInput) {
                    preorderContactInput.value = storedContactValue;
                }

                const storedContactType =
                    storedPreorder?.contactType || detectContactTypeFromValue(storedContactValue) || DEFAULT_CONTACT_TYPE;

                applyContactType(storedContactType);

                activePreorderUrl = dataset.preorderUrl || null;

                if (!activePreorderUrl && preorderSubmitButton) {
                    preorderSubmitButton.setAttribute('disabled', 'disabled');
                }
            };

            closeButton?.addEventListener('click', (event) => {
                event.preventDefault();
                closeVideoModal();
            });

            videoModal.addEventListener('click', (event) => {
                if (event.target === videoModal || event.target.classList.contains('video-modal-backdrop')) {
                    closeVideoModal();
                }
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && !videoModal.classList.contains('hidden')) {
                    closeVideoModal();
                }
            });

            let hasOpenedVideoFromQuery = false;

            const getVideoIdFromQuery = () => {
                try {
                    const params = new URLSearchParams(window.location.search);
                    return params.get('video');
                } catch (error) {
                    return null;
                }
            };

            const openVideoFromQuery = () => {
                if (hasOpenedVideoFromQuery) {
                    return;
                }

                if (!videoItems.length) {
                    return;
                }

                const videoIdFromQuery = getVideoIdFromQuery();

                if (!videoIdFromQuery) {
                    return;
                }

                const targetItem = videoItems.find((item) => item.dataset.videoId === videoIdFromQuery);

                if (!targetItem) {
                    return;
                }

                if (targetItem.dataset.videoFree !== 'true') {
                    return;
                }

                if (targetItem.dataset.access !== 'allowed') {
                    return;
                }

                if (!targetItem.dataset.videoUrl) {
                    return;
                }

                hasOpenedVideoFromQuery = true;

                window.requestAnimationFrame(() => {
                    targetItem.click();
                });
            };

            videoItems.forEach((item) => {
                item.addEventListener('click', () => {
                    const access = item.dataset.access;
                    activeItem = item;

                    if (access === 'login') {
                        window.openAuthModal?.('login');
                        return;
                    }

                    if (access === 'pay') {
                        hideElement(playerSection);
                        showElement(paySection);
                        hideElement(preorderSection);
                        populatePayContent(item);
                        openVideoModal();
                        return;
                    }

                    if (access === 'preorder') {
                        hideElement(playerSection);
                        hideElement(paySection);
                        populatePreorderContent(item);
                        showElement(preorderSection);
                        openVideoModal();
                        return;
                    }

                    hideElement(paySection);
                    hideElement(preorderSection);
                    showElement(playerSection);
                    populateVideoContent(item);
                    openVideoModal();
                });
            });

            openVideoFromQuery();

            playerPreorderCtaButton?.addEventListener('click', (event) => {
                event.preventDefault();

                if (!activeItem) {
                    return;
                }

                hideElement(playerSection);
                hideElement(paySection);
                populatePreorderContent(activeItem);
                showElement(preorderSection);
            });

            preorderForm?.addEventListener('submit', async (event) => {
                event.preventDefault();

                if (!activePreorderUrl) {
                    return;
                }

                const rawContactValue = preorderContactInput?.value ?? '';
                const contactTypeValue = getSelectedContactType();
                const contactValue = formatContactValueByType(rawContactValue, contactTypeValue);
                const nameValue = (preorderNameInput?.value || '').trim();

                if (preorderContactInput) {
                    preorderContactInput.value = contactValue;
                }

                if (!contactValue) {
                    setStatusMessage(preorderSuccessEl, '');
                    setStatusMessage(preorderErrorEl, 'Укажите телефон или Telegram.');
                    return;
                }

                if (!isAuthenticated && !nameValue) {
                    setStatusMessage(preorderSuccessEl, '');
                    setStatusMessage(preorderErrorEl, 'Пожалуйста, представьтесь.');
                    return;
                }

                setStatusMessage(preorderErrorEl, '');
                setStatusMessage(preorderSuccessEl, '');

                if (preorderSubmitButton) {
                    preorderSubmitButton.setAttribute('disabled', 'disabled');
                    preorderSubmitButton.classList.add('opacity-70', 'cursor-wait');
                }

                try {
                    const response = await fetch(activePreorderUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: JSON.stringify({
                            contact: contactValue,
                            name: isAuthenticated ? undefined : nameValue,
                            preorder_id: activePreorderId || undefined,
                        }),
                    });

                    const data = await response.json().catch(() => ({}));

                    if (!response.ok) {
                        const errors = data?.errors || {};
                        const firstError = Object.values(errors)[0]?.[0];
                        const message = data?.message || firstError || 'Не удалось отправить заявку. Попробуйте позже.';
                        setStatusMessage(preorderErrorEl, message);
                        setStatusMessage(preorderSuccessEl, '');
                    } else {
                        const message = data?.message || 'Заявка отправлена!';
                        const preorderIdFromServer = data?.preorder_id ? Number(data.preorder_id) : null;
                        if (preorderIdFromServer) {
                            activePreorderId = preorderIdFromServer;
                        }
                        if (activeCourseId) {
                            saveStoredPreorder(activeCourseId, {
                                contact: contactValue,
                                contactType: contactTypeValue,
                                name: isAuthenticated ? authenticatedName : nameValue,
                                preorderId: activePreorderId,
                                submitted: true,
                            });
                        }
                        closeVideoModal();
                        showPreorderThankYou(message);
                    }
                } catch (error) {
                    setStatusMessage(preorderErrorEl, 'Не удалось отправить заявку. Проверьте подключение и попробуйте снова.');
                    setStatusMessage(preorderSuccessEl, '');
                } finally {
                    if (preorderSubmitButton) {
                        preorderSubmitButton.removeAttribute('disabled');
                        preorderSubmitButton.classList.remove('opacity-70', 'cursor-wait');
                    }
                }
            });
        };

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initVideoModal, { once: true });
        } else {
            initVideoModal();
        }
    })();
</script>
