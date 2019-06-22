<?php


namespace App\Jobs;


use GuzzleHttp\Client;

class SynchronizePassword extends Job
{
    protected $user_id;
    protected $password;
    public function __construct($user_id, $password)
    {
        $this->user_id = $user_id;
        $this->password = $password;
    }

    public function handle() {
        $client = new Client();
        if (env('OTHER_SERVER_URL', null) == null) {
            return;
        }
        $url = env('OTHER_SERVER_URL', '').'/synchronize/passwords';
        $client->request('POST', $url, [
            'form_params' => [
                'user_id' => $this->user_id,
                'password' => $this->password
            ]
        ]);
    }
}