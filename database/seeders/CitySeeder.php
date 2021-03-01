<?php
namespace Database\Seeders;

use App\Models\City;
use App\Models\Province;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $file = __DIR__ . '/csvjson.json';
        $data = json_decode(file_get_contents($file), true);

        $cityKey = '都道府県名（漢字）';
        $proKey = '市区町村名（漢字）';

        $inserts = [];

        foreach ($data as $row)
        {
            if (empty($inserts[$row[$cityKey]])) {
                $inserts[$row[$cityKey]] = [];
            }

            if (!empty($row[$proKey])) {
                $inserts[$row[$cityKey]][] = ['name' => $row[$proKey]];
            }
        }

        unset($data);

        $current = new \MongoDB\BSON\UTCDateTime(now());

        foreach ($inserts as $city => $proList)
        {
            $city = City::create(['name' => $city]);
            array_walk($proList, function (&$pro) use($city, $current) {
                $pro['id_city'] = $city->getKey();
                $pro['updated_at'] = $pro['created_at'] = $current;
            });

            Province::insert($proList);
        }
    }
}
