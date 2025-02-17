<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Models\ContactPerson;
use App\Http\Requests\StoreContactRequest;
use App\Http\Requests\UpdateContactRequest;
use Illuminate\Http\Request;

class ContactsController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->sendResponse(ContactPerson::with('patient')->paginate(10), 'Contacts recuperats ambèxit', 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreContactRequest $request)
    {
        $validated = $request->validated();

        $contact = ContactPerson::create($validated);

        return $this->sendResponse($contact, 'Contacte creat ambèxit', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(ContactPerson $contact)
    {
        $contact = ContactPerson::with('patient')->findOrFail($contact->id);
        return $this->sendResponse($contact, 'Contacte recuperat ambèxit', 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateContactRequest $request, ContactPerson $contact)
    {
        $validated = $request->validated();

        $contact->update($validated);

        return $this->sendResponse($contact, 'Contacte actualitzat ambèxit', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ContactPerson $contact)
    {
        $contact->delete();

        return $this->sendResponse(null, 'Contacte esborrat ambèxit', 204);
    }
}