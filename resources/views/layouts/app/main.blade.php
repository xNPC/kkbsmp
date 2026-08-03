<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Динамический заголовок страницы -->
    <title>{{ $title ?? config('app.name', 'Laravel') }}</title>

    <!-- Подключение стилей (Tailwind v4 + Flux) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Обязательная директива Flux для темной темы и базовых стилей -->
    @fluxAppearance
</head>
<body class="min-h-full bg-zinc-50 font-sans text-zinc-900 dark:bg-zinc-900 dark:text-zinc-100 transition-colors duration-200">

<!-- Верхняя навигационная панель (Шапка) -->
    <flux:header container class="border-b border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900 flex items-center">
        <!-- Логотип -->
        <flux:brand href="/" logo="logo.png" name="{{config('app.name', 'Laravel')}}" class="max-lg:hidden" />

        <!-- Основная навигация -->
        <flux:navbar class="max-lg:hidden">
            <flux:navbar.item href="{{route('home')}}" wire:navigate>Главная</flux:navbar.item>
            <flux:navbar.item href="{{route('portals')}}" wire:navigate>Порталы</flux:navbar.item>
            <flux:navbar.item href="{{route('contacts')}}" wire:navigate>Контакты</flux:navbar.item>
        </flux:navbar>

        <flux:spacer />

        <!-- Правая часть (Переключатель темы / Профиль) -->
        <flux:radio.group x-data variant="segmented" x-model="$flux.appearance">
            <flux:radio value="light" icon="sun" x-show="$flux.appearance === 'dark'" />
            <flux:radio value="dark" icon="moon" x-show="$flux.appearance === 'light'" />
        </flux:radio.group>
        <flux:dropdown position="top" align="end">
            <flux:profile src="https://ui-avatars.com/api/?name=KKBSMP" class="cursor-pointer" />
            <flux:menu>
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                            <flux:avatar src="https://ui-avatars.com/api/?name=KKBSMP" size="sm" class="shrink-0" />
                            <div class="grid flex-1 text-left text-sm leading-tight">
                                <span class="truncate font-semibold">Токмаков Андрей Сергеевич</span>
                                <span class="truncate text-xs">my@mail.ru</span>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.radio.group>
                    <flux:menu.item icon="user">Профиль</flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.item icon="arrow-right-start-on-rectangle" variant="danger">Выйти</flux:menu.item>
            </flux:menu>
        </flux:dropdown>
    </flux:header>

<!-- Основной контейнер для контента страниц -->
<flux:main>
    <flux:container>
        <!-- Сюда Livewire будет автоматически вставлять верстку ваших страниц -->
        {{ $slot }}
    </flux:container>
</flux:main>

<!-- Футер сайта -->
<flux:footer container>
    <div class="text-sm text-zinc-500">
        &copy; {{ date('Y') }} Мой Проект. Все права защищены.
    </div>
</flux:footer>

<!-- Обязательные JavaScript скрипты Flux -->
@fluxScripts
</body>
</html>
