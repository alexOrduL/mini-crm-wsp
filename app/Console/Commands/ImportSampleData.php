<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;
use App\Models\Company;
use App\Models\Contact;
use App\Models\Deal;

class ImportSampleData extends Command
{
    protected $signature = 'import:sample';
    
    public function handle()
    {
        $path = storage_path('app/sample_data.csv');

        if (!file_exists($path)) {
            $this->error("CSV file not found at: {$path}");
            return Command::FAILURE;
        }

        $csv = Reader::createFromPath($path, 'r');
        $csv->setHeaderOffset(0);
        $records = $csv->getRecords();

        DB::beginTransaction();

        try {
            foreach ($records as $record) {
                $company = null;
                if (!empty($record['company_domain'])) {
                    $company = Company::firstOrCreate(
                        ['domain' => $record['company_domain']],
                        [
                            'id' => $record['company_id'],
                            'name' => $record['company_name'],
                        ]
                    );
                }

                $contact = Contact::firstOrCreate(
                    ['email' => $record['email']],
                    [
                        'id' => $record['contact_id'],
                        'company_id' => $company?->id,
                        'first_name' => $record['first_name'],
                        'last_name' => $record['last_name'],
                        'phone' => $record['phone'],
                    ]
                );

                Deal::firstOrCreate(
                    ['id' => $record['deal_id']],
                    [
                        'contact_id' => $contact->id,
                        'title' => $record['title'],
                        'amount' => $record['amount'],
                        'currency' => $record['currency'],
                        'status' => $record['status'],
                    ]
                );
            }

            DB::commit();
            $this->info('Sample data imported successfully.');
            return Command::SUCCESS;

        } catch (\Throwable $e) {
            DB::rollBack();
            $this->error('Import failed: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
