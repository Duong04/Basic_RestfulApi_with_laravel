<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\ClasseService;
use Illuminate\Validation\Rule;
use App\Models\Course;
use App\Http\Requests\ClassRequest;

class ClasseController extends Controller
{
    private $classeService;
    public function __construct(ClasseService $classeService) {
        $this->classeService = $classeService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lists = $this->classeService->all();
        return view('admin.classes.list', compact('lists'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $courses = Course::all();
        return view('admin.classes.add', compact('courses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ClassRequest $request)
    {
        $data = $request->validated();

        $this->classeService->create($data);

        return back()->with('success','Classe created successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $result = $this->classeService->find($id);
        $courses = Course::all();
        return view('admin.classes.update', compact('result', 'courses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ClassRequest $request, string $id)
    {
        $data = $request->validated();

        $this->classeService->update($id, $data);
        return redirect()->route('classe.list')->with('success', 'Classe update successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->classeService->delete($id);
        return back()->with('success', 'Classe destroy successfully!');
    }
}
