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

        $paginated_corps = Corp::where('type', $type)
        ->where('Division', 'like', '%' . $div . "%")
        ->orderBy('div_corp_index', 'asc')
        ->paginate(10);

        // 非搜索列表，按div_corp_index来实现可跨页的 prev next
        $request->session()->forget('active_page');

        $data = ['corps'=>$paginated_corps, 'div'=>$div, 'type'=>$type, 'page'=>$page, 'page_type'=>'corp_index'];
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

        // 2019-6-1增加：联络员电话重复数量
        $contact_phone_list = Corp::where('ContactPhone', $corp->ContactPhone)
        ->where('ContactPhone', '<>', '无记录')
        ->get();
        $contact_phone_repeat_count = $contact_phone_list->count();
        
        $data = 
        [
            'corp'=>$corp, 
            'div'=>$div, 
            'type'=>$type, 
            'page'=>$page, 
            'page_type'=>'corp_detail', 
            'phone_list'=>$phone_list, 
            'cphone_list'=>$contact_phone_list
        ];

        return view('corp.show', $data);
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $corp = Corp::find($id); //find得到的是object,可以在BLADE中使用$corp->xxx
            // ->get(); get得到的是collection,需要使用$corp[0]->xxx,或者 $corp->first()->xxx
        $type = session('type') ?? $corp->type;
        $div = session('div') ?? $corp->div;
        $page = session('page') ?? '';

        // 2019-05-27增加：统计电话重复数量
        $phone_list = Corp::where('Phone', $corp->Phone)
        ->get();

        // 2019-6-1 增加：统计联络员电话重复数量
        $contact_phone_list = Corp::where('ContactPhone', $corp->ContactPhone)
        ->where('ContactPhone', '<>', '无记录')
        ->get();


        $search_content = $request->search_content ?? NULL;

        $data = 
        [
            'corp'=>$corp, 
            'div'=>$div, 
            'type'=>$type, 
            'page'=>$page, 
            'page_type'=>'corp_detail', 
            'phone_list'=>$phone_list, 
            'contact_phone_list'=>$contact_phone_list,
            'search_content'=>$search_content
        ];

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
    
    public function corps_search(Request $request)
    {
        $search_content = $request->search_content ?? '';
        
        // 判断是否纯数字
        if (intval($search_content)) {
            $search_column = 'RegNum';
        }else {
            $search_column = 'CorpName';
        }
        // 数据库操作,根据 regnum模糊查询 取得数据,事实上不分页
        $corps = Corp::where($search_column, 'like', "%" . $search_content . "%")
        ->paginate(10000);

        session()->put('active_page', $corps);

        $data = ['corps' => $corps, 'page_type' => 'corp_index', 'is_search_page' => TRUE, 'search_content' => $search_content];
        // 输出到blade模版 corp中的index模版
        return view('corp.index', $data);
    }

    // 要求div corp index为连续的
    public function next(Request $request, $id)
    {
        // 搜索结果类名单存放在SESSION中
        $active_page = $request->session()->get('active_page', NULL);
        
        if ($active_page != NULL) {
            
            $corps = $active_page->all();
            $corps_count = count($corps);
            foreach ($corps as $key => $corp) {
                if($corp->RegNum == $id && $key != $corps_count-1) {
                    //不是最后一户，序号加1

                    return redirect(route('corp.edit', $corps[$key + 1]->RegNum));

                }elseif ($corp->RegNum == $id && $key == $corps_count-1)  {
                    // 是最后一户，返回第1户

                    return redirect(route('corp.edit', $corps[0]->RegNum));

                }
            }
            
        }else {
            // Session中无列表记录，则通知当前企业的 division 和 div_corp_index来定位下一户
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
                    ->orderBy('div_corp_index', 'asc')
                    ->first();
            } else {
                $next_corp = Corp::where('Division', $division)
                    ->where('type', $type)
                    ->where('div_corp_index', '>', $div_corp_index)
                    ->orderBy('div_corp_index', 'asc')
                    ->first();
            }
            return redirect(route('corp.edit', $next_corp->RegNum));
        }
        // return redirect(route('corp.edit', $next_corp[0]->RegNum));
    }

    public function prev(Request $request, $id)
    {
        $active_page = $request->session()->get('active_page', NULL);
        
                
        if ($active_page != NULL) {
            
            $corps = $active_page->all();
            $corps_count = count($corps);
            foreach ($corps as $key => $corp) {
                if($corp->RegNum == $id && $key != 0) {
                    //不是第一户，序号减1

                    return redirect(route('corp.edit', $corps[$key - 1]->RegNum));

                }elseif ($corp->RegNum == $id && $key == 0)  {
                    // 是第一户，返回最后一户

                    return redirect(route('corp.edit', $corps[$corps_count-1]->RegNum));

                }
            }
            
            }else {

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
                    ->orderBy('div_corp_index', 'desc')  // 使用COUNT得到本企业所在列表最大的序号
                    ->first();
            } else {
                $prev_corp = Corp::where('Division', $division)
                    ->where('type', $type)
                    ->where('div_corp_index', '<', $div_corp_index)
                    ->orderBy('div_corp_index', 'desc')
                    ->first();

            }
            return redirect(route('corp.edit', $prev_corp->RegNum));

        }
    }
}
