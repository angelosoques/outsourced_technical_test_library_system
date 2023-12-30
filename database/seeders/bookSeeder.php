<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class bookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $returnValue = DB::table('books')->insert([
            'ISBN' => 1029384765213,
            'book_title' => 'sun and moon',
            'book_author' => 'michael reeves',
            'number_of_copies' => 25,
            'library_id' => 1
        ]);
    }
}
