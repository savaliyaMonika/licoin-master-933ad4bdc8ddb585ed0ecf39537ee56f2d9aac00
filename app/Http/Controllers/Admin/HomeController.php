<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TrnslRequests;
use App\Models\PushTryRequest;
use App\User;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $todayConvertedFiles = TrnslRequests::whereDate('created_at', Carbon::today())
            ->where('status', 'processed')
            ->count();
        $todayPushTryRequest = PushTryRequest::whereDate('created_at', Carbon::today())->count();

        $totalConvertedFiles = TrnslRequests::where('status', 'processed')->count();
        $totalPushTryRequest = PushTryRequest::count();
        $conversionInProcess = TrnslRequests::where('status', 'inprocess')->count();
        $conversionFailed = TrnslRequests::where('status', 'failed')->count();
        // dd($chartData);
        $pushTryChartData = $this->pushTryChartData();
        $trnslRequestsChartData = $this->trnslRequestsChartData();

        return view('admin.dashboard.index')
            ->with([
                'conversionInProcess' => $conversionInProcess,
                'conversionFailed' => $conversionFailed,
                'totalConvertedFiles' => $totalConvertedFiles,
                'totalPushTryRequest' => $totalPushTryRequest,
                'todayConvertedFiles' => $todayConvertedFiles,
                'todayPushTryRequest' => $todayPushTryRequest,
                'pushTryChartData' => $pushTryChartData,
                'trnslRequestsChartData' => $trnslRequestsChartData,
                // 'todayTrnslRequestsChartData' => $todayTrnslRequestsChartData,
            ]);
    }


    public function listUser()
    {
        return view('admin.users-table.index');
    }

    public function datatable()
    {
        $getdata =  User::get();
        return Datatables::of($getdata)->make(true);
    }

    /**
     * For Get Chart Data of Push Try
     *
     */

     public function pushTryChartData()
     {
      $diff = 7; // Difference in days
      $startDate = Carbon::today()->subDays($diff);
      $carbonStart = Carbon::parse($startDate)->startOfDay();
      $carbonEnd = Carbon::now()->endOfDay();
      $pushTryRequestArr = PushTryRequest::where('created_at', '>=', $carbonStart)
          ->where('created_at', '<=', $carbonEnd)
          ->get();
      $pushTryRequestArr = $pushTryRequestArr->groupBy(
          function ($date) {
              return Carbon::parse($date->created_at)->format('d M Y');
          }
      );
      $failedPushCount = [];
      $processedPushCount = [];
      $pushTryChartData = [];

      for($i = 0 ; $i <= $diff; $i++){
          $currentDay = $carbonStart->copy()->addDays($i)->format('d M Y');
          if(isset($pushTryRequestArr[$currentDay])){
              $value = $pushTryRequestArr[$currentDay];
              $failedPushCount = 0;
              $processedPushCount = count($value);
              $pushTryChartData[] = [$currentDay, $processedPushCount, $failedPushCount];
          } else {
              $pushTryChartData[] = [$currentDay, 0, 0];
          }
      }

      $pushTryChartData = array_reverse($pushTryChartData);
      return $pushTryChartData;
     }

    /**
     * For Get Chart Data of Push Try
     *
     */


    public function trnslRequestsChartData()
    {

      $diff = 7; // Difference in days
      $startDate = Carbon::today()->subDays($diff);
      $carbonStart = Carbon::parse($startDate)->startOfDay();
      $carbonEnd = Carbon::now()->endOfDay();
      $requestsArr = TrnslRequests::where('created_at', '>=', $carbonStart)
          ->where('created_at', '<=', $carbonEnd)
          ->get();

      $requestsArr = $requestsArr->groupBy(
          function ($date) {
              return Carbon::parse($date->created_at)->format('d M Y');
          }
      );

      $failedCount = [];
      $processedCount = [];
      $chartData = [];

      for($i = 0 ; $i <= $diff; $i++){
          $currentDay = $carbonStart->copy()->addDays($i)->format('d M Y');
          if(isset($requestsArr[$currentDay])){
            $value = $requestsArr[$currentDay];
            $failedCount = count($value->whereIn('status', ['mailfailed', 'failed']));
            $totalrequest = count($value);
            $processedCount = count($value->where('status', 'processed'));
            $chartData[] = [$currentDay, $totalrequest, $processedCount, $failedCount ];
        } else {
            $chartData[] = [$currentDay, 0, 0,0];
        }
    }

      $chartData = array_reverse($chartData);
      return $chartData;
    }

    public function viewLogFile(Request $request) {
      // dd($request->fileName);
      $fileName = $request->fileName;
      // dd((url('storage/logs/' . $fileName )));
      $test = \File::get(storage_path('logs/' . $fileName));
      dump($test);
    }

    public function jsonLocalTranslate(Request $request)
    {
        return view('admin.json-local-translate.index');
    }
}
