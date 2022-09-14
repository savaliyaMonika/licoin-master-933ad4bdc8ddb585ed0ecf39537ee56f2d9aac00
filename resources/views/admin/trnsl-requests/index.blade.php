@extends('admin.layouts.app')

@section('title', 'View Translation Requests')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox float-e-margins">
        <div class="ibox-title pb-3">
          <h5>Translation Requests</h5>
          <div class="ibox-tools align-items-center d-flex">
            <a class="btn btn-md btn-primary mr-3 mb-0" href="{{url('/langtranslate')}}" target="_blank">
              Test Translation
            </a>
            <a class="refresh-link mr-2" id="refreshDataTable" title="Refresh">
              <i class="fa fa-refresh fa-2x"></i>
            </a>
          </div>
        </div>
        <div class="ibox-content">
          <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover w-100" id="trnslRequestsTable">
              <thead>
                <tr>
                  <th>Id</th>
                  <th>Email</th>
                  <th>From</th>
                  <th>To</th>
                  <th>Status</th>
                  <th>Time To Process (HH:MM:SS)</th>
                  <th>Updated At</th>
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
        dtable = $('#trnslRequestsTable').DataTable({
            processing: true,
            serverSide: true,
            stateSave: true,
            ajax: {
                url: 'trnsl-requests/datatable',
            },
            order: [[ 0, "desc" ]],
            columns: [
                {
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: null,
                    name: 'from_lng',
                    searchable: false,
                    // className: 'text-center',
                    render: function (o) {
                        return `
                          <div class="d-flex aling-items-center">
                            <span class="text-uppercase mr-3">${o.from_lng}</span>
                            <a href="${o.file_url}${o.from_file}" class="mb-0 btn btn-success p-w-sm btn-md" target="_blank">
                              <i class="fa fa-download"></i>
                            </a>
                          </div>`
                    }
                },
                {
                    data: null,
                    name: 'to_lng',
                    searchable: false,
                    // className: 'text-center',
                    render: function (o) {
                        // console.log(o);
                        return `
                          <div class="d-flex aling-items-center">
                            <span class="text-uppercase mr-3">${o.to_lng}</span>
                            <a href="${o.file_url}${o.to_file}" class="mb-0 btn btn-success p-w-sm btn-md" target="_blank">
                              <i class="fa fa-download"></i>
                            </a>
                          </div>
                        `
                    }
                },
                {
                    data: 'status',
                    searchable: false,
                    // className: 'text-center',
                    render: function (status) {
                      // console.log(status)
                        if (status === 'requested') {
                          return `<span class="lc-font-12 text-capitalize text-lg label">${status}</span>`
                        } else if (status === 'inprocess') {
                          return `<span class="lc-font-12 text-capitalize text-lg label-success label">${status}</span>`
                        } else if (status === 'processed') {
                          return `<span class="lc-font-12 text-capitalize text-lg label-info label">${status}</span>`
                        } else if (status === 'failed') {
                          return `<span class="lc-font-12 text-capitalize text-lg label-danger label">${status}</span>`
                        } else if (status === 'mailfailed') {
                          return `<span class="lc-font-12 text-capitalize text-lg label-warning label">Mail Failed</span>`
                        }
                        return "Not Valid Status";
                    }
                },
                {
                    data: 'time_to_process',
                    searchable: false,
                    render: function (timeToProcess) {
                        return timeToProcess ? timeToProcess : '';
                    }
                },
                {
                    data: 'updated_at',
                    name: 'updated_at',
                },
                {
                    data: null,
                    searchable: false,
                    sortable: false,
                    className: 'text-center',
                    render: function (o) {
                        // if(o.status !== "processed") {
                            return `
                            <a href='javascript:void(0);' onclick=enqueueRequest(${o.id}) class='btn btn-primary p-w-sm btn-md' title="Enqueue">
                              <i class="fa fa-tasks"></i>
                            </a>
                            <a href='javascript:void(0);' onclick=sendMail(${o.id}) class='btn btn-primary p-w-sm btn-md' title="Send Mail">
                              <i class="fa fa-paper-plane"></i>
                            </a>
                            <a href='javascript:void(0);' onclick=deleteRequest(${o.id}) class='btn btn-danger p-w-sm btn-md' title="Delete">
                              <i class="fa fa-trash"></i>
                            </a>`;
                        // }
                        // return ` <a href='javascript:void(0);' onclick=deleteRequest(${o.id}) class='btn btn-danger p-w-sm btn-md' title="Delete">
                        //       <i class="fa fa-trash"></i>
                        //     </a>`;

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
    function enqueueRequest(id) {
        $.ajax({
            url: '{{ url("admin/trnsl-requests/") }}/'+id,
            type: "get",
            processData: false,
            cache: false,
            contentType: false,
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
        });
    }
    function sendMail(id) {
        $.ajax({
            url: '{{ url("admin/trnsl-requests/send-mail") }}/'+id,
            type: "get",
            processData: false,
            cache: false,
            contentType: false,
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
        });
    }
    function deleteRequest(id) {
        $.ajax({
            url: '{{ url("admin/trnsl-requests/") }}/'+id,
            type: "delete",
            processData: false,
            cache: false,
            contentType: false,
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
        });
    }
</script>
@endpush