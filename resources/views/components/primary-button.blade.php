<button
    {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium rounded-lg shadow-sm transition-all text-white bg-green-600 border border-transparent hover:bg-green-700']) }}>
    {{ $slot }}
</button>
