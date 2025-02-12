<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactPerson;
use App\Http\Requests\StoreContactRequest;
use App\Http\Requests\UpdateContactRequest;
use Illuminate\Http\Request;

class ContactsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(ContactPerson::with('patient')->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreContactRequest $request)
    {
        $validated = $request->validated();

        $contact = ContactPerson::create($validated);

        return response()->json($contact, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $contact = ContactPerson::with('patient')->findOrFail($id);
        return response()->json($contact);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateContactRequest $request, string $id)
    {
        $contact = ContactPerson::findOrFail($id);
        $validated = $request->validated();
            'patient_id' => 'sometimes|required|exists:patients,id',
            'name' => 'sometimes|required|string|max:255',
            'relationship' => 'sometimes|required|string|max:100',
            'phone' => 'sometimes|required|string|max:20',
            'address' => 'sometimes|required|string',
            'availability' => 'sometimes|required|string',
            'has_keys' => 'sometimes|required|boolean',
            'priority_level' => 'sometimes|required|integer|min:1|max:5',
        ]);

        $contact->update($validated);

        return response()->json($contact);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $contact = ContactPerson::findOrFail($id);
        $contact->delete();

        return response()->json(null, 204);
    }
}
