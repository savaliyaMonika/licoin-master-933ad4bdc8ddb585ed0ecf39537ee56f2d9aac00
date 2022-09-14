<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TrnslKey;
use App\Models\TransIbmUrlKey;
use Yajra\Datatables\Datatables;
use Validator;

class TrnslKeyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.trnsl-keys.index');
    }


    public function datatable()
    {
        $getdata =  TrnslKey::get();
        return Datatables::of($getdata)->make(true);
    }

    public function resetCounts()
    {
        TrnslKey::where('count', '>', 0)->update(['count' => 0]);
        $result['code'] = 200;
        $result['message'] = "Counts reset successfully";
        return response()->json($result);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $selectData = array('yandex'=>'Yandex translation','google'=> 'Google translation','ibm'=> 'IBM translation');
        return view('admin.trnsl-keys.create', compact('selectData'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required|max:255',
            'api_key_type' => 'required',
            'key' => 'required|max:255|unique:trnsl_keys',
            'url_key' => 'sometimes|max:255',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $trnslKey = new TrnslKey();
        $trnslKey->name = $input['name'];
        $trnslKey->api_key_type = $input['api_key_type'];
        $trnslKey->key = $input['key'];
        $trnslKey->save();

        if($trnslKey->api_key_type == 'ibm'){
            $transIbmUrlKey = new TransIbmUrlKey();
            $transIbmUrlKey->url_key = $input['url_key'];
            $transIbmUrlKey->trans_key_id = $trnslKey->id;
            $transIbmUrlKey->save();
        }
        return redirect('/admin/trnsl-keys')->with("success", "Key Added Successfully");
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
        $selectData = array('yandex' => 'Yandex translation', 'google' => 'Google translation', 'ibm' => 'IBM translation');
        $key = TrnslKey::where('id',$id)->with('transIbmUrlKey')->first();

        if (empty($key)) {
            return redirect('/admin/trnsl-keys')->with("error", "Record not found.");
        }

        return view('admin.trnsl-keys.edit',compact('key', 'selectData'));
        // return view('admin.trnsl-keys.edit',compact($key))->with('key', (object)$key);
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
        $input = $request->all();
        $trnslKey = TrnslKey::find($id);
        $validator = Validator::make($input, [
            'name' => 'required|max:255',
            'api_key_type' => 'required',
            'key' => "required|max:255|unique:trnsl_keys,key,{$id}",
            'url_key' => 'sometimes|max:255',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if (empty($trnslKey)) {
            return redirect('/admin/trnsl-keys')->with("error", "Record not found.");
        }
        $trnslKey->name = $input['name'];
        $trnslKey->api_key_type = $input['api_key_type'];
        $trnslKey->key = $input['key'];
        $trnslKey->save();

        if ($trnslKey->api_key_type == 'ibm') {
            $transIbmUrlKey = TransIbmUrlKey::where('trans_key_id', $id)->first();
            $transIbmUrlKey->url_key = $input['url_key'];
            $transIbmUrlKey->save();
        }
        return redirect('/admin/trnsl-keys')->with("success", "Key Added Successfully");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $key = TrnslKey::find($id);

        if ($key) {
            $key->delete();
            return response()->json(["message" => 'Key deleted successfully!'], 200);
        } else {
            return response()->json(["message" => 'Error deleting key!'], 400);
        }
    }

    public function changeStatus(Request $request)
    {
        $trnslkey = TrnslKey::find($request->id);
        if($request->checked == 'true'){
            $trnslkey->status = 1;
        }else{
            $trnslkey->status = 0;
        }
        $trnslkey->save();
        if ($trnslkey) {
            return response()->json(["message" => 'status change successfully!'], 200);
        } else {
            return response()->json(["message" => 'Error change status!'], 400);
        }

    }
}
