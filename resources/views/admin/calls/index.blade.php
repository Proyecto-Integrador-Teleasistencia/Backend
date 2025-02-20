<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Panel de Control de Llamadas
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <livewire:calls-dashboard />
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>
        const pusher = new Pusher('{{ config('broadcasting.connections.pusher.key') }}', {
            cluster: '{{ config('broadcasting.connections.pusher.options.cluster') }}'
        });

        const channel = pusher.subscribe('calls');
        
        channel.bind('CallCreated', (data) => {
            Livewire.dispatch('refreshDashboard');
        });

        channel.bind('CallUpdated', (data) => {
            Livewire.dispatch('refreshDashboard');
        });
    </script>
    @endpush
</x-app-layout>
