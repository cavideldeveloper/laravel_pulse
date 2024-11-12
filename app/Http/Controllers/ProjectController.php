<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       

        $projects = Project::all();
       
        return view('projects.index', compact('projects')); // Pass to view
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //

        $request->validate([
            'name' => 'required|string|max:255',
            'link' => 'required|url'
        ]);

        Project::create($request->only('name', 'link'));

        return redirect()->route('projects.index')->with('status', 'Added Successfully');
        ;
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        //

        $request->validate([
            'name' => 'required|string|max:255',
            'link' => 'required|url'
        ]);

        $project->update($request->only('name', 'link'));

        return redirect()->route('projects.index')->with('status', 'Updated Successfully');
        ;

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        //

        $project->delete();
        return redirect()->route('projects.index')->with('status', 'Deleted Successfully');;

    }
}