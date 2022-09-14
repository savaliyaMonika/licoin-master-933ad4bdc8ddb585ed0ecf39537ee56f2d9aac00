@extends('admin.layouts.app')

@section('title', 'View Users Table ')
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
                    <h5>Users Table</h5>
                    <div class="ibox-tools align-items-center d-flex">
                        <a class="refresh-link mr-2" id="refreshDataTable" title="Refresh">
                            <i class="fa fa-refresh fa-2x"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover w-100" id="usersTable">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">Id</th>
                                    <th style="width: 5%;">name</th>
                                    <th style="width: 50%;">email</th>
                                    <th style="width: 5%;">isVerified</th>
                                    <th style="width: 5%;">otp</th>
                                    <th style="width: 5%;">created_at</th>
                                    <th style="width: 10%;">updated_at</th>
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
        dtable = $('#usersTable').DataTable({
            processing: true,
            serverSide: true,
            stateSave: true,
            ajax: {
                url: 'users/datatable',
            },
            columns: [
                {
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'name',
                    name: 'name',
                },
                {
                    data: 'email',
                    name: 'email',
                },
                {
                    data: 'isVerified',
                    name: 'isVerified'
                },
                {
                    data: 'otp',
                    name: 'otp'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: 'updated_at',
                    name: 'updated_at',
                },
            ]
        });
    });

    $("#refreshDataTable").click(function(e) {
        dtable.draw(false);
    });

</script>
@endpush