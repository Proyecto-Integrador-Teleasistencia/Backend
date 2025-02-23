<x-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Crear Zona') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('backend.zonas.store') }}" class="space-y-6">
                @csrf

                <div>
                    <x-form.input name="nombre" label="Nombre" required />
                </div>

                <div>
                    <x-form.input name="codigo" label="Código" required />
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="activa" id="activa" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                    <label for="activa" class="ml-2 block text-sm text-gray-900">{{ __('Zona activa') }}</label>
                </div>

                <div class="flex items-center justify-end mt-4">
                    <x-button href="{{ route('backend.zonas.index') }}" class="bg-gray-600 hover:bg-gray-700 mr-2">
                        {{ __('Cancelar') }}
                    </x-button>
                    <x-button type="submit" class="bg-blue-600 hover:bg-blue-700">
                        {{ __('Crear') }}
                    </x-button>
                </div>
            </form>
        </div>
    </div>
</x-layout>
