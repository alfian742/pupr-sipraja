<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@gmail.com',
            'role' => 'superadmin',
            'is_active' => true,
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        // User::factory()->create([
        //     'name' => 'Administrator',
        //     'email' => 'admin2@gmail.com',
        //     'role' => 'admin',
        //     'is_active' => true,
        //     'password' => bcrypt('password'),
        //     'email_verified_at' => now(),
        // ]);

        // User::factory()->create([
        //     'name' => 'Operator',
        //     'email' => 'operator@gmail.com',
        //     'role' => 'operator',
        //     'is_active' => true,
        //     'password' => bcrypt('password'),
        //     'email_verified_at' => now(),
        // ]);

        // User::factory()->create([
        //     'name' => 'Pimpinan',
        //     'email' => 'head.of.department@gmail.com',
        //     'role' => 'head_of_department',
        //     'is_active' => true,
        //     'password' => bcrypt('password'),
        //     'email_verified_at' => now(),
        // ]);

        // User::factory(9)->create();

        // Panggil seeder
        $this->call([
            OrganizationProfileSeeder::class,
            OtherInformationSeeder::class,
            MainPerformanceIndicatorSeeder::class,
            RegionalPerformanceIndicatorSeeder::class,
            DepartmentSeeder::class,
            // FinanceSeeder::class,
            BlogSeeder::class,
            // HeroCarouselSeeder::class,
        ]);
    }
}
