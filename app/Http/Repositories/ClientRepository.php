<?php
namespace App\Repositories;

use App\Clients;
use Auth;


class ClientRepository{

    public function __construct()
    {
        
    }
    public function updateOrCreate($request)
    {
        if (isset($request['phone'])) {
            # code...
            if (Clients::where('phone', '=', $request['phone'])->exists()) {
                # code...
                return response()->json([
                    "message" => "Client already exist in our system.",
                    "code" => 200,
                ], 200);
                 
            }

            if (Clients::where('email', '=', $request['email'])->exists()) {
                # code...
                return response()->json([
                    "message" => "Client already exist in our system.",
                    "code" => 200,
                ], 200);
                 
            }
      
        }

        $token = bin2hex(openssl_random_pseudo_bytes(35));

        $clients = Clients::create(
            [
                'name' => $request['name'],
                'surname' => $request['surname'],
                'phone' => $request['phone'],
                'email' => $request['email'],
                'password' => Hash::make($request['password']),
                'social_id' => $request['social_id'],
                'auth_type' => $request['auth_type'],
                'device_type' => $request['device_type'],
                'token' => $token,
                'push_token' => $request['push_token'],
                'active' => 1,
            ]
        );

    }

}