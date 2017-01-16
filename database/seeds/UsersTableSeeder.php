<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
           'fullname' => 'Ralph John Galindo',
           'username' => 'ralphjohngalindo',
           'password' => bcrypt('ralph123$$'),
           'created_at' => Carbon::now()->format('Y-m-d H:i:s')
       ]);
    }
}
