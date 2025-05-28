<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\CompanyResource;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Requests\Api\V1\CompanyRequest;  // Correcto
use App\Http\Requests\Api\V1\ContactRequest;
use App\Http\Resources\Api\V1\ContactResource;
use App\Models\Contact;

class CompanyController extends Controller
{
      /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $query = Company::query()
            ->with(['contacts', 'deals']);

        if ($request->has('email')) {
            $query->whereHas('contacts', function($q) use ($request) {
              $q->where('email', $request->email);
            });
        }

        if ($request->has('withTrashed') && $request->boolean('withTrashed')) {
            $query->withTrashed();
        }

        return CompanyResource::collection($query->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CompanyRequest $request)
    {
        try {
            $company = Company::create($request->validated());

            return response()->json([
                'data' => new CompanyResource($company),
                'message' => 'Company created successfully'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create contact',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * attach a newly created resource in storage.
     */
    public function attachContact(ContactRequest $contactRequest, $companyId)
    {
        try {
            $validated = $contactRequest->validated();
            $validated['company_id'] = $companyId;

            $contact = Contact::create($validated);

            return response()->json([
                'data' => new ContactResource($contact),
                'message' => 'Contact created successfully'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create contact',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Company $company)
    {
        try {
            $company->update($request->validated());

            return response()->json([
                'data' => new CompanyResource($company),
                'message' => 'Company updated successfully' // Cambiado de "Contact" a "Company"
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update company',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        $company->delete();

        return response()->json([
            'message' => 'Company deleted successfully'
        ]);
    }
}
