<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema; 

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
     
        Schema::disableForeignKeyConstraints();

   
        DB::table('projects')->truncate();

        DB::table('projects')->insert([
            [
                'name' => 'Autoboli',
                'slug' => Str::slug('Autoboli'),
                'url' => 'http://localhost/portfolio/autoboli',
                'github_link' => 'https://github.com/shakeeb/autoboli',
                'technologies' => 'Vue 3, Vite, Laravel API, TailwindCSS',
                'image' => 'autoboli.jpg',
                'database' => 'MySQL',
                'framework' => 'Laravel & Vue',
                'description' => 'Cars ki buying aur selling k liye ek behtareen bidding aur auction platform jahan users gaariyo par online boli laga sakte hain.',
                'video' => null,
                'date' => '2026-01-10',
                'developed_by' => 'Shakeeb',
                'author' => 'Shakeeb',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Therareperfumes',
                'slug' => Str::slug('Therareperfumes'),
                'url' => 'http://localhost/portfolio/therareperfumes',
                'github_link' => 'https://github.com/shakeeb/therareperfumes',
                'technologies' => 'Vue.js, Pinia, Laravel Web Services, Bootstrap 5',
                'image' => 'perfumes.jpg',
                'database' => 'MySQL',
                'framework' => 'Laravel & Vue',
                'description' => 'Luxury aur premium perfumes k liye ek shandar E-Commerce website jahan shopping cart aur secure checkout ka option majood hai.',
                'video' => null,
                'date' => '2026-02-15',
                'developed_by' => 'Shakeeb',
                'author' => 'Shakeeb',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Rent Car',
                'slug' => Str::slug('Rent Car'),
                'url' => null,
                'github_link' => 'https://github.com/shakeeb/rent-car',
                'technologies' => 'Laravel, Vue Router, TailwindCSS',
                'image' => 'rent-car.jpg',
                'database' => 'MySQL',
                'framework' => 'Laravel & Vue',
                'description' => 'Gariyan rent par lene aur dene ka system. Isme gariyo ki availability, per day rent calculator aur booking management shamil hai.',
                'video' => null,
                'date' => '2026-03-22',
                'developed_by' => 'Shakeeb',
                'author' => 'Shakeeb',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Animal Food Selling',
                'slug' => Str::slug('Animal Food Selling'),
                'url' => null,
                'github_link' => 'https://github.com/shakeeb/animal-food',
                'technologies' => 'Vue 3, Laravel, Axios, MySQL',
                'image' => 'animal-food.jpg',
                'database' => 'MySQL',
                'framework' => 'Laravel & Vue',
                'description' => 'Pets aur animals k khane (food) aur accessories online sell karne ka store, jisme category wise products search karne ka feature hai.',
                'video' => null,
                'date' => '2026-04-05',
                'developed_by' => 'Shakeeb',
                'author' => 'Shakeeb',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'POS Management System',
                'slug' => Str::slug('POS Management System'),
                'url' => null,
                'github_link' => 'https://github.com/shakeeb/pos-system',
                'technologies' => 'Laravel, Vue.js, Alpine.js, TailwindCSS',
                'image' => 'pos.jpg',
                'database' => 'MySQL',
                'framework' => 'Laravel',
                'description' => 'Dukan ya business ka khata chalane k liye Point of Sale system. Isme inventory tracking, billing, invoice generation aur sales reports milti hain.',
                'video' => null,
                'date' => '2026-05-12',
                'developed_by' => 'Shakeeb',
                'author' => 'Shakeeb',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ghareeb Nawaz Medical',
                'slug' => Str::slug('Ghareeb Nawaz Medical'),
                'url' => null,
                'github_link' => 'https://github.com/shakeeb/medical-store',
                'technologies' => 'Vue.js, Vite, Laravel Sanctum, Bootstrap',
                'image' => 'medical.jpg',
                'database' => 'MySQL',
                'framework' => 'Laravel & Vue',
                'description' => 'Medical store aur pharmacy mmanagement k liye software, jahan dawaiyon (medicines) ka stock, expiry dates aur customer sales ka record rkha jata hai.',
                'video' => null,
                'date' => '2026-06-01',
                'developed_by' => 'Shakeeb',
                'author' => 'Shakeeb',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Data Scraping Bots',
                'slug' => Str::slug('Data Scraping Bots'),
                'url' => null,
                'github_link' => 'https://github.com/shakeeb/scraping-bots',
                'technologies' => 'Python, Node.js, Laravel (Dashboard)',
                'image' => 'scraping.jpg',
                'database' => 'MySQL',
                'framework' => 'Laravel & Python',
                'description' => 'Mukhtalif websites se automated tareeqe se data nikalne (scrape karne) k bots, jo data ko sanbhal kar database me save karte hain.',
                'video' => null,
                'date' => '2026-06-25',
                'developed_by' => 'Shakeeb',
                'author' => 'Shakeeb',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);


        Schema::enableForeignKeyConstraints();
    }
}