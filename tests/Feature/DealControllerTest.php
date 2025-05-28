<?php

use App\Models\Contact;
use App\Models\Deal;

uses(Tests\TestCase::class, Illuminate\Foundation\Testing\RefreshDatabase::class);


test('can list deals', function () {
    Deal::factory()->count(10)->create();

    $response = $this->getJson('/api/v1/deals');

    $response->assertOk()
        ->assertJsonCount(10, 'data');
});

test('can create deal', function () {
    $contact = Contact::factory()->create();
    $dealData = [
        'contact_id' => $contact->id,
        'title' => 'Test Deal',
        'amount' => 1000,
        'currency' => 'USD',
        'status' => 'open'
    ];

    $response = $this->postJson('/api/v1/deals', $dealData);

    $response->assertCreated()
        ->assertJsonPath('data.title', 'Test Deal');
});

test('can update deal', function () {
    $deal = Deal::factory()->create(['title' => 'Old Title']);

    $response = $this->putJson("/api/v1/deals/{$deal->id}", [
        'contact_id' => $deal->contact_id,
        'title' => 'New Title',
        'amount' => 1000,
        'currency' => 'USD',
        'status' => 'open'
    ]);

    $response->assertOk()
        ->assertJsonPath('data.title', 'New Title');
});

test('can soft delete deal', function () {
    $deal = Deal::factory()->create();

    $response = $this->deleteJson("/api/v1/deals/{$deal->id}");

    $response->assertOk();
    $this->assertSoftDeleted($deal);
});