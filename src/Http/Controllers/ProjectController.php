<?php

namespace Isotope\CRM\Http\Controllers;

use Isotope\CRM\Models\Client;
use Isotope\CRM\Models\Project;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::get();
        return view('crm::projects.index', compact('projects'));
    }

    public function create()
    {
        $clients = Client::all();
        return view('crm::projects.create-modal', compact('clients'));
    }

    public function store(Request $request)
    {

        $project = Project::create([
            'title'        => $request->input('title'),
            'project_type' => $request->input('project_type'),
            'client_id'    => $request->input('client_id'),
            'description'  => $request->input('description'),
            'start_date'   => $request->input('start_date'),
            'deadline'     => $request->input('deadline'),
            'price'        => $request->input('price'),
            'labels'       => $request->input('labels'),
        ]);
        return redirect()->back()->with('success', 'Project created successfully');
    }

    public function show(Project $project)
    {
        $project->load('client');
        return view('crm::projects.show', compact('project'));
    }

   public function edit($id)
    {
        $project = Project::findOrFail($id);
        return response()->json($project); // ajax এ json send করবো
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'project_type' => 'required|string',
            'client_id' => 'required|integer',
            'start_date' => 'required|date',
        ]);

        $project = Project::findOrFail($id);
        $project->update($request->all());

        return redirect()->back()->with('success', 'Project updated successfully');
    }

    public function delete($id)
    {
        Project::destroy($id);
        return redirect()->back()->with('success', 'Project deleted successfully');
    }
}
