<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;




class ImportOuiData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:oui-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import IEEE OUI data into the database';

    /**
     * Execute the console command.
     */
    public function handle()

    {
        // Download the latest OUI data CSV
        $response = Http::get('http://standards-oui.ieee.org/oui/oui.csv');
    
        if ($response->successful()) {
            // Parse the CSV data into an array
            $csvData = str_getcsv($response->body(), "\n");
            $headers = str_getcsv(array_shift($csvData)); // Get and remove the header row
    
            foreach ($csvData as $row) {
                $data = str_getcsv($row);
    
                // Create an associative array based on the header row
                $rowData = [];
                foreach ($headers as $index => $header) {
                    if (isset($data[$index])) {
                        $rowData[$header] = $data[$index];
                    }
                }
    
                // Insert data into your table
                DB::table('oui')->insert($rowData);
            }
    
            $this->info('IEEE OUI data imported successfully.');
        } else {
            $this->error('Failed to download IEEE OUI data.');
        }
    }
   
}
