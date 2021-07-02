<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            User::create([
                'name' => 'Admin',
                'last_name' => 'HDLR',
                'fullname' => 'Admin HDLR',
                'email' => 'admin@hdlr.com',
                'admin' => '1',
                'password' => Hash::make('12345678'),
                'whatsapp' => '23423423423432',
                'referred_id' => 0,
            ]);
        } catch (\Throwable $th) {
            dd($th);
            //throw $th;
        }
    }
}
