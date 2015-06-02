<?php

class UserTableSeeder extends Seeder {
 
    public function run()
    {
        DB::table('users')->delete();
        User::create(array('username' => 'vt_admin', 'password' => 'ItalianoVeroAdmin', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()));
    }
 
}