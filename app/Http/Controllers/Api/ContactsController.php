<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Models\Contacto;
use App\Models\Paciente;
use App\Http\Requests\StoreContactRequest;
use App\Http\Requests\UpdateContactRequest;
use App\Http\Resources\ContactResource;
use Illuminate\Http\Request;

class ContactsController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->sendResponse(Contacto::with('paciente')->paginate(10), 'Contacts recuperats ambèxit', 200);
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
        $patient = Paciente::findOrFail($patientId);
        $contacts = $patient->contacts()->paginate(10);
        return $this->sendResponse($contacts, 'Contactes del pacient recuperats ambèxit');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreContactRequest $request)
    {
        try {
            $validated = $request->validated();

            $contact = Contacto::create($validated);

            return $this->sendResponse(new ContactResource($contact), 'Contacte creat ambèxit', 201);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->sendError('No s ha trobat el pacient amb el id: ' . $request->patient_id, [], 404);
        } catch (\Exception $e) {
            return $this->sendError('Error creant el contacte', [], 500);
        }
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
        try {
            $patient = Paciente::findOrFail($patientId);
            $validated = $request->validated();
            $validated['paciente_id'] = $patientId;
            
            $contact = Contacto::create($validated);
            
            return $this->sendResponse(new ContactResource($contact), 'Contacte creat ambèxit', 201);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->sendError('No s ha trobat el pacient amb el id: ' . $patientId, [], 404);
        } catch (\Exception $e) {
            return $this->sendError('Error creant el contacte', [], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $contact = Contacto::with('paciente')->findOrFail($id);
            return $this->sendResponse($contact, 'Contacte recuperat amb èxit', 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->sendError('No s ha trobat el contacte amb el id: ' . $id, [], 404);
        } catch (\Exception $e) {
            return $this->sendError('Error recuperant el contacte', [], 500);
        }
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
    public function update(UpdateContactRequest $request, Contacto $contact)
    {
        try {
            $validated = $request->validated();
            $contact->update($validated);
            return $this->sendResponse(new ContactResource($contact), 'Contacte actualitzat ambèxit', 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->sendError('No s ha trobat el contacte amb el id: ' . $contact->id, [], 404);
        } catch (\Exception $e) {
            return $this->sendError('Error actualitzant el contacte', [], 500);
        }
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
    public function destroy(Contacto $contact)
    {
        try {
            $contact->delete();
            return $this->sendResponse(null, 'Contacte eliminat amb èxit', 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->sendError('No s ha trobat el contacte amb el id: ' . $contact->id, [], 404);
        } catch (\Exception $e) {
            return $this->sendError('Error eliminant el contacte', [], 500);
        }
    }
}