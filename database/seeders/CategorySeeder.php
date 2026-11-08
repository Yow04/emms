<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    //Run Database Seeder
    public function run(): void
    {
        $categories = [
            [
                'name' => 'hardware',
                'description' => 'Permasalahan perangkat keras komputer dan periferal',
            ],

            [
                'name' => 'software',
                'description' => 'Permasalahan perangkat lunak aplikasi sistem operasi atau driver'
            ],

            [
                'name' => 'network',
                'description' => 'Permasalahan koneksi dan jaringan' 
            ],

            [
                'name' => 'user_access',
                'description' => 'Permasalahan hak akses akun atau otorisasi'
            ],

            [
                'name' => 'other',
                'description' => 'Permasalahan lain yang tidak termasuk kategori di atas'
            ],
        ];

        foreach ($categories as $category){
            Category::updateOrCreate([
                'name' => $category['name'],
            ], $category);
        }
    }
}
