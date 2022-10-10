<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('users')->insert([
            'name'       => 'Admin',
            'email'      => 'email@email.com',
            'user_group' => 1,
            'status'     => 'active',
            'user_type'  => 'administrator',
            'password'   => Hash::make('12345678'),
        ]);
    }
}
