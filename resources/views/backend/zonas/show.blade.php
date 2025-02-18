<x-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalles de la Zona') }}: {{ $zona->nombre }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-zona
                :zona="$zona"
                :show-actions="true"
                :show-operators="true"
                :show-dates="true"
            />
        </div>
    </div>
</x-layout>
