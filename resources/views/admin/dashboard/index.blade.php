@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="wrapper wrapper-content">
    <div class="row">
        <div class="col-lg-4">
            <div class="ibox ">
                <div class="ibox-title">
                    <span class="label label-success float-right">Today</span>
                    <h5>PushNotification Tests </h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{$todayPushTryRequest}}</h1>
                    <div class="stat-percent font-bold text-success">{{$totalPushTryRequest}} <i class="fa fa-bolt"></i>
                    </div>
                    <small>Total PushNotification Tests till today</small>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="ibox ">
                <div class="ibox-title">
                    <span class="label label-success float-right">Today</span>
                    <h5>Converted File</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{$todayConvertedFiles}}</h1>
                    <div class="stat-percent font-bold text-success">{{$totalConvertedFiles}} <i class="fa fa-bolt"></i>
                    </div>
                    <small>Total converted files till today</small>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="ibox ">
                <div class="ibox-title">
                    <span class="label label-primary float-right">In Process</span>
                    <h5>Conversion </h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{$conversionInProcess}}</h1>
                    <div class="stat-percent font-bold text-danger">{{$conversionFailed}} <i
                            class="fa fa-level-down"></i></div>
                    <small>Conversion failed till today</small>
                </div>
            </div>
        </div>
        {{-- today translation request --}}
        {{-- <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-title">
                    <h5> <i class="fa fa-language"></i> Today's Request </h5>
                </div>
                <div class="ibox-content">
                    <div id="fileChartTodayDiv" ></div>
                </div>
            </div>
        </div> --}}
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-title">
                    <h5><i class="fa fa-language"></i> Files Converted This Week</h5>
                </div>
                <div class="ibox-content">
                    <div id="fileChartDiv" ></div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-title">
                    <h5><i class="fa fa-bell"></i> Push Tested This Week</h5>
                </div>
                <div class="ibox-content">
                    <div id="pushChartDiv"></div>
                </div>
            </div>
        </div>
        <div id="top_x_div" style="width: 900px; height: 500px;"></div>
    </div>
</div>
@endsection

@push('script')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script>
    $(document).ready(function () {
    var trnslRequestsChartData = {!! json_encode($trnslRequestsChartData) !!};

    google.charts.load('current', {packages: ['corechart', 'bar']});
    var fileChartDiv = document.getElementById('fileChartDiv');
    var fileChartTodayDiv = document.getElementById('fileChartTodayDiv');
    var fileChartVAxis = 'Files';
    google.charts.setOnLoadCallback(function() { drawChart(trnslRequestsChartData, fileChartDiv, fileChartVAxis); });
    //  pushTryChart
    var pushTryChartData = {!! json_encode($pushTryChartData) !!};
    // console.log(pushTryChartData)
    google.charts.load('current', {packages: ['corechart', 'bar']});
    var pushChartDiv = document.getElementById('pushChartDiv');
    var pushChartDivVAxis = 'Push Tries';
    google.charts.setOnLoadCallback(function() { drawChart(pushTryChartData, pushChartDiv, pushChartDivVAxis,1); });
  });

  function drawChart(chartData,chartDiv, vAxis,isPush= null) {

      var data = new google.visualization.DataTable();
      data.addColumn('string', 'Date');
      isPush ?  null : data.addColumn('number', 'Total');
      data.addColumn('number', 'Success');
      data.addColumn('number', 'Failed');
      data.addRows(chartData);
      // data.addRows([
      //   // ['27/05/2019', 5, 1],
      //   // ['26/05/2019', 2, 1],
      //   // ['25/05/2019', 3, 1],
      //   // ['24/05/2019', 4, 2],
      //   // ['23/05/2019', 5, 2],
      //   // ['22/05/2019', 6, 3],
      //   // ['21/05/2019', 7, 4],
      // ]);

      var options = {
        // tooltip: { trigger: 'selection' },
        hAxis: {
          title: 'Date',
        },
        vAxis: {
          title: vAxis,
          format: '#',
        },

        colors: isPush? ['#1b9e77', '#b70101']: ['#0069d9','#1b9e77', '#b70101'],
        height: 200,
        chartArea: {
          left: 90,
          // top: 10,
          // width: '50%',
          // height: 300
        },
        animation:{
          startup: true,
          duration: 1000,
          easing: 'out',
        },
      };

      var chart = new google.visualization.ColumnChart(chartDiv);
      chart.draw(data, options);

      // var materialChart = new google.charts.Bar(document.getElementById('chart_div'));
      // materialChart.draw(data, options);
    }
</script>
@endpush
