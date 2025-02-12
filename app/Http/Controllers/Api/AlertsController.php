<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Alert;
use App\Http\Requests\StoreAlertRequest;
use App\Http\Requests\UpdateAlertRequest;
use Illuminate\Http\Request;

class AlertsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Alert::with(['category'])->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAlertRequest $request)
    {
        $validated = $request->validated();

        $alert = Alert::create($validated);

        return response()->json($alert, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $alert = Alert::with(['category'])->findOrFail($id);
        return response()->json($alert);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAlertRequest $request, string $id)
    {
        $alert = Alert::findOrFail($id);
        $validated = $request->validated();
            'periodicity' => 'sometimes|required|string|in:one-time,periodic',
            'datetime' => 'sometimes|required|date',
            'category_id' => 'sometimes|required|exists:categories,id',
        ]);

        $alert->update($validated);

        return response()->json($alert);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $alert = Alert::findOrFail($id);
        $alert->delete();

        return response()->json(null, 204);
    }
}
