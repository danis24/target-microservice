<?php

use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
    * Run the database seeds.
    *
    * @return void
    */
    public function run()
    {
        factory(\App\Services\Users\User::class, 10)->create(
            [
                'password' => app('hash')->make('suckhack24'),
            ]
        );
    }
}
