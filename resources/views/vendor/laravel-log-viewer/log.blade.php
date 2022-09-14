@extends('admin.layouts.app')
@section('title', 'Logs')
@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox float-e-margins">
        <div class="ibox-title pb-3">
          <h5>Logs</h5>
          <div class="ibox-tools align-items-center d-flex">
            @if($current_file)
              <a class="" href="{{url('admin/logs/'. $current_file)}}" target="_blank">
                <span class="fa fa-file-text-o"></span> View Raw File
              </a>
                |
              <a href="?dl={{ \Illuminate\Support\Facades\Crypt::encrypt($current_folder ? $current_folder . "/" . $current_file : $current_file) }}{{ ($current_folder) ? '&f=' . \Illuminate\Support\Facades\Crypt::encrypt($current_folder) : '' }}">
                <span class="fa fa-download"></span> Download file
              </a>
                |
              <a id="clean-log"
                href="?clean={{ \Illuminate\Support\Facades\Crypt::encrypt($current_folder ? $current_folder . "/" . $current_file : $current_file) }}{{ ($current_folder) ? '&f=' . \Illuminate\Support\Facades\Crypt::encrypt($current_folder) : '' }}">
                <span class="fa fa-sync"></span> Clean file
              </a>
                |
              <a id="delete-log"
                href="?del={{ \Illuminate\Support\Facades\Crypt::encrypt($current_folder ? $current_folder . "/" . $current_file : $current_file) }}{{ ($current_folder) ? '&f=' . \Illuminate\Support\Facades\Crypt::encrypt($current_folder) : '' }}">
                <span class="fa fa-trash"></span> Delete file
              </a>
              @if(count($files) > 1)
                  |
                  <a id="delete-all-log"
                    href="?delall=true{{ ($current_folder) ? '&f=' . \Illuminate\Support\Facades\Crypt::encrypt($current_folder) : '' }}">
                    <span class="fa fa-trash-alt"></span> Delete all files
                  </a>
              @endif
            @endif
            <button class="btn btn-primary dropdown-toggle mb-0" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Select Log File
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
              @foreach($files as $file)
                <a href="?l={{ \Illuminate\Support\Facades\Crypt::encrypt($file) }}"
                  class="dropdown-item @if ($current_file == $file) llv-active @endif">
                {{$file}}
                </a>
              @endforeach
            </div>
            <a class="refresh-link mx-2" id="refreshDataTable" title="Refresh">
              <i class="fa fa-refresh fa-2x"></i>
            </a>
          </div>
        </div>
        <div class="ibox-content">
          <div class="table-responsive">
            @if ($logs === null)
              <div>
                Log file >50M, please download it.
              </div>
            @else
              <table id="table-log" class="table table-striped" data-ordering-index="{{ $standardFormat ? 2 : 0 }}">
                <thead>
                  <tr>
                    @if ($standardFormat)
                    <th>Level</th>
                    <th>Context</th>
                    <th>Date</th>
                    @else
                    <th>Line number</th>
                    @endif
                    <th>Content</th>
                  </tr>
                </thead>
                <tbody>

                @foreach($logs as $key => $log)
                  <tr data-display="stack{{{$key}}}">
                    @if ($standardFormat)
                      <td class="nowrap text-{{{$log['level_class']}}}">
                        <span class="fa fa-{{{$log['level_img']}}}" aria-hidden="true"></span>&nbsp;&nbsp;{{$log['level']}}
                      </td>
                      <td class="text">{{$log['context']}}</td>
                    @endif
                      <td class="date">{{{$log['date']}}}</td>
                      <td class="text word-break">
                        @if ($log['stack'])
                        <button type="button" class="float-right expand btn btn-outline-dark btn-sm mb-2 ml-2 view-more" data-display="stack{{{$key}}}">
                          <span class="fa fa-search"></span>
                        </button>
                    @endif
                      {{{$log['text']}}}
                      @if (isset($log['in_file']))
                        <br />{{{$log['in_file']}}}
                      @endif
                      @if ($log['stack'])
                        <div class="stack" id="stack{{{$key}}}" style="display: none; white-space: pre-wrap;">
                          {{{ trim($log['stack']) }}}
                        </div>
                      @endif
                    </td>
                  </tr>
                @endforeach

              </tbody>
            </table>
            @endif
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
  $(document).ready(function () {
        $(document).on('click','.view-more', function () {
            $('#' + $(this).data('display')).toggle();
        });
        if ($('.llv-active') && $('.llv-active').html() && $('#dropdownMenuButton')) {
          $('#dropdownMenuButton').html($('.llv-active').html().trim() + '  &or;');
        }

        dtable = $('#table-log').DataTable({
            order: [$('#table-log').data('orderingIndex'), 'desc'],
            columns: [
                {
                    data: 'Level',
                    name: 'Level'
                },
                {
                    data: 'Context',
                    name: 'Context',
                },
                {
                    data: 'Date',
                    name: 'Date',
                    render: function (date) {
                      var gmtDateTime = moment.utc(date,"YYYY-MM-DD HH:mm:ss")
                      return gmtDateTime.local().format('DD-MM-YYYY HH:mm:ss');
                    }
                },
                {
                    data: 'Content',
                    name: 'Content'
                },
            ],
            stateSave: true,
            stateSaveCallback: function (settings, data) {
                window.localStorage.setItem("datatable", JSON.stringify(data));
            },
            stateLoadCallback: function (settings) {
                var data = JSON.parse(window.localStorage.getItem("datatable"));
                if (data) data.start = 0;
                return data;
            }
        });

        $("#refreshDataTable").click(function(e) {
            dtable.draw(false);
        });

        $('#delete-log, #clean-log, #delete-all-log').click(function () {
            return confirm('Are you sure?');
        });
  });
</script>
@endpush