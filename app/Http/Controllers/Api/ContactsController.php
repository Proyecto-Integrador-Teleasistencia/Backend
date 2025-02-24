<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Models\Contacto;
use App\Models\Paciente;
use App\Http\Requests\StoreContactRequest;
use App\Http\Requests\UpdateContactRequest;
use App\Http\Resources\ContactResource;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class ContactsController extends BaseController
{
    /**
     * @OA\Get(
     *     path="/api/contacts",
     *     summary="Listar todos los contactos",
     *     tags={"Contacts"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de contactos recuperada con éxito",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(ref="#/components/schemas/ContactResource")
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        return $this->sendResponse(Contacto::with('paciente')->get(), 'Contacts recuperats ambèxit', 200);
    }

    /**
     * @OA\Get(
     *     path="/api/patients/{patient_id}/contacts",
     *     summary="Obtener contactos de un paciente",
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
     *         description="Lista de contactos del paciente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(ref="#/components/schemas/ContactResource")
     *             )
     *         )
     *     )
     * )
     */
     public function getPatientContacts($patientId)
     {
         try {
             $patient = Paciente::findOrFail($patientId);
             $contacts = $patient->contactos()->get();
             return $this->sendResponse(ContactResource::collection($contacts), 'Contactes del pacient recuperats ambèxit', 200);
         } catch (\Exception $e) {
             return $this->sendError('Error al recuperar els contactes del pacient', [], 500);
         }
     }

/**
     * @OA\Post(
     *     path="/api/contacts",
     *     summary="Crear un nuevo contacto",
     *     tags={"Contacts"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreContactRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Contacto creado exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/ContactResource")
     *     )
     * )
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
     *     summary="Agregar un contacto a un paciente",
     *     tags={"Contacts"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="patient_id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreContactRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Contacto agregado exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/ContactResource")
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
     * @OA\Get(
     *     path="/api/contacts/{id}",
     *     summary="Obtener detalles de un contacto",
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
     *         description="Contacto recuperado con éxito",
     *         @OA\JsonContent(ref="#/components/schemas/ContactResource")
     *     )
     * )
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
     *     summary="Actualizar un contacto",
     *     tags={"Contacts"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateContactRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Contacto actualizado exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/ContactResource")
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
     *     summary="Eliminar un contacto",
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
     *         description="Contacto eliminado exitosamente"
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