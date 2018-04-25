<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Corp as Corp;

class CorpsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $div = $request->div;
        $type = $request->identifier;

        $corps = Corp::where('type', $type)
                ->where('Division', $div)
                ->get();
        return view('corp.index',['corps' => $corps]);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $corp = Corp::find($id); //find得到的是object,可以在BLADE中使用$corp->xxx
            // ->get(); get得到的是collection,需要使用$corp[0]->xxx,或者 $corp->first()->xxx
        return view('corp.show', ['corp'=>$corp]);        
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $corp = Corp::find($id); //find得到的是object,可以在BLADE中使用$corp->xxx
            // ->get(); get得到的是collection,需要使用$corp[0]->xxx,或者 $corp->first()->xxx
        return view('corp.edit', ['corp'=>$corp]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \app\Corp $corp
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $result = Corp::where('RegNum', $id)
            ->update($request->except(['_token', '_method']));
        if ($result) {
            return response()->json([
                'result' => 'success'
            ]);
        } else {
            return response()->json([
                'result' => 'failed'
            ]);
        }
        // return redirect(route('corp.edit', $id));
    }
}
