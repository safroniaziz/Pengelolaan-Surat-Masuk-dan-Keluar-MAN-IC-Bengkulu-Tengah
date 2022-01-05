<?php

namespace Database\Seeders;

use App\Models\JenisSurat;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SuratKeluarTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        foreach (range(1,10) as $index) {
            DB::table('tb_surat_keluar')->insert([
                'jenisSuratId'    =>  JenisSurat::all()->random()->id,
                'penerima'    =>  $faker->name(),
                'nomorsurat'    =>  $faker->randomNumber(),
                'perihal'    =>  $faker->name(),
                'tujuan'    =>  $faker->name(),
                'lampiran'    =>  $faker->text(),
                'catatan'    =>  $faker->text(),
                'sifatSurat'    =>  $faker->randomElement(['penting','segera','rahasia','biasa']),
                'tanggalSurat' => $faker->dateTime(),
                'created_at'    =>  date(now()),
                'updated_at'    =>  date(now()),
            ]);
        }
    }
}
