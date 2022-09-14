@extends('admin.layouts.app')

@section('title', 'View Translation Keys')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title pb-3">
                    <h5>Translation Keys</h5>
                    <div class="ibox-tools align-items-center d-flex">
                      <a class="btn btn-md btn-primary mr-2" onclick="resetCounts()"
                            href="javascript:void(0)">Reset Counts</a>
                        <a href="{{ url('admin/trnsl-keys/create')}}" class="mr-2" title="Add Key">
                            <i class="fa fa-plus fa-2x"></i>
                        </a>
                        <a class="refresh-link mr-2" id="refreshDataTable" title="Refresh">
                            <i class="fa fa-refresh fa-2x"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover w-100" id="trnslKeyTable">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Name</th>
                                    <th>Key</th>
                                    <th>Count (Used times)</th>
                                    <th>Key Type</th>
                                    <th>Status</th>
                                    <th>UpdatedAt (Last used)</th>
                                    <th>Action</th>
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
        dtable = $('#trnslKeyTable').DataTable({
            processing: true,
            serverSide: true,
            stateSave: true,
            ajax: {
                url: 'trnsl-keys/datatable',
            },
            order: [[ 0, "desc" ]],
            columns: [
                {
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'key',
                    name: 'key'
                },
                {
                    data: 'count',
                    name: 'count'
                },
                {
                    data: 'api_key_type',
                    name: 'api_key_type'
                },
                {
                    "data": null,
                    "orderable": false,
                    "searchable": false,
                    "width": '10%',
                    render:function(o){
                        var str="";
                        var status ="";
                        status = o.status == 1 ? 'checked' : '';
                        str += '<div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input trans_status" data-id="'+o.id+'" id="'+o.id+'" '+status+'><label class="custom-control-label" for="'+o.id+'"></label></div>';
                        return str;
                    }
                },
                {
                    data: 'updated_at',
                    name: 'updated_at'
                },
                {
                    "data": null,
                    "name": "isFormFilled",
                    "searchable": false,
                    "className": 'text-center',
                    "render": function (o) {
                        return "<a href='trnsl-keys/" + o.id + "/edit' class='btn btn-primary p-w-sm btn-md'>Edit</a>" +
                        "<span>&nbsp;&nbsp;</span>" +
                        "<a href='javascript:void(0);" + o.id + "' class='btn btn-danger p-w-sm btn-md'" +
                        "onclick='deleteRecord(" + o.id + ")'><i class='fa fa-trash fa-controls'></i></a>";
                    }
                },
            ]
        });


        $(document).on('click', '.trans_status', function() {
            var el = $(this);
            $.ajax({
                url: 'trnsl-keys/status',
                type:"post",
                data: {
                    id : el.data('id'),
                    checked : $(el).is(":checked")
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(success) {
                    showToast('success', success.message);
                    dtable.draw(false);
                },
                error: function(error) {
                    showToast('error', error.responseJSON.message);
                    dtable.draw(false);
                },
            })
        } );

    });

    $("#refreshDataTable").click(function(e) {
        dtable.draw(false);
    });




    function resetCounts() {
        swal({
            title: "Are you sure?",
            customClass: 'custom-swal',
            text: "Do you really want to reset All Counts",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, Reset it!",
            closeOnConfirm: false
        }, function() {
            $.ajax({
                url: "trnsl-keys/reset-counters",
                type: "PUT",
                processData: false,
                cache: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(success) {
                    showToast('success', success.message);
                    dtable.draw(false);
                    swal.close();
                },
                error: function(error) {
                    showToast('error', error.responseJSON.message);
                    dtable.draw(false);
                    swal.close();
                },
            });
        });
    }

    function showToast(type, text) {
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "progressBar": true,
            "preventDuplicates": false,
            "positionClass": "toast-top-right",
            "onclick": null,
            "showDuration": "400",
            "hideDuration": "1000",
            "timeOut": "7000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }
        toastr[type](text)
    }

    function deleteRecord(id) {
        swal({
            title: "Are you sure?",
            customClass: 'custom-swal',
            text: "Do you really want to delete this Key",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            closeOnConfirm: false
        }, function() {
            $.ajax({
                url: "trnsl-keys/" + id,
                type: "DELETE",
                processData: false,
                cache: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(success) {
                    showToast('success', success.message);
                    dtable.draw(false);
                    swal.close();
                },
                error: function(error) {
                    showToast('error', error.responseJSON.message);
                    dtable.draw(false);
                    swal.close();
                },
            });
        });
    }

</script>
@endpush
