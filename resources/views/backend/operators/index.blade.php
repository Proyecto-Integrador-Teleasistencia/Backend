<x-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Operadores') }}
            </h2>
            <x-button href="{{ route('backend.operators.create') }}" class="bg-blue-500 hover:bg-blue-700">
                {{ __('Crear Operador') }}
            </x-button>
        </div>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <x-table>
                <x-slot name="header">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Nombre') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Email') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Teléfono') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Estado') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Zona') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Acciones') }}
                        </th>
                    </tr>
                </x-slot>

                <x-slot name="body">
                    @forelse ($operators as $operator)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $operator->nombre }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $operator->email }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $operator->telefono ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $operator->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $operator->is_active ? __('Activo') : __('Inactivo') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $operator->zona->nombre ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <a href="{{ route('backend.operators.show', $operator) }}" class="text-blue-600 hover:text-blue-900">
                                    {{ __('Ver') }}
                                </a>
                                <a href="{{ route('backend.operators.edit', $operator) }}" class="text-indigo-600 hover:text-indigo-900">
                                    {{ __('Editar') }}
                                </a>
                                <form action="{{ route('backend.operators.destroy', $operator) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('{{ __('¿Estás seguro de que quieres eliminar este operador?') }}')">
                                        {{ __('Eliminar') }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                {{ __('No hay operadores registrados.') }}
                            </td>
                        </tr>
                    @endforelse
                </x-slot>
            </x-table>

            <div class="mt-4">
                {{ $operators->links() }}
            </div>
        </div>
    </div>
</x-layout>
