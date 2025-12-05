@props(['type' => 'success'])


<div 
    x-data="{ show: true }" 
    x-show="show"
    x-transition:enter="transform ease-out duration-300 transition"
    x-transition:enter-start="translate-y-5 opacity-0"
    x-transition:enter-end="translate-y-0 opacity-100"
    x-transition:leave="transform ease-in duration-300 transition"
    x-transition:leave-start="translate-y-0 opacity-100"
    x-transition:leave-end="translate-y-5 opacity-0"
    x-init="setTimeout(() => show = false, 3500)"
    class="fixed top-5 right-5 z-50 flex items-center gap-3 px-4 py-3 
           rounded-xl shadow-lg border backdrop-blur 
           bg-white/90 dark:bg-gray-800/90
           {{ $type === 'success' ? 'border-green-400 text-green-700' : '' }}
           {{ $type === 'danger' ? 'border-red-400 text-red-700' : '' }}
           {{ $type === 'warning' ? 'border-yellow-400 text-yellow-700' : '' }}"
>
    @if ($type === 'null')
        <i class="fa-solid fa-circle-check text-green-600 text-xl"></i>
    @endif

    @if ($type === 'danger')
        <i class="fa-solid fa-circle-xmark text-red-600 text-xl"></i>
    @endif

    @if ($type === 'warning')
        <i class="fa-solid fa-triangle-exclamation text-yellow-500 text-xl"></i>
    @endif

    <span class="font-medium">{{ $slot }}</span>
</div>

