<?php

namespace App\Http\Controllers\Admin;

use App\Models\PushTryRequest;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PushTryRequestController extends Controller
{
    /**
     * Display resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      return view('admin.push-try.index');
    }
    /**
     *  return Datatable
     *
     *  @return Yajra\Datatables\Datatables
     */
    public function datatable()
    {
        $getdata =  PushTryRequest::get();
        return Datatables::of($getdata)->make(true);
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
     * @param  \App\Models\PushTryRequest  $pushTryRequest
     * @return \Illuminate\Http\Response
     */
    public function show(PushTryRequest $pushTryRequest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PushTryRequest  $pushTryRequest
     * @return \Illuminate\Http\Response
     */
    public function edit(PushTryRequest $pushTryRequest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PushTryRequest  $pushTryRequest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PushTryRequest $pushTryRequest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PushTryRequest  $pushTryRequest
     * @return \Illuminate\Http\Response
     */
    public function destroy(PushTryRequest $pushTryRequest)
    {
        //
    }
}
