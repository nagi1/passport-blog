<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();

        User::create(
            [
                'name'     => 'Ahmed Nagi',
                'email'    => 'nagi@tailors.com',
                'password' => bcrypt('password'),
                'is_admin' => false,
            ],
        );
        User::create(
            [
                'name'     => 'Admin',
                'email'    => 'admin@admin.com',
                'password' => bcrypt('admin'),
                'is_admin' => true,
            ]
        );
    }
}
