<?php

class UserTableSeeder extends Seeder {
 
    public function run()
    {
        DB::table('users')->delete();
        User::create(array('username' => '', 'password' => '', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()));
    }
 
}
