<?php

namespace App\Livewire;

use App\Models\Llamada;
use App\Models\Zona;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;
use Livewire\Attributes\On;

class CallsDashboard extends Component
{
    use WithPagination;

    public $selectedDate;
    public $selectedZone;
    public $callType = 'all'; // 'all', 'entrante', 'saliente'
    public $showCallDetails = false;
    public $selectedCall = null;
    
    protected $queryString = [
        'selectedDate' => ['except' => ''],
        'selectedZone' => ['except' => ''],
        'callType' => ['except' => 'all'],
    ];

    public function mount()
    {
        $this->selectedDate = now()->format('Y-m-d');
    }

    #[On('refreshDashboard')]
    public function refresh()
    {
        // La vista se actualizará automáticamente
    }

    public function showCallDetails($callId)
    {
        $this->selectedCall = Llamada::with(['operador', 'paciente', 'categoria', 'subcategoria', 'aviso'])
            ->findOrFail($callId);
        $this->showCallDetails = true;
    }

    public function closeCallDetails()
    {
        $this->showCallDetails = false;
        $this->selectedCall = null;
    }

    public function updatedSelectedDate()
    {
        $this->resetPage();
    }

    public function updatedSelectedZone()
    {
        $this->resetPage();
    }

    public function updatedCallType()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Llamada::query()->with(['paciente', 'operador', 'categoria', 'subcategoria']);

        if ($this->selectedDate) {
            $query->whereDate('fecha_hora', $this->selectedDate);
        }

        if ($this->selectedZone) {
            $query->whereHas('paciente', function ($q) {
                $q->where('zona_id', $this->selectedZone);
            });
        }

        if ($this->callType !== 'all') {
            $query->where('tipo_llamada', $this->callType);
        }

        $calls = $query->orderBy('fecha_hora', 'desc')->paginate(10);
        $zones = Zona::all();

        return view('livewire.calls-dashboard', [
            'calls' => $query->orderBy('fecha_hora', 'desc')->paginate(5),
            'zones' => $zones
        ]);
    }
}
