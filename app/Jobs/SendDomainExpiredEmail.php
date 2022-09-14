<?php

namespace App\Jobs;

use App\Mail\SslExpiredMail;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Carbon\Carbon;
use Mail;
use DB;
use App\Models\DomainSslDetails;
use Illuminate\Support\Facades\Log;
class SendDomainExpiredEmail implements ShouldQueue
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
        // Log::info("SendDomainExpiredEmail gone in __construct");
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $from = Carbon::today()->addDays(7);
        // $to = Carbon::today()->addDays(5);
        // Log::info("gone in handle");
        // DB::enableQueryLog();
        $expiredDomains =  DomainSslDetails::select('*')
            ->whereDate('expiration_date', '=', $from->toDateString())
            // ->whereBetween('expiration_date', [$from, $to])
            ->with(['subscriber'])
            ->get();

        $expiredDomains->each(function ($item, $key) {
            $mailList = $item->subscriber->pluck('email')->toArray();
            // Log::info("");
            // Log::info($item['expiration_date']);
            // Log::info("");
            $item['expiration_date'] = Carbon::createFromFormat('Y-m-d H:i:s', $item['expiration_date'])->format('D d-m-Y h:i:s');
            $item['valid_from_date'] = Carbon::createFromFormat('Y-m-d H:i:s', $item['valid_from_date'])->format('D d-m-Y h:i:s');
            $item['expiration_date_diff'] = Carbon::now()->diffInHours($item['expiration_date'], false);
            Mail::to($mailList)->send(new SslExpiredMail($item));
        });
    }
}
