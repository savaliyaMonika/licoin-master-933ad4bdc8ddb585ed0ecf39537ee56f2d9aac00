<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TrnslRequests;
use Yajra\Datatables\Datatables;
use App\Jobs\LangTrasnlate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\LangTrasnlateMail;

class TrnslRequestsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.trnsl-requests.index');
    }

    public function datatable()
    {
        $getdata =  TrnslRequests::get();
        return Datatables::of($getdata)->make(true);
    }

    /**
     * Enque failed request from admin panel
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    public function enqueueTrnslRequests($id)
    {

      $trnslRequests = TrnslRequests::find($id);
      $fromLang = $trnslRequests->from_lng;
      $toLang = $trnslRequests->to_lng;
      $toEmail = $trnslRequests->email;
      $path = config('siteconfig.PATH.UPLOAD_TRANSLATION_FILE');
      $file = $trnslRequests->from_file;
      $filelangjson = $path.$file;
    //   TODO: ibm var. dynamic
    LangTrasnlate::dispatch($toEmail, $filelangjson, $fromLang, $toLang,"ibm");
      return response()->json(["message" => 'Request enqueue successfully'], 200);
    }

    public function sendMail($id)
    {

      $trnslRequests = TrnslRequests::find($id);
      $pathLangTranslatedFile = $trnslRequests->file_url . $trnslRequests->to_file;
      // \Log::info($trnslRequests);
      // \Log::info($pathLangTranslatedFile);
      $mailSend = Mail::to($trnslRequests->email)->send(new LangTrasnlateMail($pathLangTranslatedFile));

      $trnslRequests->update(['status' => 'processed']);
      return response()->json(["message" => 'Mail sent successfully'], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $record = TrnslRequests::find($id);
        // dd($fileToDelete);
        // return response()->json(['message' => $record]);
        if ($record) {
            $fileToDelete = 'filelangjson/' . $record->from_file;
            Storage::delete($fileToDelete);
            $record->delete();
            return response()->json(["message" => 'Record deleted successfully!'], 200);
        } else {
            return response()->json(["message" => 'Error deleting record!'], 400);
        }
    }
}
