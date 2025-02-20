<div class="p-6">
    <!-- Modal de Detalles -->
    @if($showCallDetails && $selectedCall)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full" id="modal">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Detalles de la Llamada</h3>
                <div class="space-y-3">
                    <p><strong>Fecha:</strong> {{ $selectedCall->fecha_hora->format('d/m/Y H:i') }}</p>
                    <p><strong>Tipo:</strong> {{ ucfirst($selectedCall->tipo_llamada) }}</p>
                    <p><strong>Operador:</strong> {{ $selectedCall->operador->nombre }}</p>
                    <p><strong>Paciente:</strong> {{ $selectedCall->paciente->nombre }}</p>
                    <p><strong>Categoría:</strong> {{ $selectedCall->categoria->nombre }}</p>
                    @if($selectedCall->subcategoria)
                        <p><strong>Subcategoría:</strong> {{ $selectedCall->subcategoria->nombre }}</p>
                    @endif
                    <p><strong>Estado:</strong> {{ ucfirst($selectedCall->estado) }}</p>
                    <p><strong>Duración:</strong> {{ $selectedCall->duracion }} segundos</p>
                    @if($selectedCall->descripcion)
                        <p><strong>Descripción:</strong> {{ $selectedCall->descripcion }}</p>
                    @endif
                    @if($selectedCall->alert)
                        <p><strong>Aviso Relacionado:</strong> {{ $selectedCall->alert->descripcion }}</p>
                    @endif
                </div>
                <div class="mt-4">
                    <button wire:click="closeCallDetails"
                            class="bg-indigo-500 text-white px-4 py-2 rounded hover:bg-indigo-600 w-full">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
    <div class="mb-6 grid grid-cols-1 gap-6 md:grid-cols-3">
        <!-- Filtro de Fecha -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Fecha</label>
            <input type="date" wire:model.live="selectedDate" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        </div>

        <!-- Filtro de Zona -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Zona</label>
            <select wire:model.live="selectedZone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Todas las zonas</option>
                @foreach($zones as $zone)
                    <option value="{{ $zone->id }}">{{ $zone->nombre }}</option>
                @endforeach
            </select>
        </div>

        <!-- Filtro de Tipo de Llamada -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Tipo de Llamada</label>
            <select wire:model.live="callType" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="all">Todas</option>
                <option value="entrante">Entrantes</option>
                <option value="saliente">Salientes</option>
            </select>
        </div>
    </div>

    <!-- Tabla de Llamadas -->
    <div class="overflow-x-auto bg-white shadow-md sm:rounded-lg">
        @if($calls->isEmpty())
            <div class="text-center py-12">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M12 20a8 8 0 100-16 8 8 0 000 16z" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No hay llamadas disponibles</h3>
                <p class="text-gray-500 mb-6">
                    @if($selectedDate)
                        No se encontraron llamadas para el día {{ \Carbon\Carbon::parse($selectedDate)->format('d/m/Y') }}
                    @elseif($selectedZone)
                        No se encontraron llamadas para la zona seleccionada
                    @elseif($callType && $callType !== 'all')
                        No se encontraron llamadas de tipo {{ $callType === 'entrante' ? 'entrantes' : 'salientes' }}
                    @else
                        No hay llamadas registradas en el sistema
                    @endif
                </p>
            </div>
        @else
            <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha y Hora</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Operador</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paciente</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categoría</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($calls as $call)
                    <tr wire:key="call-{{ $call->id }}" class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $call->fecha_hora->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $call->tipo_llamada === 'entrante' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ ucfirst($call->tipo_llamada) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $call->operador->nombre }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $call->paciente->nombre }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $call->categoria->nombre }}
                            @if($call->subcategoria)
                                <br>
                                <span class="text-xs text-gray-500">{{ $call->subcategoria->nombre }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $call->estado === 'completada' ? 'bg-green-100 text-green-800' : 
                                   ($call->estado === 'en_curso' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ ucfirst($call->estado) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <button wire:click="$dispatch('showCallDetails', { callId: {{ $call->id }} })" 
                                    class="text-indigo-600 hover:text-indigo-900">
                                Ver detalles
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>

    <div class="mt-4">
        {{ $calls->links() }}
    </div>
</div>
