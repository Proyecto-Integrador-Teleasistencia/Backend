<x-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('backend.zonas.update', $zona) }}" class="space-y-6">
                        @csrf
                        @method('PUT')
        
                        <div>
                            <x-form.input name="nombre" label="Nombre" :value="old('nombre', $zona->nombre)" required />
                        </div>
        
                        <div>
                            <x-form.input name="codigo" label="Código" :value="old('codigo', $zona->codigo)" required />
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" name="activa" id="activa" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" {{ $zona->activa ? 'checked' : '' }}>
                            <label for="activa" class="ml-2 block text-sm text-gray-900">{{ __('Zona activa') }}</label>
                        </div>
        
                        <div class="flex items-center justify-end mt-4">
                            <x-button href="{{ route('backend.zonas.index') }}" class="bg-gray-600 hover:bg-gray-700 mr-2">
                                {{ __('Cancelar') }}
                            </x-button>
                            <x-button type="submit" class="bg-blue-600 hover:bg-blue-700">
                                {{ __('Actualizar') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layout>
