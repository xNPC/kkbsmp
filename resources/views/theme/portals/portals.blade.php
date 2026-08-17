<div>
    <!-- Стили для красивого скрытия дефолтных стрелок браузера и плавного вращения нашей -->
    <style>
        details summary::-webkit-details-marker { display: none; }
        details summary { list-style: none; }

        /* Плавный поворот стрелки */
        details[open] .native-arrow {
            transform: rotate(180deg);
        }

        /* Контейнер для анимации */
        .details-wrapper {
            display: grid;
            grid-template-rows: 0fr;
            transition: grid-template-rows 300ms ease-in-out, opacity 250ms ease-in-out;
            opacity: 0;
        }

        /* Когда details открыт, плавно увеличиваем высоту до авто и проявляем контент */
        details[open] + .details-wrapper {
            grid-template-rows: 1fr;
            opacity: 1;
        }

        .details-content {
            overflow: hidden;
        }
    </style>

    <div class="flex flex-col gap-4 mb-6">
        <flux:heading level="1" size="xl">Порталы</flux:heading>

        <!-- Сделаем поиск чуть шире для удобства на мобильных (w-full), но ограничим на десктопе (md:max-w-xs) -->
        <flux:input
            wire:model.live.debounce.300ms="search"
            placeholder="Поиск по ссылкам..."
            clearable=true
            icon="magnifying-glass"
            class="w-full"
        />
    </div>

    <div class="grid grid-cols-1 gap-6 items-start">
        @foreach($categories as $category)
            <div
                wire:key="cat-card-{{ $category->id }}"
                class="p-6 rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm"
            >
                <details {{ $search ? 'open' : '' }} class="w-full">
                    <summary
                        onclick="toggleDetails(event)"
                        class="list-none flex items-center justify-between cursor-pointer select-none focus:outline-none group"
                    >
                        <div class="flex items-center gap-2">
                            <flux:icon.folder variant="solid" class="size-5 text-amber-500 dark:text-amber-300" />
                                <flux:heading level="2" size="lg">
{{--                            <h2 class="text-lg font-semibold text-zinc-800 dark:text-zinc-100 group-hover:text-zinc-600 dark:group-hover:text-zinc-300">--}}
                                {{ $category->title }}
                                <!-- Компактный счетчик количества ссылок -->
                                <span class="ml-1.5 text-sm font-normal text-zinc-400">({{ $category->links->count() }})</span>
                                </flux:heading>
{{--                            </h2>--}}
                        </div>

                        <svg
                            xmlns="http://w3.org"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="native-arrow size-4 text-zinc-400 transition-transform duration-300 ease-in-out"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                        </svg>
                    </summary>
                </details>

                <div class="details-wrapper {{ $search ? '!grid-template-rows-[1fr] !opacity-100' : '' }}">
                    <div class="details-content">
                        <div class="mt-4">
                            <!-- Используем flex-wrap и gap-2 для компактного выстраивания плиток -->
                            <div class="flex flex-wrap gap-2">
                                @forelse($category->links as $link)
                                    <a
                                        wire:key="link-badge-{{ $link->id }}"
                                        href="{{ $link->url }}"
                                        target="_blank"
                                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-medium bg-zinc-50 dark:bg-zinc-800/50 text-zinc-700 dark:text-zinc-300 border border-zinc-200/60 dark:border-zinc-700/40 hover:bg-zinc-100 dark:hover:bg-zinc-800 hover:text-primary-600 dark:hover:text-primary-400 hover:border-primary-300 dark:hover:border-primary-800 transition-all shadow-sm"
                                    >
                                        {{ $link->title }}
                                        <!-- Увеличили иконку стрелочки до размера size-3.5, чтобы она соответствовала новому шрифту -->
                                        <flux:icon.arrow-top-right-on-square class="ms-1 size-3.5 text-green-800 dark:text-green-500 group-hover:text-primary-500" />
                                    </a>

                                @empty
                                    <span class="text-sm text-zinc-400 italic py-1">Нет доступных ссылок</span>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        @endforeach
    </div>

    <!-- Легковесный нативный скрипт для мягкого переключения без участия тяжелых библиотек -->
    <script>
        function toggleDetails(event) {
            event.preventDefault(); // Останавливаем мгновенное переключение браузера

            const summary = event.currentTarget;
            const details = summary.closest('details');
            const wrapper = details.nextElementSibling;

            if (details.hasAttribute('open')) {
                // Если закрываем: сначала запускаем анимацию скрытия CSS
                wrapper.style.gridTemplateRows = '0fr';
                wrapper.style.opacity = '0';

                // Ждем окончания анимации (300мс) и только потом убираем атрибут open
                setTimeout(() => {
                    details.removeAttribute('open');
                }, 300);
            } else {
                // Если открываем: сначала включаем open, чтобы контент появился в DOM
                details.setAttribute('open', '');
                // Даем браузеру микросекунду подготовиться и плавно раскрываем
                requestAnimationFrame(() => {
                    wrapper.style.gridTemplateRows = '1fr';
                    wrapper.style.opacity = '1';
                });
            }
        }

        // Поддержка плавности при перерендере Livewire во время поиска
        document.addEventListener('livewire:init', () => {
            Livewire.on('post-render', () => {
                document.querySelectorAll('details').forEach(details => {
                    const wrapper = details.nextElementSibling;
                    if (details.hasAttribute('open')) {
                        wrapper.style.gridTemplateRows = '1fr';
                        wrapper.style.opacity = '1';
                    }
                });
            });
        });
    </script>
</div>
