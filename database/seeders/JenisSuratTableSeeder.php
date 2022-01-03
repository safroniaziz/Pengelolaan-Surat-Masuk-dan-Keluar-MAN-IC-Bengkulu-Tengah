<?php

namespace Database\Seeders;

use App\Models\JenisSurat;
use Illuminate\Database\Seeder;

class JenisSuratTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        $jabatan = [
            [
                'jenisSurat' => "Surat Undangan",
            ],
            [
                'jenisSurat' => 'Surat Keputusan',
            ],
            [
                'jenisSurat' => "Surat Pengantar",
            ],
            [
                'jenisSurat' => "Surat Permohonan",
            ],
            [
                'jenisSurat' => "Bendahara",
            ],
            
        ];

        JenisSurat::insert($jabatan);
    }
}
