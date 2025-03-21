<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Postcode;
use League\Csv\Reader;
use ZipArchive;

class ImportPostcodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-postcodes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download and import UK postcodes into the database';

    /**
     * The url at which the ONS postcode Zip can be downloaded
     *
     * @var string
     */
    private $downloadUrl = 'https://www.arcgis.com/sharing/rest/content/items/525b74a332c84146add21c9b54f5ce68/data';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info('Starting UK postcode import...');

        // Download ZIP file
        $zipPath = storage_path('app/postcodes.zip');
        $this->info("Downloading postcodes dataset...");
        file_put_contents($zipPath, file_get_contents($this->downloadUrl));

        // Extract ZIP file
        $extractPath = storage_path('app/postcodes');
        if (!is_dir($extractPath)) {
            mkdir($extractPath, 0777, true);
        }

        $zip = new ZipArchive();
        if ($zip->open($zipPath) === true) {
            $zip->extractTo($extractPath);
            $zip->close();
        } else {
            $this->error('Failed to extract postcode ZIP file.');
            return;
        }

        // Locate CSV file inside `Data dirrctory
        $csvFile = glob("$extractPath/Data/*.csv")[0] ?? null;
        if (!$csvFile) {
            $this->error('No CSV file found inside the extracted ZIP.');
            return;
        }
        $this->info("Processing CSV file: $csvFile");

        // Read CSV and Import Data
        $reader = Reader::createFromPath($csvFile, 'r');
        $reader->setHeaderOffset(0); // Assuming first row contains headers

        $batchSize = 1000;
        $dataCount = 0;
        $data = [];

        foreach ($reader as $record) {
            $data[] = [
                // here we remove spaces from Postcodes for sanitisation purposes
                'postcode' => str_replace(' ', '', trim($record['pcd'])),
                'latitude' => (float) $record['lat'],
                'longitude' => (float) $record['long'],
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if (count($data) >= $batchSize) {
                $dataCount += $batchSize;
                Postcode::insert($data);
                $data = [];
                $this->info("Inserted $dataCount records...");
            }
        }

        if (!empty($data)) {
            Postcode::insert($data);
        }

        $this->info('Postcodes imported successfully!');

    }
}
