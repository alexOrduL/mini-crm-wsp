<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\ContactRequest;
use App\Http\Resources\Api\V1\ContactResource;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
    * @OA\Get(
    *     path="/api/v1/contacts",
    *     summary="List all contacs",
    *     tags={"Contacts"},
    *     @OA\Response(
    *         response=200,
    *         description="List of Contacts, it could filter by an specific email"
    *     )
    * )
    */
    public function index(Request $request)
    {
        $query = Contact::query()
            ->with(['company', 'deals']);

        // Filtros
        if ($request->has('email')) {
            $query->where('email', $request->email);
        }

        if ($request->has('withTrashed') && $request->boolean('withTrashed')) {
            $query->withTrashed();
        }

        return ContactResource::collection($query->paginate());
    }

    /**
    * @OA\Put(
    *     path="/api/v1/contacts/{contact}",
    *     summary="Updates a contact",
    *     tags={"Contacts"},
    *     @OA\Response(
    *         response=200,
    *         description="Updates a contact"
    *     )
    * )
    */
    public function update(ContactRequest $contactRequest, Contact $contact)
    {
       try {
            $validated = $contactRequest->validated();

            $contact->update($validated);

            return response()->json([
                'data' => new ContactResource($contact),
                'message' => 'Contact updated successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update contact',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
    * @OA\Delete(
    *     path="/api/v1/contacts/{contact}",
    *     summary="Deletes a contact",
    *     tags={"Contacts"},
    *     @OA\Response(
    *         response=200,
    *         description="it makes a Soft Delete of an specific Contact"
    *     )
    * )
    */
    public function destroy(Contact $contact)
    {
        $contact->delete();

        return response()->json([
            'message' => 'Contact deleted successfully'
        ]);
    }
}
