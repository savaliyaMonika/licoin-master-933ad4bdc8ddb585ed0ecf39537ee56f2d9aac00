@extends('admin.layouts.app')

@section('title', 'View SSL Checker Table ')
@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title pb-3">
                    <h5>SSL Checker Subscriber</h5>
                    <div class="ibox-tools align-items-center d-flex">
                        <a class="btn btn-md btn-primary mr-3 mb-0" href="{{url('/sslchecker')}}" target="_blank">
                          SSL Checker
                        </a>
                        <a class="refresh-link mr-2" id="refreshDataTable" title="Refresh">
                            <i class="fa fa-refresh fa-2x"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover w-100" id="sslCheckerSubscriberTable">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">Id</th>
                                    <th style="width: 5%;">Website</th>
                                    <th style="width: 5%;">Expiration Date</th>
                                    <th style="width: 5%;">Server Ip Address</th>
                                    <th style="width: 5%;">Email (Subscriber)</th>
                                    <th style="width: 5%;">Created At</th>
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
        dtable = $('#sslCheckerSubscriberTable').DataTable({
            processing: true,
            serverSide: true,
            stateSave: true,
            ajax: {
                url: 'ssl-checker/datatable',
            },
            columns: [
                {
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'web_site',
                    name: 'web_site'
                },
                {
                    data: 'expiration_date',
                    name: 'expiration_date'
                },
                {
                    data: 'server_ip_address',
                    name: 'server_ip_address',
                },
                {
                    data: null,
                    name: 'subscriber.email',
                    searchable: false,
                    sortable: false,
                    render: function (object) {
                      // console.log(object);
                      var email  = object.subscriber.map((sub) => {
                          return sub.email
                      }).join(',') || '';
                      // console.log(email);
                      return email;
                    }
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