<?php

use Illuminate\Support\Facades\File;
use function Pest\Laravel\artisan;
use function Pest\Laravel\assertDatabaseHas;

uses(Tests\TestCase::class);

test('can import sample data', function () {
    $csv = <<<CSV
company_id,company_name,company_domain,contact_id,first_name,last_name,email,phone,deal_id,title,amount,currency,status
c1c35e0e-0001-4000-bb01-000000000001,Test Co,testco.example.com,a1c35e0e-1001-4000-bb01-000000000001,Ana,Lopez,ana@testco.com,123456789,d1c35e0e-2001-4000-bb01-000000000001,Consulting,1500,USD,open
CSV;

    $path = storage_path('app/sample_data.csv');
    File::ensureDirectoryExists(dirname($path));
    file_put_contents($path, $csv);

    artisan('import:sample')->assertExitCode(0);

    assertDatabaseHas('companies', ['domain' => 'testco.example.com']);
    assertDatabaseHas('contacts', ['email' => 'ana@testco.com']);
    assertDatabaseHas('deals', ['title' => 'Consulting']);

    unlink($path);
});

test('fails when file not found', function () {
    $path = storage_path('app/sample_data.csv');
    if (file_exists($path)) {
        unlink($path);
    }

    artisan('import:sample')
        ->expectsOutputToContain('CSV file not found at:')
        ->assertExitCode(1);
});
