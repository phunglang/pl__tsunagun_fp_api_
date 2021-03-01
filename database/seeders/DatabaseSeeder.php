<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CitySeeder::class);
        $this->call(UserSeeder::class);
        $this->call(NGwordSeeder::class);
        $this->call(NewsSeeder::class);
        $this->call(SkillSeeder::class);
        $this->call(PostSeeder::class);
        $this->call(JobSeeder::class);
        $this->call(LikeSeeder::class);
        $this->call(ReportSeeder::class);
        $this->call(MessageSeeder::class);
        $this->call(CertificateSeeder::class);
    }
}
