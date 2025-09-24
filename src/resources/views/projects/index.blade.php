@extends('isotope::master')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-end mb-3">
            <button class="btn btn-primary" id="addProjectBtn">
                <i class="fas fa-plus"></i> Add Project
            </button>
        </div>

        <div class="table-responsive">
            <table class="table align-middle table-row-dashed fs-6 gy-5">
                <thead>
                    <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                        <th>ID</th>
                        <th>Title</th>
                        <th>Client</th>
                        <th>Price</th>
                        <th>Start date</th>
                        <th>Deadline</th>
                        <th>Progress</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($projects as $project)
                    <tr>
                        <td>{{ $project->id }}</td>
                        <td>{{ $project->title }}</td>
                        <td>{{ $project->clinte_id ?? 'N/A' }}</td>
                        <td>{{ $project->price }} TK</td>
                        <td>{{ $project->start_date }}</td>
                        <td class="text-danger">{{ $project->deadline}}</td>
                        <td>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" 
                                    style="width: {{ $project->progress ?? 0 }}%" 
                                    aria-valuenow="{{ $project->progress ?? 0 }}" 
                                    aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </td>
                        <td>{{ ucfirst($project->status ?? 'pending') }}</td>
                        <td class="text-end">
                            <button data-id="{{ $project->id }}" class="btn btn-light btn-active-light-primary btn-sm editProjectBtn">
                                <i class="fas fa-edit"></i>
                            </button>
                            <a href="{{ route('crm.projects.delete', $project->id) }}" class="btn btn-light btn-active-light-danger btn-sm delete-record">
                                <i class="fas fa-times"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>


{{-- Modal --}}
<div class="modal fade" id="ajax-model" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header py-3">
                <h4 class="modal-title" id="modalTitle">Add Project</h4>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ki-duotone ki-cross fs-1"></i>
                </div>
            </div>

            <form id="projectForm" method="POST">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">

                <div class="modal-body py-4">
                    <div class="fv-row mb-8">
                        <label class="required fw-semibold fs-6 mb-2">Title</label>
                        <input type="text" name="title" id="title"
                            class="form-control form-control-solid"
                            placeholder="Enter Title" required>
                    </div>

                    <div class="fv-row mb-8">
                        <label class="required fw-semibold fs-6 mb-2">Project type</label>
                        <select name="project_type" id="project_type" class="form-select form-select-solid" required>
                            <option value="">Select Type</option>
                            <option value="client_project">Client Project</option>
                            <option value="internal">Internal</option>
                        </select>
                    </div>

                    <div class="fv-row mb-8">
                        <label class="required fw-semibold fs-6 mb-2">Client</label>
                        <select name="client_id" id="client_id" class="form-select form-select-solid" required>
                            <option value="">Select Client</option>
                            <option value="1"> Client1</option>
                            <option value="2"> Client2</option>
                            {{-- @foreach($clients as $client)
                                <option value="{{ $client->id }}">{{ $client->company_name }}</option>
                            @endforeach --}}
                        </select>
                    </div>

                    <div class="fv-row mb-8">
                        <label class="fw-semibold fs-6 mb-2">Description</label>
                        <textarea name="description" id="description"
                            class="form-control form-control-solid" rows="4" placeholder="Enter Description"></textarea>
                    </div>

                    <div class="row g-9 mb-8">
                        <div class="col-md-6 fv-row">
                            <label class="required fw-semibold fs-6 mb-2">Start date</label>
                            <input type="date" name="start_date" id="start_date"
                                class="form-control form-control-solid" required>
                        </div>
                        <div class="col-md-6 fv-row">
                            <label class="fw-semibold fs-6 mb-2">Deadline</label>
                            <input type="date" name="deadline" id="deadline"
                                class="form-control form-control-solid">
                        </div>
                    </div>

                    <div class="fv-row mb-8">
                        <label class="fw-semibold fs-6 mb-2">Price</label>
                        <input type="number" name="price" id="price"
                            class="form-control form-control-solid"
                            placeholder="Enter Price" step="0.01">
                    </div>

                    <div class="fv-row">
                        <label class="fw-semibold fs-6 mb-2">Labels</label>
                        <input type="text" name="labels" id="labels"
                            class="form-control form-control-solid"
                            placeholder="Enter Labels">
                    </div>
                </div>

                <div class="modal-footer py-3">
                    <button type="button" class="btn btn-light-primary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="saveBtn">
                        <span class="indicator-label">Save</span>
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection

@push('js')
<script>
$(document).on('click', '.delete-record', function(e){
    e.preventDefault();
    var url = $(this).attr('href'); // delete route url

    Swal.fire({
        title: 'Are you sure?',
        text: "This project will be permanently deleted!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // redirect delete route এ
            window.location.href = url;

            // Optional: success message
            // Swal.fire('Deleted!','Your project has been deleted.','success');
        }
    })
});
</script>
<script>
$(document).ready(function(){

    // ADD
    $("#addProjectBtn").click(function(){
        $("#projectForm").attr("action", "{{ route('crm.projects.store') }}");
        $("#formMethod").val("POST");
        $("#projectForm")[0].reset();
        $("#modalTitle").text("Add Project");
        $("#saveBtn span").text("Save");
        $("#ajax-model").modal("show");
    });

    // EDIT
    $(".editProjectBtn").click(function(){
        var id = $(this).data("id");
        $.get("{{ url('crm/projects') }}/" + id + "/edit", function(data){
            $("#projectForm").attr("action", "{{ url('crm/projects') }}/" + id);
            $("#formMethod").val("PUT");
            $("#modalTitle").text("Edit Project");
            $("#saveBtn span").text("Update");

            $("#title").val(data.title);
            $("#project_type").val(data.project_type);
            $("#client_id").val(data.client_id);
            $("#description").val(data.description);
            $("#start_date").val(data.start_date);
            $("#deadline").val(data.deadline);
            $("#price").val(data.price);
            $("#labels").val(data.labels);

            $("#ajax-model").modal("show");
        });
    });

});
</script>
@endpush
