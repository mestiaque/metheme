@extends('isotope::master')

@section('title', 'Proposals | Isotope IT LTD')

@section('content')
<div class="page-wrapper clearfix grid-button">
    <div class="card clearfix">
        <ul id="proposal-tabs" data-bs-toggle="ajax-tab" class="nav nav-tabs bg-white title" role="tablist">
            <li class="title-tab"><h4 class="pl15 pt10 pr15">Proposals</h4></li>
            <li>
                <a id="monthly-proposal-button"
                   role="presentation"
                   data-bs-toggle="tab"
                   href="#monthly-proposals">Monthly</a>
            </li>
            <li>
                <a role="presentation"
                   data-bs-toggle="tab"
                   href="{{ url('proposals/yearly') }}"
                   data-bs-target="#yearly-proposals">Yearly</a>
            </li>
            <div class="tab-title clearfix no-border proposal-page-title">
                <div class="title-button-group">
                    <a href="#"
                       class="btn btn-default"
                       title="Add proposal"
                       data-act="ajax-modal"
                       data-title="Add proposal"
                       data-action-url="{{ route('crm.proposals.create') }}">
                        <i data-feather='plus-circle' class='icon-16'></i> Add proposal
                    </a>
                </div>
            </div>
        </ul>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade" id="monthly-proposals">
                <div class="table-responsive">
                    <table id="monthly-proposal-table" class="display" cellspacing="0" width="100%">
                    </table>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="yearly-proposals"></div>
        </div>
    </div>
</div>

{{-- AJAX Modal --}}
<div class="modal fade" id="ajaxModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="ajaxModalTitle"></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div id="ajaxModalContent">
                <div class="p-3 text-center">
                    <div class="spinner-border"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@section('js')
    {{-- jQuery, Bootstrap 5, Feather Icons --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/feather-icons"></script>

    {{-- appTable plugin (ensure this exists in your project) --}}
    <script src="{{ asset('assets/js/appTable.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            // Feather icons init
            feather.replace();

            // Load proposals table
            loadProposalsTable("#monthly-proposal-table", "monthly");

            // Handle AJAX modal open
            $(document).on("click", "[data-act='ajax-modal']", function (e) {
                e.preventDefault();

                let url = $(this).data("action-url");
                let title = $(this).data("title");

                $("#ajaxModalTitle").text(title);
                $("#ajaxModalContent").html("<div class='p-3 text-center'><div class='spinner-border'></div></div>");
                $("#ajaxModal").modal("show");

                $.get(url, function (response) {
                    $("#ajaxModalContent").html(response);
                }).fail(function () {
                    $("#ajaxModalContent").html("<div class='p-3 text-danger'>Failed to load content.</div>");
                });
            });
        });

        function loadProposalsTable(selector, dateRange) {
            $(selector).appTable({
                source: '{{ url("proposals/list_data") }}',
                order: [[0, "desc"]],
                dateRangeType: dateRange,
                filterDropdown: [
                    {
                        name: "status",
                        class: "w150",
                        options: [
                            {"id":"","text":"- Status -"},
                            {"id":"draft","text":"Draft"},
                            {"id":"sent","text":"Sent"},
                            {"id":"accepted","text":"Accepted"},
                            {"id":"declined","text":"Declined"}
                        ]
                    }
                ],
                columns: [
                    {title: "Proposal", "class": "w15p all"},
                    {title: "Client", "class": "w15p"},
                    {visible: false, searchable: false},
                    {title: "Proposal date", "iDataSort": 2, "class": "w15p"},
                    {visible: false, searchable: false},
                    {title: "Valid until", "iDataSort": 4, "class": "w15p"},
                    {title: "Amount", "class": "text-right w15p"},
                    {title: "Status", "class": "text-center"},
                    {title: "<i data-feather='menu' class='icon-16'></i>", "class": "text-center option w150"}
                ],
                printColumns: combineCustomFieldsColumns([0, 1, 3, 5, 6, 7], ''),
                xlsColumns: combineCustomFieldsColumns([0, 1, 3, 5, 6, 7], ''),
                summation: [{column: 6, dataType: 'currency', currencySymbol: "TK"}]
            });
        }
    </script>
@endsection
