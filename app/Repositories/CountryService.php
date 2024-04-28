<?php 

namespace App\Repositories;

use App\Models\Countries;

class CountryService
{
    public function all()
    {
        return Countries::all();
    }

    public function create($data)
    {
        return Countries::create($data);
    }

    public function find($id)
    {
        return Countries::findOrFail($id);
    }

    public function update($id, $data)
    {
        $country = Countries::findOrFail($id);
        $country->update($data);
        return $country;
    }

    public function delete($id)
    {
        $country = Countries::findOrFail($id);
        $country->delete();
    }
}