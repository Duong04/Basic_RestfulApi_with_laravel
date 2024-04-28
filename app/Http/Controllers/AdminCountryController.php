<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Providers\CountryServiceProvider;
use App\Repositories\CountryService;
use App\Http\Requests\CountryRequest;
use Illuminate\Validation\Rule;

class AdminCountryController extends Controller
{
    protected $countryService;
    public function __construct(CountryService $countryService)
    {
        $this->countryService = $countryService;
    }

    public function index()
    {
        $listCountries = $this->countryService->all();

        return view('admin.countries.list', compact('listCountries'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.countries.add');
    }

    public function store(Request $request)
    {
        $request->validate([
            'country_code' => 'required|max:2|unique:apps_countries,country_code',
            'country_name' => 'required'
            ], 
        );
        $this->countryService->create($request->all());
        return back()->with('success','Thêm thành công!');
    }

    /**
     * Display the specified resource.
     */
    // public function show(string $id)
    // {
    //     $result = $this->countryService->find($id);
    //     return $result;
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $result = $this->countryService->find($id);
        return view('admin.countries.update', compact('result'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CountryRequest $request, string $id)
    {
        $request->validate([
            'country_code' => ['required','max:2', Rule::unique('apps_countries')->ignore($id)],
            'country_name' => 'required'
        ]);
        $this->countryService->update($id, $request->all());
        return redirect('../../admin/list-countries')->with('success', 'Cập nhật thành công');
    }

    public function destroy(string $id)
    {
        $this->countryService->delete($id);
        return back()->with('success', 'Xóa thành công');
    }
}
