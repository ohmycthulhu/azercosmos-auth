<?php


namespace App\Http\Controllers;


use App\Helper\Helper;
use App\Jobs\SynchronizePassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PasswordsController extends Controller
{
    public function __construct()
    {
    }

    public function setPassword (Request $request) {
        $user_id = app()->id;
        $pass = $request->input('password');
        $password = $this->__setPassword($user_id, $pass)->toArray();
        $this->dispatch(new SynchronizePassword($user_id, $pass));
        unset($password['salt']);
        return $password;
    }

    public function login (Request $request) {
        $user_table_name = env('ERP_DB', '').'.PEOPLE';
        $people_id = DB::table($user_table_name )
            ->where('LOGIN', $request->input('login', ''))
            ->first();
        if ($people_id == null) {
            return response('User doesn\'t exists', 401);
        }
        $id = $people_id->ID;
        $password = DB::table('passwords')
            ->where('user_id', $id)->first();
        if ($password == null) {
            return response('You hasn\'t generated password', 402);
        }
        if ($password->password != $request->input('password')) {
            return response('Passwords doesn\'t match', 403);
        }
        return [
            'key' => $id.'/'.Helper::generateHash($id, $password->salt),
            'id' => $id
            ];
    }

    public function checkHash(Request $request) {
        $parts = explode('/', $request->input('hash'));
        if (empty($parts) or sizeof($parts) < 2) {
            return 'Nope';
        }
        $user_id = $parts[0];
        $hash = $parts[1];
        $password = DB::table('passwords')->where('user_id', $user_id)->first();
        return [
            'match' => $password == null ? false : $hash == Helper::generateHash($user_id, $password->salt)
        ];
    }

    public function syncSetPassword(Request $request) {
        $this->__setPassword($request->input('user_id'), $request->input('password'));
    }

    private function __setPassword ($id, $pass) {
        $password = DB::table('passwords')->where('user_id', $id)->first();
        if ($password != null) {
            DB::table('passwords')->where('user_id', $id)->update([
                'password' => $pass,
                'salt' => Helper::generateSalt()
            ]);
        } else {
            $password = DB::table('passwords')->insert([
                'user_id' => $id,
                'password' => $pass,
                'salt' => Helper::generateSalt()
            ]);
        }
        return $password;
    }

}
