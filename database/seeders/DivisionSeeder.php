<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Division;

class DivisionSeeder extends Seeder
{
    public function run(): void
    {
        Division::create(['d_code' => 'DIV001', 'd_name' => 'IT Support', 'd_desc' => 'Divisi Teknologi Informasi']);
        Division::create(['d_code' => 'DIV002', 'd_name' => 'HRD', 'd_desc' => 'Divisi Sumber Daya Manusia']);
        Division::create(['d_code' => 'DIV003', 'd_name' => 'Finance', 'd_desc' => 'Divisi Keuangan']);
    }
}   