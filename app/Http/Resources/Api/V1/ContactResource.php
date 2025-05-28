<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class ContactResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'company_id' => $this->company_id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->when($request->withTrashed, $this->deleted_at),
            'company' => new CompanyResource($this->whenLoaded('company')),
            'deals' => DealResource::collection($this->whenLoaded('deals')),

            'company' => $this->getCompanyRelation(),
            'deals' => $this->getDealsRelation()
        ];
    }

     protected function getCompanyRelation()
    {
        return $this->company_id 
            ? new CompanyResource($this->whenLoaded('company', $this->company)) 
            : null;
    }
    
    protected function getDealsRelation()
    {
        return $this->deals->isNotEmpty() 
            ? DealResource::collection($this->whenLoaded('deals', $this->deals)) 
            : [];
    }

    
}