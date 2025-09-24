<div class="modal fade" id="taskModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-850px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bold" id="modalTitle">Add Task</h2>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1"></i>
                </div>
            </div>

            <form id="taskForm" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="_method" id="method" value="POST">
                
                <div class="modal-body">
                    <div class="row mb-3">
                        <label class="col-md-3 col-form-label required">Title</label>
                        <div class="col-md-9">
                            <input type="text" name="title" id="title" class="form-control" placeholder="Enter title" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-md-3 col-form-label">Description</label>
                        <div class="col-md-9">
                            <textarea name="description" id="description" class="form-control" rows="3"></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-md-3 col-form-label required">Related to</label>
                        <div class="col-md-9">
                            <select name="project_id" class="form-select" required>
                                <option value="">Select Project</option>
                                @foreach($projects as $project)
                                    <option value="{{ $project->id }}">{{ $project->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-md-3 col-form-label">Points</label>
                        <div class="col-md-9">
                            <select name="points" class="form-select">
                                <option value="">Select Points</option>
                                <option value="1">1 Point</option>
                                <option value="2">2 Points</option>
                                <option value="3">3 Points</option>
                                <option value="5">5 Points</option>
                                <option value="8">8 Points</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-md-3 col-form-label">Milestone</label>
                        <div class="col-md-9">
                            <select name="milestone" class="form-select">
                                <option value="">Select Milestone</option>
                                <option value="planning">Planning</option>
                                <option value="development">Development</option>
                                <option value="testing">Testing</option>
                                <option value="deployment">Deployment</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-md-3 col-form-label required">Assign to</label>
                        <div class="col-md-9">
                            <select name="assigned_to" class="form-select" required>
                                <option value="">Select User</option>
                                @foreach($users ?? [] as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- <div class="row mb-3">
                        <label class="col-md-3 col-form-label">Collaborators</label>
                        <div class="col-md-9">
                            <select name="collaborators[]" class="form-select" multiple>
                                @foreach($users ?? [] as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div> --}}

                    <div class="row mb-3">
                        <label class="col-md-3 col-form-label required">Start date</label>
                        <div class="col-md-4">
                            <input type="date" name="start_date" class="form-control" required>
                        </div>
                        <label class="col-md-1 col-form-label">Time</label>
                        <div class="col-md-4">
                            <input type="time" name="start_time" class="form-control">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-md-3 col-form-label">End date</label>
                        <div class="col-md-4">
                            <input type="date" name="end_date" class="form-control">
                        </div>
                        <label class="col-md-1 col-form-label">Time</label>
                        <div class="col-md-4">
                            <input type="time" name="end_time" class="form-control">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-md-3 col-form-label">Labels</label>
                        <div class="col-md-9">
                            <input type="text" name="labels" class="form-control" placeholder="Enter labels">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-md-3 col-form-label required">Status</label>
                        <div class="col-md-9">
                            <select name="status" class="form-select" required>
                                <option value="todo">To Do</option>
                                <option value="in_progress">In Progress</option>
                                <option value="review">Review</option>
                                <option value="done">Done</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-md-3 col-form-label">Priority</label>
                        <div class="col-md-9">
                            <select name="priority" class="form-select">
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                                <option value="urgent">Urgent</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-md-3 col-form-label">Attachments</label>
                        <div class="col-md-9">
                            <input type="file" name="attachments[]" class="form-control" multiple>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <span class="indicator-label">Save</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
