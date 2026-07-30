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
<header class="sticky top-0 z-40 w-full border-b border-zinc-200 bg-white/80 backdrop-blur dark:border-zinc-800 dark:bg-zinc-950/80">
    <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
        <!-- Логотип -->
        <flux:brand href="/" logo="https://fluxui.dev" name="Мой Проект" class="max-lg:hidden" />

        <!-- Основная навигация -->
        <flux:navbar class="max-lg:hidden">
            <flux:navbar.item href="/" current wire:navigate>Главная</flux:navbar.item>
            <flux:navbar.item href="/about" wire:navigate>О нас</flux:navbar.item>
            <flux:navbar.item href="/contacts" wire:navigate>Контакты</flux:navbar.item>
        </flux:navbar>

        <!-- Правая часть (Переключатель темы / Профиль) -->
        <div class="flex items-center gap-4">
            {{--<flux:darkmodeToggle />--}}
            <flux:dropdown position="top" align="end">
                <flux:avatar src="https://fluxui.dev" initials="ИО" class="cursor-pointer" />
                <flux:menu>
                    <flux:menu.item icon="user">Профиль</flux:menu.item>
                    <flux:menu.item icon="arrow-right-start-on-rectangle" variant="danger">Выйти</flux:menu.item>
                </flux:menu>
            </flux:dropdown>
        </div>
    </div>
</header>

<!-- Основной контейнер для контента страниц -->
<flux:main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
    <!-- Сюда Livewire будет автоматически вставлять верстку ваших страниц -->
    {{ $slot }}
</flux:main>

<!-- Футер сайта -->
<footer class="mt-auto border-t border-zinc-200 bg-white dark:border-zinc-800 dark:bg-zinc-950">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8 text-center text-sm text-zinc-500 dark:text-zinc-400">
        &copy; {{ date('Y') }} Мой Проект. Все права защищены.
    </div>
</footer>

<!-- Обязательные JavaScript скрипты Flux -->
@fluxScripts
</body>
</html>
