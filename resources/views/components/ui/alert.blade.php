@props(['type' => 'success', 'message' => null, 'title' => null])

@php
    $configs = [
        'success' => [
            'container' => 'border-success-200 bg-success-50 dark:bg-success-900/10 dark:border-success-800',
            'icon_bg' => 'bg-success-100 text-success-600 dark:bg-success-800 dark:text-success-300',
            'title_color' => 'text-success-800 dark:text-success-100',
            'text_color' => 'text-success-700 dark:text-success-200',
            'default_title' => 'Berhasil',
        ],
        'error' => [
            'container' => 'border-error-200 bg-error-50 dark:bg-error-900/10 dark:border-error-800',
            'icon_bg' => 'bg-error-100 text-error-600 dark:bg-error-800 dark:text-error-300',
            'title_color' => 'text-error-800 dark:text-error-100',
            'text_color' => 'text-error-700 dark:text-error-200',
            'default_title' => 'Gagal',
        ],
        'warning' => [
            'container' => 'border-warning-200 bg-warning-50 dark:bg-warning-900/10 dark:border-warning-800',
            'icon_bg' => 'bg-warning-100 text-warning-600 dark:bg-warning-800 dark:text-warning-300',
            'title_color' => 'text-warning-800 dark:text-warning-100',
            'text_color' => 'text-warning-700 dark:text-warning-200',
            'default_title' => 'Peringatan',
        ],
    ];

    $config = $configs[$type] ?? $configs['success'];
@endphp

@if($message || $slot->isNotEmpty())
<div {{ $attributes->merge(['class' => "mb-6 flex w-full border px-5 py-4 shadow-sm rounded-xl {$config['container']}"]) }}>
    <div class="mr-4 flex-shrink-0 flex h-10 w-10 items-center justify-center rounded-full {{ $config['icon_bg'] }}">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="currentColor" stroke-width="2.5">
            @if($type === 'success') <path d="M20 6L9 17l-5-5" stroke-linecap="round" stroke-linejoin="round" /> @endif
            @if($type === 'error') <path d="M18 6L6 18M6 6l12 12" stroke-linecap="round" stroke-linejoin="round"/> @endif
            @if($type === 'warning') <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" stroke-linecap="round" stroke-linejoin="round"/> @endif
        </svg>
    </div>
    <div class="w-full">
        <h5 class="mb-1 text-base font-bold leading-none {{ $config['title_color'] }}">{{ $title ?? $config['default_title'] }}</h5>
        <div class="text-sm leading-relaxed {{ $config['text_color'] }}">
            {{ $message ?? $slot }}
        </div>
    </div>
</div>
@endif