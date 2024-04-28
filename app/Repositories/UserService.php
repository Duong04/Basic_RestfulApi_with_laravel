<?php 

namespace App\Repositories;

use App\Models\User;

class UserService {
    public function all()
    {
        return User::all();
    }

    public function create($data)
    {
        return User::create($data);
    }

    public function find($id)
    {
        return User::findOrFail($id);
    }

    public function update($id, $data)
    {
        $user = User::findOrFail($id);
        $user->update($data);
        return $user;
    }

    public function delete($id)
    {
        $country = User::findOrFail($id);
        $country->delete();
    }
}