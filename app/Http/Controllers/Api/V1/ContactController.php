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
     * Display a listing of the resource.
     */
    public function show(Request $request)
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
     * Update the specified resource in storage.
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
     * Remove the specified resource from storage.
     */
    public function destroy(Contact $contact)
    {
        $contact->delete();

        return response()->json([
            'message' => 'Contact deleted successfully'
        ]);
    }
}
