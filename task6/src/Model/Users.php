<?php

namespace App\Model;

class Users extends Model
{
    protected $table = 'users';
    protected $fillable = [
        'firstname',
        'lastname',
        'patronymic',
        'email',
        'password'
    ];
}