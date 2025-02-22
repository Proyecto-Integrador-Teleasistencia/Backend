<x-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Operador') }}: {{ $operator->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('backend.operators.update', $operator) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-form.input name="nombre" label="Nombre" :value="old('nombre', $operator->nombre)" required />
                        </div>

                        <div>
                            <x-form.input name="email" type="email" label="Email" :value="old('email', $operator->email)" required />
                        </div>

                        <div>
                            <x-form.input name="telefono" label="Teléfono" :value="old('telefono', $operator->telefono)" />
                        </div>

                        <div>
                            <x-form.input name="password" type="password" label="Contraseña" />
                            <p class="mt-2 text-sm text-gray-500">{{ __('Dejar en blanco para mantener la contraseña actual') }}</p>
                        </div>

                        <div>
                            <x-form.input name="password_confirmation" type="password" label="Confirmar Contraseña" />
                        </div>

                        <div>
                            <label for="zone_id" class="block text-sm font-medium text-gray-700">Zona</label>
                            <select id="zona_id" name="zona_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">{{ __('Seleccionar zona') }}</option>
                                @foreach($zonas as $zona)
                                <option value="{{ $zona->id }}" {{ old('zona_id', $operator->zona_id) == $zona->id ? 'selected' : '' }}>
                                    {{ $zona->nombre }}
                                </option>
                                @endforeach
                            </select>
                            @error('zone_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="is_active" class="inline-flex items-center">
                                <input type="checkbox" id="is_active" name="is_active" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" {{ old('is_active', $operator->is_active) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-600">{{ __('Activo') }}</span>
                            </label>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-button href="{{ route('backend.operators.index') }}" class="bg-gray-600 hover:bg-gray-700 mr-2">
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
