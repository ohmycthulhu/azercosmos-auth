<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use \App\Helper\Helper;

class PasswordsTableSeeder extends Seeder
{
    const DEFAULT_PASSWORD = 'password';
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = DB::table(env('ERP_DB').".PEOPLE")->get();
        foreach($users as $user) {
            DB::table('passwords')->insert([
                'user_id' => ((array)$user)['ID'],
                'password' => self::DEFAULT_PASSWORD,
                'salt' => Helper::generateSalt()
            ]);
        }
        //
    }
}
