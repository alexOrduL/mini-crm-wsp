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
    * @OA\Get(
    *     path="/api/v1/companies",
    *     summary="List all companies",
    *     tags={"Companies"},
    *     @OA\Response(
    *         response=200,
    *         description="List of Companies"
    *     )
    * )
    */
   public function index(Request $request)
    {
        $withTrashed = $request->boolean('withTrashed');

        $query = Company::query()
            ->with([
                'contacts' => fn($q) => $withTrashed ? $q->withTrashed() : $q,
                'deals' => fn($q) => $withTrashed ? $q->withTrashed() : $q,
            ]);

        if ($request->filled('email')) {
            $query->whereHas('contacts', function($q) use ($request) {
                $q->where('email', $request->email);
            });
        }

        if ($withTrashed) {
            $query->withTrashed();
        }

        return CompanyResource::collection($query->paginate());
    }


    /**
    * @OA\Post(
    *     path="/api/v1/companies",
    *     summary="Create a new company",
    *     tags={"Companies"},
    *     @OA\Response(
    *         response=200,
    *         description="Create Company"
    *     )
    * )
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
    * @OA\Post(
    *     path="/api/v1/companies/{companyId}/contacts",
    *     summary="Create a new contact",
    *     tags={"Contacts","Companies"},
    *     @OA\Response(
    *         response=200,
    *         description="Create a new Contact with a relationship with a Company"
    *     )
    * )
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
    * @OA\Put(
    *     path="/api/v1/companies/{company}",
    *     summary="Updates a company",
    *     tags={"Companies"},
    *     @OA\Response(
    *         response=200,
    *         description="Updates a Company"
    *     )
    * )
    */
    public function update(Request $request, Company $company)
    {
        try {
            $company->update($request->validated());

            return response()->json([
                'data' => new CompanyResource($company),
                'message' => 'Company updated successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update company',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
    * @OA\Delete(
    *     path="/api/v1/companies/{companie}",
    *     summary="Deletes a company",
    *     tags={"Companies"},
    *     @OA\Response(
    *         response=200,
    *         description="it makes a Soft Delete of an specific Company"
    *     )
    * )
    */
    public function destroy(Company $company)
    {
        $company->delete();

        return response()->json([
            'message' => 'Company deleted successfully'
        ]);
    }
}
