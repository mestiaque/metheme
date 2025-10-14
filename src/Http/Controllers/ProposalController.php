<?php

namespace Isotope\CRM\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Isotope\CRM\Models\Task;
use Isotope\CRM\Models\Project;
use App\Http\Controllers\Controller;

class ProposalController extends Controller
{
    public function index()
    {

        $tasks = Task::get();
        $projects = Project::all();
        $users = User::all();
        return view('crm::proposals.index', compact('tasks', 'projects', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'project_id' => 'required|exists:projects,id',
            'assigned_to' => 'required|exists:users,id',
            'start_date' => 'required|date',
            'status' => 'required|string'
        ]);

        $task = Task::create([
            'title'        => $request->title,
            'description'  => $request->description,
            'project_id'   => $request->project_id,
            'points'       => $request->points,
            'milestone_id' => $request->milestone,
            'assigned_to'  => $request->assigned_to,
            'start_date'   => $request->start_date,
            'lead_id'      => $request->labels,
            'status'       => $request->status,
            // 'start_time'  => $request->start_time,
            // 'end_date'    => $request->end_date,
            // 'end_time'    => $request->end_time,
            // 'priority'    => $request->priority
        ]);
        // if ($request->has('collaborators')) {
        //     $task->collaborators()->sync($request->collaborators);
        // }

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('task-attachments');
                $task->attachments()->create([
                    'path' => $path,
                    'filename' => $file->getClientOriginalName()
                ]);
            }
        }

        return redirect()->back()->with('success', 'Task created successfully');
    }

    public function edit($id)
    {
        $task = Task::findOrFail($id);
        // $task->collaborators = $task->collaborators->pluck('id')->toArray();
        return response()->json($task);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title'       => 'required|string',
            'project_id'  => 'required|exists:projects,id',
            'assigned_to' => 'required|exists:users,id',
            'status'      => 'required|string',
        ]);

        $task = Task::findOrFail($id);
        $task->update($request->all());
        return redirect()->back()->with('success', 'Task updated successfully');
    }

    public function destroy($id)
    {
        Task::destroy($id);
        return redirect()->back()->with('success', 'Task deleted successfully');
    }

    public function create()
    {
        return view('crm.proposals.form');
    }
}
