<x-layout>
@section('title', __('Zonas'))


    <div class="flex justify-between items-center mb-4">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Zonas') }}
        </h2>
        <x-button href="{{ route('backend.zonas.create') }}" class="bg-blue-500 hover:bg-blue-700">
            {{ __('Crear Zona') }}
        </x-button>
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <x-table>
                <x-slot name="header">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Nombre') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Código') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Operadores') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Acciones') }}
                        </th>
                    </tr>
                </x-slot>

                <x-slot name="body">
                    @forelse ($zonas as $zona)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $zona->nombre }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $zona->codigo }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $zona->getNumeroOperadoresAttribute() ?? 0 }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                <x-button href="{{ route('backend.zonas.show', $zona) }}" class="bg-blue-600 hover:bg-blue-700">
                                    {{ __('Ver') }}
                                </x-button>
                                <x-button href="{{ route('backend.zonas.edit', $zona) }}" class="bg-yellow-600 hover:bg-yellow-700">
                                    {{ __('Editar') }}
                                </x-button>
                                <form method="POST" action="{{ route('backend.zonas.destroy', $zona) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <x-button type="submit" class="bg-red-600 hover:bg-red-700" onclick="return confirm('{{ __('¿Estás seguro de que deseas eliminar esta zona?') }}')">
                                        {{ __('Eliminar') }}
                                    </x-button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                {{ __('No hay zonas registradas') }}
                            </td>
                        </tr>
                    @endforelse
                </x-slot>
            </x-table>

            @if($zonas->hasPages())
                <div class="mt-4">
                    {{ $zonas->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
