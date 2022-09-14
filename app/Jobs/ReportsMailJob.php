<?php

namespace App\Jobs;

use App\Mail\ReportsMail;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\TrnslRequests;
use App\Models\PushTryRequest;
use Carbon\Carbon;
use Mail;

class ReportsMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $todayConvertedFiles = TrnslRequests::whereDate('created_at', Carbon::yesterday())
            ->where('status', 'processed')
            ->count();
        $todayPushTryRequest = PushTryRequest::whereDate('created_at', Carbon::yesterday())
            ->count();

        $totalConvertedFiles = TrnslRequests::where('status', 'processed')->count();
        $totalPushTryRequest = PushTryRequest::count();
        $conversionInProcess = TrnslRequests::where('status', 'inprocess')->count();
        $conversionFailed = TrnslRequests::where('status', 'failed')->count();

        $item = [];
        $item['conversionInProcess'] = $conversionInProcess;
        $item['conversionFailed'] = $conversionFailed;
        $item['totalConvertedFiles'] = $totalConvertedFiles;
        $item['totalPushTryRequest'] = $totalPushTryRequest;
        $item['todayConvertedFiles'] = $todayConvertedFiles;
        $item['todayPushTryRequest'] = $todayPushTryRequest;

        Mail::to('niravjadatiya@gmail.com')->send(new ReportsMail($item));
    }
}
