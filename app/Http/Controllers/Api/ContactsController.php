<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Models\ContactPerson;
use App\Models\Patient;
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
     * @OA\Get(
     *     path="/api/patients/{patient_id}/contacts",
     *     summary="List contacts of a patient",
     *     tags={"Contacts"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="patient_id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of patient's contacts"
     *     )
     * )
     */
    public function getPatientContacts($patientId)
    {
        $patient = Patient::findOrFail($patientId);
        $contacts = $patient->contacts()->paginate(10);
        return $this->sendResponse($contacts, 'Contactes del pacient recuperats amb èxit');
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
     * @OA\Post(
     *     path="/api/patients/{patient_id}/contacts",
     *     summary="Add a contact to a patient",
     *     tags={"Contacts"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="patient_id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Contact created successfully"
     *     )
     * )
     */
    public function addPatientContact(StoreContactRequest $request, $patientId)
    {
        $patient = Patient::findOrFail($patientId);
        $validated = $request->validated();
        $validated['patient_id'] = $patientId;
        
        $contact = ContactPerson::create($validated);
        
        return $this->sendResponse($contact, 'Contacte creat amb èxit', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(ContactPerson $contact)
    {
        $contact = ContactPerson::with('patient')->findOrFail($contact->id);
        return $this->sendResponse($contact, 'Contacte recuperat amb èxit', 200);
    }

    /**
     * @OA\Put(
     *     path="/api/contacts/{id}",
     *     summary="Update a contact",
     *     tags={"Contacts"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Contact updated successfully"
     *     )
     * )
     */
    public function update(UpdateContactRequest $request, ContactPerson $contact)
    {
        $validated = $request->validated();
        $contact->update($validated);
        return $this->sendResponse($contact, 'Contacte actualitzat amb èxit');
    }

    /**
     * @OA\Delete(
     *     path="/api/contacts/{id}",
     *     summary="Delete a contact",
     *     tags={"Contacts"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Contact deleted successfully"
     *     )
     * )
     */
    public function destroy(ContactPerson $contact)
    {
        $contact->delete();
        return $this->sendResponse(null, 'Contacte eliminat amb èxit');
    }
}