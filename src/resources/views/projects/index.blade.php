@extends('isotope::master')

@section('title', __('Projects'))

@push('buttons')
    <a href="#" data-act="ajax-model" data-action-url="{{ route('crm.projects.create') }}" class="btn btn-sm btn-isotope fw-bold">Create</a>
@endpush

@section('content')
<div class="card">
    <div class="card-body">
    </div>
</div>

<div class="modal fade" id="ajax-model" tabindex="-1" role="dialog" aria-labelledby="createProjectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content"></div>
    </div>
</div>

@push('js')
    <script>
        $(document).ready(() => {
            $('a[data-act="ajax-model"]').click(function (e) {
                e.preventDefault();
                var url = $(this).data('action-url');
                $('#ajax-model .modal-content').load(url, function () {
                    $('#ajax-model').modal('toggle');
                });
            });
        });
    </script>
@endpush

@endsection