<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Classes;

class ClassesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Classes::create(['name' => 'Guerreiro']);
        Classes::create(['name' => 'Mago']);
        Classes::create(['name' => 'Arqueiro']);
        Classes::create(['name' => 'Clérigo']);
    }
}
