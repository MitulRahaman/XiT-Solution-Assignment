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
    private $id, $name, $email, $password;

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

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

    public function accept()
    {
        return User::where('id',$this->id)
            ->update(['is_verified' => Config::get('variable_constants.status.approved'), 'updated_at' => $this->updated_at]);
    }

    public function decline()
    {
        return User::where('id',$this->id)
            ->update(['is_deleted' => Config::get('variable_constants.check.yes'), 'updated_at' => $this->updated_at]);
    }

    public function allPendingUsers()
    {
        return User::where('is_verified', Config::get('variable_constants.status.pending'))
            ->where('is_deleted', '=', Config::get('variable_constants.check.no'))
            ->select('id', 'name', 'email')
            ->get();
    }


}
