<?php

namespace Database\Seeders;

use App\Models\Jabatan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        foreach (range(1,15) as $index) {
            DB::table('tb_user')->insert([
                'jabatanId'    =>  Jabatan::all()->random()->id,
                'nip'       =>  $faker->randomNumber,
                'namaUser'    =>  $faker->name(),
                'password'    =>  bcrypt("password"),
                'email'     =>  $faker->email(),
                'hakAkses' =>  $faker->randomElement(['admin','staf_tu','pimpinan']),
                'status'    =>  $faker->randomElement(['aktif','nonaktif']),
            ]);
        }
    }
}
