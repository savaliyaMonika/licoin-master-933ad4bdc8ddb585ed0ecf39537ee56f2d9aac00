<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return view('home');
        return redirect('https://www.logisticinfotech.com/');
    }

    public function testApi() {
        return response()->json(["Hello", "Hi"], 200);
    }

    public function testPostJson(Request $request) {

        $fname = $request->input('fname');
        $lname = $request->input('lname');

        $fullName = ucwords($fname. ' '.$lname);

        return response()->json(['fullname' => $fullName], 200);
    }
}
