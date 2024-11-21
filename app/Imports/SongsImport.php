<?php

namespace App\Imports;

use App\Models\Artist;
use App\Models\Category;
use App\Models\Language;
use App\Models\Song;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SongsImport implements ToModel, WithHeadingRow
{
    /**
     * Specify the row where the headers are located.
     * The default is the first row, but you can specify another row if needed.
     *
     * @return int
     */
    public function headingRow(): int
    {
        return 1;  // Set the header row to 1 (the default) or another row if needed.
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $row = array_map('trim', $row); // Trim spaces from each header

        if (!isset($row['artist_name'])) {
            return null; // Return null if the column doesn't exist (could log this error)
        }


        // Sanitize column names to remove any extra spaces
        $row = array_map('trim', $row); // Trim spaces from each header

        return new Song([
            'artist_id' => Artist::firstOrCreate(['name' => $row['artist_name']])->id,
            'name' => $row['song_title'],
            'language_id' => Language::firstOrCreate(['name' => $row['language']])->id,
            'category_id' =>  $row['category'] ? Category::firstOrCreate(['name' => $row['category']])->id : '',
        ]);
    }
}
