<?php

namespace Isotope\CRM\Http\Controllers;

use Isotope\CRM\Models\Client;
use Isotope\CRM\Models\Project;
use App\Http\Controllers\Controller;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::with('client:id,company_name')->get();
        return view('crm::projects.index', compact('projects'));
    }

    public function create()
    {
        $clients = Client::all();
        return view('crm::projects.create-model', compact('clients'));
    }
}
