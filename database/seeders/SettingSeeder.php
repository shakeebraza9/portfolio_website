<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SettingSeeder extends Seeder
{

    public function run(): void
    {
  $settings = [
    [
        'key' => 'website_name',
        'value' => 'Shakeeb',
    ],
    [
        'key' => 'avatar',
        'value' => 'http://[::1]:5173/resources/js/assets/avatar.png',
    ],
    [
        'key' => 'name',
        'value' => 'Muhammad Shakeeb Raza',
    ],
    [
        'key' => 'exp',
        'value' => 'Full-Stack Developer Specializing in Laravel & Vue.js Ecosystem.',
    ],
    [
        'key' => 'dsc',
        'value' => 'I am a passionate web developer with expertise in Laravel and modern JavaScript frameworks.',
    ],
    [
        'key' => 'cv_url',
        'value' => 'http://[::1]:5173/resources/js/assets/shakeebcv.pdf',
    ],
    [
        'key' => 'contact',
        'value' => '+923406095534',
    ],
    [
        'key' => 'image',
        'value' => 'http://[::1]:5173/resources/js/assets/profile.jpeg',
    ],
    [
        'key' => 'designation',
        'value' => 'Software Engineer',
    ],
    [
        'key' => 'detail_dsc',
        'value' => 'Over the past few years, I have built scalable web applications, managed databases, and worked closely with client requirements to deliver high-quality software solutions.',
    ],
    [
        'key' => 'exp_years',
        'value' => '3', 
    ],
    [
        'key' => 'projects_count',
        'value' => '25', 
    ],
    [
        'key' => 'skills',
        'value' => 'Laravel, PHP, MySQL, Vue.js, React, Docker, AWS, HTML, CSS, JavaScript, Tailwind CSS', 
    ],
    [
        'key' => 'github',
        'value' => 'https://github.com/shakeebraza9',
    ],
    [
        'key' => 'linkdin',
        'value' => 'https://www.linkedin.com/in/muhammad-shakeeb-raza-0990b120a/',
    ],
];

        foreach ($settings as $setting) {
            
            DB::table('settings')->updateOrInsert(
                ['key' => $setting['key']],
                [
                    'value' => $setting['value'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]
            );
        }
    }
}