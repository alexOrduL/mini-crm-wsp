<?php

use App\Models\Contact;

uses(Tests\TestCase::class, Illuminate\Foundation\Testing\RefreshDatabase::class);

test('can list contacts', function () {
    Contact::factory()->count(10)->create();

    $response = $this->getJson('/api/v1/contacts');

    $response->assertOk()
        ->assertJsonCount(10, 'data');
});

test('can update contact', function () {
    $contact = Contact::factory()->create(['first_name' => 'Old Name']);

    $response = $this->putJson("/api/v1/contacts/{$contact->id}", [
        'first_name' => 'New Name',
        'last_name' => $contact->last_name,
        'email' => $contact->email,
        'phone' => $contact->phone,
    ]);

    $response->assertOk()
        ->assertJsonPath('data.first_name', 'New Name');
});

test('can soft delete contact', function () {
    $contact = Contact::factory()->create();

    $response = $this->deleteJson("/api/v1/contacts/{$contact->id}");

    $response->assertOk();
    $this->assertSoftDeleted($contact);
});