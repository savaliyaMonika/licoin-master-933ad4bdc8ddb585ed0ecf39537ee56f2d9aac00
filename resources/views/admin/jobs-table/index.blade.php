@extends('admin.layouts.app')

@section('title', 'View Jobs Table ')
@push('style')
    <style>
        .word-break {
            word-break: break-all;
        }
    </style>
@endpush
@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title pb-3">
                    <h5>Jobs Table</h5>
                    <div class="ibox-tools align-items-center d-flex">
                        <a class="refresh-link mr-2" id="refreshDataTable" title="Refresh">
                            <i class="fa fa-refresh fa-2x"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover w-100" id="jobsTable">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">Id</th>
                                    <th style="width: 5%;">queue</th>
                                    <th style="width: 50%;">payload</th>
                                    <th style="width: 5%;">attempts</th>
                                    <th style="width: 5%;">reserved_at</th>
                                    <th style="width: 5%;">available_at</th>
                                    <th style="width: 10%;">created_at</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    var dtable;
    $(document).ready(function() {
        dtable = $('#jobsTable').DataTable({
            processing: true,
            serverSide: true,
            stateSave: true,
            ajax: {
                url: 'jobs-table/datatable',
            },
            columns: [
                {
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'queue',
                    name: 'queue',
                },
                {
                    data: 'payload',
                    name: 'payload',
                    className: "word-break"
                },
                {
                    data: 'attempts',
                    name: 'attempts'
                },
                {
                    data: 'reserved_at',
                    name: 'reserved_at'
                },
                {
                    data: 'available_at',
                    name: 'available_at'
                },
                {
                    data: 'created_at',
                    name: 'created_at',
                },
            ]
        });
    });

    $("#refreshDataTable").click(function(e) {
        dtable.draw(false);
    });

</script>
@endpush