<?php

use Laravel\Passport\ClientRepository;

if (!function_exists('create')) {
    function create($model, $attributes = [], $count = 1)
    {
        if ($count > 1) {
            return factory($model, $count)->create($attributes);
        } else {
            return factory($model)->create($attributes);
        }
    }
}

if (!function_exists('make')) {
    function make($model, $attributes = [], $count = 1)
    {
        if ($count > 1) {
            return factory($model, $count)->make($attributes);
        } else {
            return factory($model)->make($attributes);
        }
    }
}

if (!function_exists('createHeaders')) {
    function createHeaders($user = null)
    {
        $headers = [
            'Accept' => 'application/json',
            // 'Content-Type' => 'application/json'
        ];

        if ($user) {
            $headers['Authorization'] = 'Bearer ' . $user->createToken('PAT')->accessToken;
        }

        return $headers;
    }

    if (!function_exists('setUpPassport')) {
        function setUpPassport()
        {
            $clientRepo = new ClientRepository();
            $client = $clientRepo->createPersonalAccessClient(null, 'PAT', url('/'));
            DB::table('oauth_personal_access_clients')->insert([
                'client_id' => $client->id,
                'created_at' => new \DateTime(),
                'updated_at' => new \DateTime()
            ]);
        }
    }
}
