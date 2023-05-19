<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Path to your CSV file
        $csvFile = database_path('seeds/data/city.csv');
        $csv = fopen($csvFile, 'r');

        // Get the header row from the CSV file
        $header = fgetcsv($csv);

        // Loop through each row in the CSV file
        while (($row = fgetcsv($csv)) !== false) {
            // Combine the header row and current row to create an associative array
            $data = array_combine($header, $row);

            // Insert the data into the database table
            DB::table('cities')->insert($data);
        }

        fclose($csv);
    }
}
