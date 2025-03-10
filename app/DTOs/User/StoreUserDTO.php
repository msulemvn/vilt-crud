<?php

namespace App\DTOs\User;

use App\DTOs\BaseDTO;
use Illuminate\Support\Facades\Hash;

class StoreUserDTO extends BaseDTO
{
    public string $name;
    public string $email;
    public string $password;

    public function __construct($request)
    {
        $this->name = $request['name'];
        $this->email = $request['email'];
        $this->password = Hash::make($request['password']);
    }
}
