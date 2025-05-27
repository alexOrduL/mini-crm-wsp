<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CompanyFactory extends Factory
{
    protected $model = Company::class;

    public function definition()
    {
        $name = $this->faker->company;
        $domain = Str::slug($name) . '.' . $this->faker->safeEmailDomain;
        return [
            'id' => (string) Str::uuid(),
            'name' => $name,
            'domain'=> $domain
        ];
    }
}
