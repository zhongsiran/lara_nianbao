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
        /** 从REQUEST里面取得参数,如果没有就从SESSION里面取得
         * $type    企业类型
         * $div     片区
         * $page    页码
         */
        
        $type = $request->type ?? session('type');
        $div = $request->div ?? session('div');
        $page = $request->page ?? session('page') ?? '';
        
        // 将新取得的参数存入SESSION
        $request->session()->put('type', $type);
        $request->session()->put('div', $div);
        $request->session()->put('page', $page);

        // 数据库操作,根据 type 和 div 取得数据,并用paginate()分页
        $corps = Corp::where('type', $type)
                ->where('Division', $div)
                ->paginate(10);
                
        $data = ['corps'=>$corps, 'div'=>$div, 'type'=>$type, 'page'=>$page, 'page_type'=>'corp_index'];
        // 输出到blade模版 corp中的index模版
        return view('corp.index', $data);
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

        $type = session('type') ?? $corp->type;
        $div = session('div') ?? $corp->div;
        $page = session('page') ?? '';
        
        // 2019-05-27增加：统计电话重复数量
        $phone_list = Corp::where('Phone', $corp->Phone)
        ->get();
        $phone_repeat_count = $phone_list->count();
        
        $data = ['corp'=>$corp, 'div'=>$div, 'type'=>$type, 'page'=>$page, 'page_type'=>'corp_detail', 'phone_list'=>$phone_list];

        return view('corp.show', $data);
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
        $type = session('type') ?? $corp->type;
        $div = session('div') ?? $corp->div;
        $page = session('page') ?? '';

        // 2019-05-27增加：统计电话重复数量
        $phone_list = Corp::where('Phone', $corp->Phone)
        ->get();
        // $phone_list = $phone_list->count();


        $data = ['corp'=>$corp, 'div'=>$div, 'type'=>$type, 'page'=>$page, 'page_type'=>'corp_detail', 'phone_list'=>$phone_list];

        return view('corp.edit', $data);
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
    
    // 要求div corp index为连续的
    public function next(Request $request, $id)
    {
        $corp = Corp::find($id);
        $division = $corp->Division;
        $type = $corp->type;
        $div_corp_index = $corp->div_corp_index;

        $list_with_type_div = Corp::where('Division', $division)
            ->where('type', $type)
            ->orderBy('div_corp_index')
            ->get();

        if ($corp->div_corp_index === $list_with_type_div->count()) {
            // 最后一户
            $next_corp = Corp::where('Division', $division)
                ->where('type', $type)
                ->where('div_corp_index', 1)
                ->get();
        } else {
            $next_corp = Corp::where('Division', $division)
                ->where('type', $type)
                ->where('div_corp_index', $div_corp_index + 1)
                ->get();
        }

        return redirect(route('corp.edit', $next_corp[0]->RegNum));
    }

    public function prev(Request $request, $id)
    {
        $corp = Corp::find($id);
        $division = $corp->Division;
        $type = $corp->type;
        $div_corp_index = $corp->div_corp_index;

        $list_with_type_div = Corp::where('Division', $division)
            ->where('type', $type)
            ->orderBy('div_corp_index')
            ->get();

        /*判断是否第一户，是就返回最后一户，否则返回上一户*/
        if ($corp->div_corp_index === 1) {
            // 第一户
            $prev_corp = Corp::where('Division', $division)
                ->where('type', $type)
                ->where('div_corp_index', $list_with_type_div->count())  // 使用COUNT得到本企业所在列表最大的序号
                ->get();
        } else {
            $prev_corp = Corp::where('Division', $division)
                ->where('type', $type)
                ->where('div_corp_index', $div_corp_index - 1)
                ->get();
        }

        return redirect(route('corp.edit', $prev_corp[0]->RegNum));
    }
}
