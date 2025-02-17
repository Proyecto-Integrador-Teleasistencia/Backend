<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\StoreAlertRequest;
use App\Http\Requests\UpdateAlertRequest;
use App\Models\Alert;
use App\Http\Resources\AlertResource;
use Illuminate\Http\Request;

class AlertsController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return AlertResource::collection(Alert::with(['category'])->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAlertRequest $request)
    {
        $validated = $request->validated();

        $alert = Alert::create($validated);

        return $this->sendResponse($alert, 'Alerta creada ambèxit', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Alert $alert)
    {
        return $this->sendResponse(new AlertResource($alert), 'Alerta recuperada ambèxit', 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAlertRequest $request, Alert $alert)
    {
        $validated = $request->validated();

        $alert->update($validated);

        return $this->sendResponse(new AlertResource($alert), 'Alerta actualitzada ambèxit', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Alert $alert)
    {
        $alert->delete();

        return $this->sendResponse(null, 'Alerta eliminada ambèxit', 200);
    }
}
