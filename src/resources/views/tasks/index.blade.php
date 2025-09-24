@extends('isotope::master')

@section('content')
<div class="card">
    <div class="card-header border-0 pt-6">
        <div class="card-toolbar">
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-primary" id="addTaskBtn">
                    <i class="fas fa-plus"></i> Add Task
                </button>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="d-flex flex-wrap justify-content-between mb-6">
            <!-- Filter buttons -->
            <div class="d-flex align-items-center position-relative my-1">
                <button class="btn btn-light-primary btn-sm me-3">
                    <i class="fas fa-filter"></i> Manage Labels
                </button>
                <button class="btn btn-light-primary btn-sm me-3">
                    <i class="fas fa-file-import"></i> Import Tasks
                </button>
                <button class="btn btn-light-primary btn-sm">
                    <i class="fas fa-tasks"></i> Add Multiple Tasks
                </button>
            </div>
            <!-- View options -->
            <div class="d-flex align-items-center">
                <a href="#" class="btn btn-light-primary btn-sm me-3 active">List</a>
                <a href="#" class="btn btn-light-primary btn-sm me-3">Kanban</a>
                <a href="#" class="btn btn-light-primary btn-sm">Gantt</a>
            </div>
        </div>

        <table class="table align-middle table-row-dashed fs-6 gy-5">
            <thead>
                <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                    <th>ID</th>
                    <th>Title</th>
                    <th>Start Date</th>
                    <th>Deadline</th>
                    <th>Milestone</th>
                    <th>Related to</th>
                    <th>Assigned to</th>
                    <th>Collaborators</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tasks as $task)
                <tr>
                    <td>{{ $task->id }}</td>
                    <td>{{ $task->title }}</td>
                    <td>{{ $task->start_date }}</td>
                    <td>{{ $task->deadline }}</td>
                    <td>{{ $task->milestone }}</td>
                    <td>{{ $task->project->title ?? 'N/A' }}</td>
                    <td>
                        @if($task->assignedTo)
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-35px me-2">
                                    <img src="{{ $task->assignedTo->avatar_url }}" alt="">
                                </div>
                                <span>{{ $task->assignedTo->name }}</span>
                            </div>
                        @endif
                    </td>
                    {{-- <td>
                        <div class="symbol-group">
                            @foreach($task->collaborators as $collaborator)
                                <div class="symbol symbol-35px">
                                    <img src="{{ $collaborator->avatar_url }}" alt="">
                                </div>
                            @endforeach
                        </div>
                    </td> --}}
                    <td>{{ ucfirst($task->status) }}</td>
                    <td class="text-end">
                        <button class="btn btn-icon btn-light-primary btn-sm me-2 editTaskBtn" data-id="{{ $task->id }}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-icon btn-light-danger btn-sm deleteTaskBtn" data-id="{{ $task->id }}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Task Modal -->
@include('crm::tasks.partials.task-modal')
@endsection

@push('js')
<script>
$(document).ready(function() {
    // Add Task
    $('#addTaskBtn').click(function() {
        resetForm();
        $('#taskModal').modal('show');
    });

    // Edit Task
    $('.editTaskBtn').click(function() {
        resetForm();
        const id = $(this).data('id');
        $.get(`{{ url('crm/tasks') }}/${id}/edit`, function(data) {
            $('#taskForm').attr('action', `{{ url('crm/tasks') }}/${id}`);
            $('#method').val('PUT');
            $('#modalTitle').text('Edit Task');
            $('#submitBtn span').text('Update');
            
            // Fill form fields
            $('#title').val(data.title);
            $('#description').val(data.description);
            $('#project_id').val(data.project_id);
            $('#points').val(data.points);
            $('#milestone').val(data.milestone_id);
            $('#assigned_to').val(data.assigned_to);
            $('#start_date').val(data.start_date);
            $('#start_time').val(data.start_time);
            $('#end_date').val(data.end_date);
            $('#end_time').val(data.end_time);
            $('#labels').val(data.labels);
            $('#status').val(data.status);
            $('#priority').val(data.priority);
            
            if(data.collaborators) {
                $('#collaborators').val(data.collaborators);
            }
            
            $('#taskModal').modal('show');
        });
    });

    // Reset Form
    function resetForm() {
        $('#taskForm')[0].reset();
        $('#taskForm').attr('action', '{{ route("crm.tasks.store") }}');
        $('#method').val('POST');
        $('#modalTitle').text('Add Task');
        $('#submitBtn span').text('Save');
    }

    // Delete Task
    $('.deleteTaskBtn').click(function() {
        const id = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `{{ url('crm/tasks') }}/${id}/delete`;
            }
        });
    });
});
</script>
@endpush
