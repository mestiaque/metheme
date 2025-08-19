<div class="modal-header">
    <h5 class="modal-title">Create New Project</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <form x-data="{ project_type: '' }">
        <div class="mb-3 row">
            <label for="title" class="col-md-3 col-form-label">Title</label>
            <div class="col-md-9">
                <input type="text" id="title" name="title" class="form-control form-control-sm" placeholder="Title">
            </div>
        </div>
        <div class="mb-3 row">
            <label for="project_type" class="col-md-3 col-form-label">Project Type</label>
            <div class="col-md-9">
                <select x-model="project_type" name="project_type" id="project-type" class="form-select form-select-sm">
                    <option value="internal_project">Internal Project</option>
                    <option value="client_project">Client Project</option>
                </select>
            </div>
        </div>
        <div class="mb-3 row" x-show="project_type === 'client_project'">
            <label for="client_id" class="col-md-3 col-form-label">Client</label>
            <div class="col-md-9">
                <select name="client_id" id="client_id" class="form-select form-select-sm">
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}">{{ $client->company_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="mb-3 row">
            <label for="description" class="col-md-3 col-form-label">Description</label>
            <div class="col-md-9">
                <textarea id="description" name="description" class="form-control form-control-sm" placeholder="Description"
                    style="height:150px;" data-rich-text-editor="true"></textarea>
            </div>
        </div>
        <div class="mb-3 row">
            <label for="start_date" class="col-md-3 col-form-label">Start Date</label>
            <div class="col-md-9">
                <input type="text" id="start_date" name="start_date" class="form-control form-control-sm" placeholder="Start Date">
            </div>
        </div>
        <div class="mb-3 row">
            <label for="deadline" class="col-md-3 col-form-label">Deadline</label>
            <div class="col-md-9">
                <input type="text" id="deadline" name="deadline" class="form-control form-control-sm" placeholder="Deadline">
            </div>
        </div>
        <div class="mb-3 row">
            <label for="price" class="col-md-3 col-form-label">Price</label>
            <div class="col-md-9">
                <input type="text" id="price" name="price" class="form-control form-control-sm" placeholder="Price">
            </div>
        </div>
        <div class="mb-3 row">
            <label for="project_labels" class="col-md-3 col-form-label">Labels</label>
            <div class="col-md-9">
                <input type="text" id="project_labels" name="labels" class="form-control form-control-sm" placeholder="Labels">
            </div>
        </div>
    </form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
    <button type="button" class="btn btn-primary">Save</button>
</div>

<script>
    $(document).ready(function() {
        $('#client_id').select2({
            placeholder: "Select a client",
            dropdownParent: $('#ajax-model'),
        }).val(null).trigger('change');
    });
</script>