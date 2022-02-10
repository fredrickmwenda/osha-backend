<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Clients extends Model
{
    //
    protected $guarded = [];
    protected $table = "clients";

    protected $fillable = [
        'name',
        'surname',
        'phone',
        'email',
        'password',
        'auth_type',
        'device_type',
        'token',
        'push_token',
        'active',
    ];
}
