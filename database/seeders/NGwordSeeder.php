<?php

namespace Database\Seeders;

use App\Models\NGword;
use Illuminate\Database\Seeder;

class NGwordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        NGword::factory(20)->create();
    }
}
