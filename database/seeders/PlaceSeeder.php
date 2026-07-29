<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Place;

class PlaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $csvFile = database_path('data/places.csv');

        $file = fopen($csvFile, 'r');

        $header = fgetcsv($file);

        while (($row = fgetcsv($file)) !== false) {
            $data = array_combine($header, $row);

            Place::updateOrCreate(
                ['name' => $data['name']],
                [
                    'lat' => (float) $data['lat'],
                    'lng' => (float) $data['lng'],
                    'address' => $data['address'],
                ]
            );
        }

        fclose($file);
    }
}
