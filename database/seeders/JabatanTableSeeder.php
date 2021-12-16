<?php

namespace Database\Seeders;

use App\Models\Jabatan;
use Illuminate\Database\Seeder;

class JabatanTableSeeder extends Seeder
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
                'namaJabatan' => "Kepala Sekolah",
            ],
            [
                'namaJabatan' => 'Wakil Kepala Sekolah',
            ],
            [
                'namaJabatan' => "Wakil Kepala Sekolah Bidang Kemahasiswaan",
            ],
            [
                'namaJabatan' => "Sektetaris",
            ],
            [
                'namaJabatan' => "Bendahara",
            ],
            
        ];

        Jabatan::insert($jabatan);
    }
}
