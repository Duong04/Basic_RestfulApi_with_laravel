<?php 

namespace App\Repositories;

use App\Models\Classe;

class ClasseService
{
    public function all()
    {
        return Classe::with('course')->get();
    }

    public function create($data)
    {
        return Classe::create($data);
    }

    public function find($id)
    {
        return Classe::findOrFail($id);
    }

    public function update($id, $data)
    {
        $classe = Classe::findOrFail($id);
        $classe->update($data);
        return $classe;
    }

    public function delete($id)
    {
        $classe = Classe::findOrFail($id);
        $classe->delete();
    }
}