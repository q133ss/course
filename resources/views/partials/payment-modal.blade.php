<div
    x-data="{
        course: null,
        init() {
            this.course = this.$root.course;
            this.$watch(() => this.$root.course, value => this.course = value);
        }
    }"
    class="space-y-6"
>
    <div class="flex items-start justify-between gap-4">
        <div class="space-y-2">
            <h2 id="payment-modal-title" class="text-2xl font-semibold text-slate-900" x-text="course?.title"></h2>
            <p class="text-sm text-slate-600">Откройте доступ ко всем урокам и материалам курса.</p>
        </div>
        <x-button variant="ghost" size="sm" class="shrink-0" @click="$root.closeModal()">
            <span class="sr-only">Закрыть</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </x-button>
    </div>

    <div class="grid gap-6 md:grid-cols-2">
        <div class="overflow-hidden rounded-2xl bg-slate-100 shadow-inner">
            <template x-if="course?.thumbnail">
                <img :src="course.thumbnail" alt="" class="h-full w-full object-cover" />
            </template>
            <div x-show="!course?.thumbnail" class="flex h-full min-h-[220px] items-center justify-center text-sm text-slate-500">
                Превью курса появится здесь
            </div>
        </div>
        <div class="space-y-5">
            <p class="text-lg font-semibold text-slate-900" x-text="course?.price ? `${Number(course.price).toLocaleString('ru-RU')} ₽` : 'Стоимость уточняется'"></p>
            <ul class="space-y-3 text-sm text-slate-600">
                <li class="flex items-start gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mt-0.5 h-4 w-4 text-emerald-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-7.5 9.5a.75.75 0 01-1.14.05l-3.5-3.75a.75.75 0 011.086-1.034l2.867 3.074 6.95-8.804a.75.75 0 011.094-.088z" clip-rule="evenodd" />
                    </svg>
                    Доступ ко всем урокам навсегда
                </li>
                <li class="flex items-start gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mt-0.5 h-4 w-4 text-emerald-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-7.5 9.5a.75.75 0 01-1.14.05l-3.5-3.75a.75.75 0 011.086-1.034l2.867 3.074 6.95-8.804a.75.75 0 011.094-.088z" clip-rule="evenodd" />
                    </svg>
                    Материалы и обновления курса
                </li>
                <li class="flex items-start gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mt-0.5 h-4 w-4 text-emerald-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-7.5 9.5a.75.75 0 01-1.14.05l-3.5-3.75a.75.75 0 011.086-1.034l2.867 3.074 6.95-8.804a.75.75 0 011.094-.088z" clip-rule="evenodd" />
                    </svg>
                    Поддержка от авторов курса
                </li>
            </ul>
            <div class="flex flex-col gap-3 sm:flex-row">
                <x-button
                    variant="primary"
                    size="lg"
                    href="{{ route('checkout.show', 0) }}"
                    x-bind:href="course?.checkout_url || '{{ route('checkout.show', 0) }}'"
                    data-autofocus
                >
                    Перейти к оплате
                </x-button>
                <x-button variant="ghost" size="lg" @click="$root.closeModal()">
                    Закрыть
                </x-button>
            </div>
        </div>
    </div>
</div>
