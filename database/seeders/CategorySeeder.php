<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::factory()
            ->count(4)
            ->state(new Sequence(

                ['name' => 'Computers',],
               [ 'name' => 'Books'],
               [ 'name' => 'Video Games'],
               [ 'name' => 'Foods'],

            ))
            ->create();


        //
    }
}
