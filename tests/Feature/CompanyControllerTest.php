<?php

use App\Models\Company;
use App\Models\Contact;

uses(Tests\TestCase::class, Illuminate\Foundation\Testing\RefreshDatabase::class);

test('can list companies', function () {
    Company::factory()->count(3)->create();

    $response = $this->getJson('/api/v1/companies');

    $response->assertOk()
        ->assertJsonCount(3, 'data');
});

test('can create company', function () {
    $data = [
        'name' => 'Test Company',
        'domain' => 'testcompany.com'
    ];

    $response = $this->postJson('/api/v1/companies', $data);

    $response->assertCreated()
        ->assertJsonPath('data.name', $data['name']);
});

test('can attach contact to company', function () {
    $company = Company::factory()->create();
    $contactData = [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john@test.com',
        'phone' => '1234567890'
    ];

    $response = $this->postJson("/api/v1/companies/{$company->id}/contacts", $contactData);

    $response->assertCreated()
        ->assertJsonPath('data.company_id', $company->id);
});

test('can update company', function () {
    $company = Company::factory()->create([
         'name' => 'Old Company',
        'domain' => 'OldDomain.com'
    ]);
    
    $response = $this->putJson("/api/v1/companies/{$company->id}", [
        'name' => 'New Name',
        'domain' => 'newDomain.net'
    ]);
    
    $response->assertOk()
        ->assertJsonPath('data.name', 'New Name');
});

test('can soft delete company', function () {
    $company = Company::factory()->create();

    $response = $this->deleteJson("/api/v1/companies/{$company->id}");

    $response->assertOk();
    $this->assertSoftDeleted($company);
});