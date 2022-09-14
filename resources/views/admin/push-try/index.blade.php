@extends('admin.layouts.app')

@section('title', 'View Push Try Requests')
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
                    <h5>PushTry Requests</h5>
                    <div class="ibox-tools align-items-center d-flex">
                        <a class="btn btn-md btn-primary mr-3 mb-0" href="{{url('/pushtry')}}" target="_blank">
                          Test PushTry
                        </a>
                        <a class="refresh-link mr-2" id="refreshDataTable" title="Refresh">
                            <i class="fa fa-refresh fa-2x"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover w-100" id="pushTryRequestsTable">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">Id</th>
                                    <th style="width: 50%;">Device Token</th>
                                    <th style="width: 20%;">Message</th>
                                    <th style="width: 5%;">Key Id</th>
                                    <th style="width: 5%;">Team Id</th>
                                    <th style="width: 5%;">App Id</th>
                                    <th class="text-capitalize" style="width: 5%;">Is Prod.?</th>
                                    <th style="width: 5%;">Cert. File</th>
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
        dtable = $('#pushTryRequestsTable').DataTable({
            processing: true,
            serverSide: true,
            stateSave: true,
            ajax: {
                url: 'pushtry-requests/datatable',
            },
            columns: [
                {
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'devicetokens',
                    name: 'devicetokens',
                    className: "word-break"
                },
                {
                    data: 'message',
                    name: 'message',
                    className: "word-break"
                },
                {
                    data: 'keyid',
                    name: 'keyid'
                },
                {
                    data: 'teamid',
                    name: 'teamid'
                },
                {
                    data: 'appid',
                    name: 'appid'
                },
                {
                    data: 'isproduction',
                    name: 'isproduction',
                    className: 'text-capitalize'
                },
                {
                    data: null,
                    name: 'file_cert',
                    searchable: false,
                    sortable: false,
                    render: function (o) {
                        return `
                          <div class="d-flex aling-items-center">
                            <a href="${o.file_url}${o.file_cert}" class="mb-0 btn btn-success p-w-sm btn-md" download title=${o.file_cert}>
                              <i class="fa fa-download"></i>
                            </a>
                          </div>`
                    }
                }
            ]
        });
    });

    $("#refreshDataTable").click(function(e) {
        dtable.draw(false);
    });
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
</script>
@endpush