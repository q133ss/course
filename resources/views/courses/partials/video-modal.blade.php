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
            <p id="video-modal-short-description" class="text-sm text-gray-600 hidden"></p>
            <div id="video-modal-player-section" class="video-modal-section hidden space-y-4">
                <img id="video-modal-preview-image" src="" alt="" class="hidden rounded-xl">
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
                <div id="video-modal-full-description" class="text-sm text-gray-700 whitespace-pre-line hidden"></div>
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
                    <div class="space-y-1">
                        <label for="video-modal-preorder-contact" class="text-sm font-semibold text-gray-700">Телефон или Telegram</label>
                        <input
                            id="video-modal-preorder-contact"
                            name="contact"
                            type="text"
                            required
                            class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                            placeholder="Например, +7 999 123-45-67 или @username"
                        >
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
            const videoItems = document.querySelectorAll('.video-item');
            const videoModal = document.getElementById('video-modal');
            const closeButton = document.getElementById('video-modal-close');
            const courseTitleEl = document.getElementById('video-modal-course');
            const videoTitleEl = document.getElementById('video-modal-title');
            const shortDescriptionEl = document.getElementById('video-modal-short-description');
            const fullDescriptionEl = document.getElementById('video-modal-full-description');
            const previewImageEl = document.getElementById('video-modal-preview-image');
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

            const resetPreorderForm = () => {
                activePreorderUrl = null;
                activeCourseId = null;
                activePreorderId = null;
                if (preorderForm) {
                    preorderForm.reset();
                }
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
                populateText(fullDescriptionEl, dataset.videoFullDescription);

                if (dataset.videoPreviewImage) {
                    previewImageEl.src = dataset.videoPreviewImage;
                    showElement(previewImageEl);
                } else {
                    previewImageEl.src = '';
                    hideElement(previewImageEl);
                }

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
                hideElement(previewImageEl);
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
                populateText(videoTitleEl, dataset.videoTitle);
                populateText(shortDescriptionEl, dataset.videoShortDescription);
                hideElement(fullDescriptionEl);
                hideElement(previewImageEl);
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

                if (preorderContactInput) {
                    preorderContactInput.value = storedPreorder?.contact || '';
                }

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

                const contactValue = (preorderContactInput?.value || '').trim();
                const nameValue = (preorderNameInput?.value || '').trim();

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
