<?php

use Illuminate\Database\Seeder;

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

        $user = new App\User;
        $user->username = 'john.doe';
        $user->password = bcrypt('12345');
        $user->first_name = 'John';
        $user->last_name = 'Doe';
        $user->save();
        $user = new App\User;
        $user->username = 'richard.doe';
        $user->password = bcrypt('12345');
        $user->first_name = 'Richard';
        $user->last_name = 'Doe';
        $user->save();
        $user = new App\User;
        $user->username = 'jane.poe';
        $user->password = bcrypt('12345');
        $user->first_name = 'Jane';
        $user->last_name = 'Poe';
        $user->save();
    }
}
