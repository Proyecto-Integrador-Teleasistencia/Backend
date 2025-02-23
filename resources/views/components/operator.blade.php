<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 bg-white border-b border-gray-200">
        @if($showActions)
        <div class="flex justify-end space-x-2 mb-4">
            <x-button href="{{ route('backend.operators.edit', $operator) }}" class="bg-yellow-600 hover:bg-yellow-700">
                {{ __('Editar') }}
            </x-button>
            <form method="POST" action="{{ route('backend.operators.destroy', $operator) }}" class="inline">
                @csrf
                @method('DELETE')
                <x-button type="submit" class="bg-red-600 hover:bg-red-700" onclick="return confirm('{{ __('¿Estás seguro de que deseas eliminar este operador?') }}')">
                    {{ __('Eliminar') }}
                </x-button>
            </form>
        </div>
        @endif

        <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
            <div class="sm:col-span-1">
                <dt class="text-sm font-medium text-gray-500">{{ __('Nombre') }}</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $operator->nombre }}</dd>
            </div>

            <div class="sm:col-span-1">
                <dt class="text-sm font-medium text-gray-500">{{ __('Email') }}</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $operator->email }}</dd>
            </div>

            @if($showFullDetails)
            <div class="sm:col-span-1">
                <dt class="text-sm font-medium text-gray-500">{{ __('Teléfono') }}</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $operator->telefono ?? '-' }}</dd>
            </div>

            <div class="sm:col-span-1">
                <dt class="text-sm font-medium text-gray-500">{{ __('Zona') }}</dt>
                <dd class="mt-1 text-sm text-gray-900">
                    @if($operator->zona)
                        {{ $operator->zona->nombre }}
                    @else
                        -
                    @endif
                </dd>
            </div>
            @endif

            <div class="sm:col-span-1">
                <dt class="text-sm font-medium text-gray-500">{{ __('Estado') }}</dt>
                <dd class="mt-1 text-sm text-gray-900">
                    @if($operator->is_active)
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            {{ __('Activo') }}
                        </span>
                    @else
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                            {{ __('Inactivo') }}
                        </span>
                    @endif
                </dd>
            </div>

            @if($showDates)
            <div class="sm:col-span-2">
                <dt class="text-sm font-medium text-gray-500">{{ __('Fecha de creación') }}</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $operator->created_at->format('d/m/Y H:i') }}</dd>
            </div>

            <div class="sm:col-span-2">
                <dt class="text-sm font-medium text-gray-500">{{ __('Última actualización') }}</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $operator->updated_at->format('d/m/Y H:i') }}</dd>
            </div>
            @endif
        </dl>
    </div>
</div>
