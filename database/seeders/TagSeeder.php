<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Tag::firstOrCreate(['nombre' => 'Correción cedula']);
        Tag::firstOrCreate(['nombre' => 'Borrar ingreso']);
        Tag::firstOrCreate(['nombre' => 'Corregir nombre paciente']);
    }
}
