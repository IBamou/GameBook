<?php

namespace Database\Seeders;

use App\Models\Table;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TableSeeder extends Seeder
{
    public function run(): void
    {
        Table::create(['capacity' => 4, 'reference' => 'T1']);
        Table::create(['capacity' => 6, 'reference' => 'T2']);
        Table::create(['capacity' => 8, 'reference' => 'T3']);
    }
}
