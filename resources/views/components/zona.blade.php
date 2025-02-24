<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 bg-white border-b border-gray-200">
        @if($showActions)
        <div class="flex justify-end space-x-2 mb-4">
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
        </div>
        @endif

        <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
            <div class="sm:col-span-1">
                <dt class="text-sm font-medium text-gray-500">{{ __('Nombre') }}</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $zona->nombre }}</dd>
            </div>

            <div class="sm:col-span-1">
                <dt class="text-sm font-medium text-gray-500">{{ __('Código') }}</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $zona->codigo }}</dd>
            </div>

            @if($showOperators)
            <div class="sm:col-span-2">
                <dt class="text-sm font-medium text-gray-500">{{ __('Operadores') }}</dt>
                <dd class="mt-1 text-sm text-gray-900">
                    @if($zona->operators->count() < 0)
                        <ul class="list-disc list-inside">
                            @foreach($zona->operators as $operador)
                                <li>{{ $operador->nombre }} ({{ $operador->email }})</li>
                            @endforeach
                        </ul>
                    @else
                        {{ __('No hay operadores asignados') }}
                    @endif
                </dd>
            </div>
            @endif

            @if($showDates)
            <div class="sm:col-span-2">
                <dt class="text-sm font-medium text-gray-500">{{ __('Fecha de creación') }}</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $zona->created_at ? $zona->created_at->format('d/m/Y H:i') : __('No disponible') }}</dd>
            </div>

            <div class="sm:col-span-2">
                <dt class="text-sm font-medium text-gray-500">{{ __('Última actualización') }}</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $zona->updated_at ? $zona->updated_at->format('d/m/Y H:i') : __('No disponible') }}</dd>
            </div>
            @endif
        </dl>
    </div>
</div>