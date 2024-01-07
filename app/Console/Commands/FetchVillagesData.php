<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class FetchVillagesData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-villages-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Se encarga de coger datos de la api de MediaWiki, procesarlos y añadirlos a la base de datos';
//safsa 
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $villagesString = $this->readVillagesFromFile();
        $villagesArray = $this->parseVillagesString($villagesString);

        print_r($villagesArray);
        foreach ($villagesArray as $village => $province) {
            $response = Http::get('https://es.wikipedia.org/w/api.php', [
                'action' => 'query',
                'formatversion' => 2,
                'format' => 'json',
                'prop' => 'pageimages|pageterms|extracts',
                'titles' => $village,
                'pilimit' => 3,
                'piprop' => 'thumbnail',
                'pithumbsize' => 500,
                'wbptterms' => 'description',
                'redirects' => '',
                'exintro' => '',
                'explaintext' => '',
            ]);
            echo "Processing village: " . $village . "\n";

            if ($response->successful()) {
                $data = $response->json();
                echo "Data fetched for $village\n";

                if(empty($data)){
                    echo "No data returned for $village\n";
                }else{
                    print_r($data);

                }
            } else {
                echo "Failed to fetch data for $village\n";
                print_r($response->body()); // This will show the error message from the API, if any.

            }
        }
    }

    private function parseVillagesString($villagesString)
    {
        $villagesArray = [];
        $villagesList = explode(',', $villagesString);
        foreach ($villagesList as $villageEntry) {
            preg_match('/^(.*?)\s*\[([A-Z])\]$/', trim($villageEntry), $matches);
            if (count($matches) === 3) {
                $villageName = $matches[1];
                $provinceCode = $matches[2];
                $provinceName = $this->mapProvinceCodeToName($provinceCode);
                $villagesArray[$villageName] = $provinceName;
            }
        }
        return $villagesArray;
    }

    private function mapProvinceCodeToName($provinceCode)
    {
        $map = ['L' => 'Lerida', 'B' => 'Barcelona', 'G' => 'Girona', 'T' => 'Tarragona'];
        return $map[$provinceCode] ?? 'Unknown';
    }

    private function extractComarcaFromData($data)
{

}

    public function readVillagesFromFile()
    {
        return Storage::disk('local')->get('pobles.txt'); 
    }
}