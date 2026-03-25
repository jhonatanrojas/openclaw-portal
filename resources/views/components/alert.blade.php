@props(['type' => 'info', 'message' => ''])
@php
    $colors = [
        'info' => 'bg-blue-100 border-blue-500 text-blue-700 dark:bg-blue-900/20 dark:border-blue-700 dark:text-blue-300',
        'success' => 'bg-green-100 border-green-500 text-green-700 dark:bg-green-900/20 dark:border-green-700 dark:text-green-300',
        'warning' => 'bg-yellow-100 border-yellow-500 text-yellow-700 dark:bg-yellow-900/20 dark:border-yellow-700 dark:text-yellow-300',
        'error' => 'bg-red-100 border-red-500 text-red-700 dark:bg-red-900/20 dark:border-red-700 dark:text-red-300',
    ];
    $icons = [
        'info' => 'ℹ️',
        'success' => '✅',
        'warning' => '⚠️',
        'error' => '❌',
    ];
@endphp
<div class="border-l-4 p-4 {{ $colors[$type] }}" role="alert">
    <div class="flex items-center">
        <span class="mr-3">{{ $icons[$type] }}</span>
        <div>
            <p class="font-bold">{{ ucfirst($type) }}</p>
            <p>{{ $message }}</p>
        </div>
    </div>
</div>
