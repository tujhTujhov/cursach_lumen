<?php


namespace App\Repositories;


use App\Models\User;
use Illuminate\Support\Str;

class UserRepository
{

    /**
     * @param array $fields
     *          [ 'login', 'password', 'email', 'role_id' ]
     * @return string
     */
    public function create(array $fields): string
    {
        $fields['api_token'] = Str::random();
        $user = User::create($fields);
        return $user->api_token;
    }

}
