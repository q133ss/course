<div
    x-data="{
        course: null,
        activeVideoIndex: 0,
        toggleFullDescription: false,
        init() {
            this.syncCourse();
            this.$watch(() => this.$root.course, () => this.syncCourse());
        },
        syncCourse() {
            this.course = this.$root.course;
            this.toggleFullDescription = false;
            this.activeVideoIndex = 0;
            this.$nextTick(() => {
                if (this.videos.length) {
                    this.setVideo(0);
                } else if (this.$refs.player) {
                    this.$refs.player.removeAttribute('src');
                    this.$refs.player.load();
                }
            });
        },
        get videos() {
            return Array.isArray(this.course?.videos) ? this.course.videos : [];
        },
        get currentVideo() {
            return this.videos[this.activeVideoIndex] || {};
        },
        setVideo(index) {
            if (!this.videos[index]) {
                return;
            }
            this.activeVideoIndex = index;
            const video = this.videos[index];
            if (this.$refs.player) {
                if (video.video_url) {
                    this.$refs.player.src = video.video_url;
                    this.$refs.player.load();
                } else {
                    this.$refs.player.removeAttribute('src');
                    this.$refs.player.load();
                }
            }
            this.$nextTick(() => {
                const activeItem = this.$refs[`playlist-${index}`];
                activeItem?.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
            });
        },
        formattedDuration(value) {
            if (!value) return '';
            const parts = value.split(':');
            return parts.length === 3 ? `${parts[0]}:${parts[1]}:${parts[2]}` : value;
        }
    }"
    class="space-y-6"
>
    <div class="flex items-start justify-between gap-4">
        <div class="space-y-2">
            <h2 id="player-modal-title" class="text-2xl font-semibold text-slate-900" x-text="course?.title"></h2>
            <p class="text-sm text-slate-600" x-text="course?.description"></p>
            <div class="flex items-center gap-2">
                <template x-if="course?.is_free">
                    <x-badge type="free">Бесплатно</x-badge>
                </template>
                <template x-if="!course?.is_free">
                    <x-badge type="paid" x-text="course?.price ? `${Number(course.price).toLocaleString('ru-RU')} ₽` : 'Платный'">
                        Платный
                    </x-badge>
                </template>
            </div>
        </div>
        <x-button variant="ghost" size="sm" class="shrink-0" @click="$root.closeModal()" data-autofocus>
            <span class="sr-only">Закрыть</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </x-button>
    </div>

    <div class="grid gap-6 lg:grid-cols-[minmax(0,_2fr)_minmax(0,_1fr)]">
        <div class="space-y-4">
            <div class="relative overflow-hidden rounded-xl bg-black shadow-inner">
                <video x-ref="player" controls playsinline class="w-full aspect-video rounded-xl bg-black"></video>
            </div>
            <div class="space-y-3">
                <div>
                    <h3 class="text-lg font-semibold text-slate-900" x-text="currentVideo?.title || 'Выберите урок'"></h3>
                    <p class="text-sm text-slate-600" x-text="currentVideo?.short_description"></p>
                </div>
                <div class="border border-slate-200 rounded-xl">
                    <button
                        type="button"
                        class="flex w-full items-center justify-between px-4 py-3 text-left text-sm font-medium text-slate-700"
                        @click="toggleFullDescription = !toggleFullDescription"
                        :aria-expanded="toggleFullDescription"
                    >
                        <span>Полное описание</span>
                        <svg :class="{ 'rotate-180': toggleFullDescription }" class="h-4 w-4 transition-transform" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.17l3.71-3.94a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div x-show="toggleFullDescription" x-collapse class="px-4 pb-4 text-sm text-slate-600">
                        <p x-text="currentVideo?.full_description || 'Описание появится после выбора урока.'"></p>
                    </div>
                </div>
            </div>
        </div>
        <aside class="space-y-4">
            <h3 class="text-sm font-semibold uppercase tracking-wide text-slate-500">Уроки курса</h3>
            <div class="max-h-[60vh] overflow-y-auto pr-1">
                <template x-for="(video, index) in videos" :key="video.id">
                    <button
                        type="button"
                        :disabled="!video.video_url"
                        @click="setVideo(index)"
                        :ref="`playlist-${index}`"
                        class="group mb-3 w-full overflow-hidden rounded-xl border border-slate-200 text-left transition focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-500"
                        :class="{
                            'bg-sky-50 border-sky-200 shadow-sm': index === activeVideoIndex,
                            'opacity-70 cursor-not-allowed' : !video.video_url,
                            'hover:border-sky-200 hover:bg-slate-50': video.video_url && index !== activeVideoIndex
                        }"
                    >
                        <div class="flex gap-4">
                            <div class="relative h-20 w-28 overflow-hidden bg-slate-200">
                                <img
                                    x-show="video.preview_image"
                                    :src="video.preview_image"
                                    alt=""
                                    class="h-full w-full object-cover"
                                >
                                <div x-show="!video.preview_image" class="flex h-full w-full items-center justify-center text-xs text-slate-500">
                                    Нет превью
                                </div>
                                <span class="absolute bottom-2 right-2 rounded bg-black/70 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-white" x-text="formattedDuration(video.duration)"></span>
                            </div>
                            <div class="flex-1 space-y-1 py-3 pr-3">
                                <p class="text-sm font-semibold text-slate-900" x-text="video.title"></p>
                                <p class="text-xs text-slate-500 line-clamp-2" x-text="video.short_description"></p>
                                <template x-if="!video.video_url">
                                    <p class="text-xs font-medium text-amber-600">Видеоурок временно недоступен</p>
                                </template>
                            </div>
                        </div>
                    </button>
                </template>
                <template x-if="!videos.length">
                    <p class="text-sm text-slate-500">Для этого курса пока нет уроков.</p>
                </template>
            </div>
        </aside>
    </div>
</div>
