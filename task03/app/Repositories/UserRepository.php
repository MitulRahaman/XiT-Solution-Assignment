<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;


class UserRepository
{
    private $name, $email, $password;

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    public function setCreatedAt($created_at){
        $this->created_at = $created_at;
        return $this;
    }

    public function setUpdatedAt($updated_at){
        $this->updated_at = $updated_at;
        return $this;
    }

    public function storeUser()
    {
        return User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'is_admin' => Config::get('variable_constants.check.no'),
            'is_verified' => Config::get('variable_constants.status.pending'),
        ]);
    }

    public function isAdmin()
    {
        return User::where('email',$this->email)->where('is_admin', Config::get('variable_constants.check.yes'))->first();
    }

    public function isVerified()
    {
        return User::where('email',$this->email)->where('is_verified', Config::get('variable_constants.status.approved'))->first();
    }

    public function allPendingUsers()
    {
        return User::where('is_verified', Config::get('variable_constants.status.pending'))->select('id', 'name', 'email')->get();
    }


}
