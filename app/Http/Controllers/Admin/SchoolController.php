<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\School\StoreSchoolRequest;
use App\Http\Requests\School\UpdateSchoolRequest;
use App\Models\School;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;
use Inertia\ResponseFactory;

class SchoolController extends Controller
{

    public function index(): Response|ResponseFactory
    {
        $schools = School::query()
            ->select('id', 'name', 'short_name', 'deleted_at')
            ->filter(request()->only('search', 'trashed','column', 'direction'))
            ->paginate()
            ->withQueryString();

        return inertia('School/Index', [
            'Schools' => $schools,
            'title' => 'All Schools',
            'filters' => request()->all('search', 'trashed','column', 'direction'),
        ]);
    }

    public function create() : Response|ResponseFactory
    {
        return inertia('School/Create');
    }


    public function store(StoreSchoolRequest $request) : RedirectResponse
    {
        School::create($request->safe()->all());
        return redirect()->route('admin.schools.index')->with('success', 'School Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    public function edit(School $school): Response|ResponseFactory
    {
        return inertia('School/Edit',[
            'School' => $school
        ]);
    }

    public function update(UpdateSchoolRequest $request, School $school)
    {
        if ($school->deleted_at) {
            $school->restore();
            return redirect()->route('admin.schools.index')->with('success', "School restored Successfully");
        }
        $school->update($request->safe()->all());
        return redirect()->back()->with('success', 'School Created Successfully');
    }

    public function destroy(School $school):  RedirectResponse
    {
        if ($school->deleted_at) {
            $school->forceDelete();
            return redirect()->route('admin.schools.index')->with('success', "School permanently deleted Successfully");
        }
        $school->delete();
        return redirect()->back()->with('success', "School deleted Successfully");
    }
}
