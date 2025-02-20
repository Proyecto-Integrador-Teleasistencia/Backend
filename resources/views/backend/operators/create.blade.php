<x-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Crear Operador') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('backend.operators.store') }}" class="space-y-6">
                        @csrf

                        <div>
                            <x-form.input name="name" label="Nombre" required />
                        </div>

                        <div>
                            <x-form.input name="email" type="email" label="Email" required />
                        </div>

                        <div>
                            <x-form.input name="phone" label="Teléfono" />
                        </div>

                        <div>
                            <x-form.input name="password" type="password" label="Contraseña" required />
                        </div>

                        <div>
                            <x-form.input name="password_confirmation" type="password" label="Confirmar Contraseña" required />
                        </div>

                        <div>
                            <label for="zone_id" class="block text-sm font-medium text-gray-700">Zona</label>
                            <select id="zone_id" name="zone_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">{{ __('Seleccionar zona') }}</option>
                                @foreach($zones as $zone)
                                    <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                                @endforeach
                            </select>
                            @error('zone_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-button href="{{ route('backend.operators.index') }}" class="bg-gray-600 hover:bg-gray-700 mr-2">
                                {{ __('Cancelar') }}
                            </x-button>
                            <x-button type="submit" class="bg-blue-600 hover:bg-blue-700">
                                {{ __('Crear') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layout>
