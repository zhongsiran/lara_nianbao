{{--  corp.index 是列出业户名单的页面,显示简要的业户信息.提供进入填报或继进行查询情况的按键  --}}
@extends('layouts._default') 
@section('content')
    @include('layouts._navi_bar')

    @if (isset($is_search_page))
        <div class="card-deck">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">根据条件“{{$search_content}}”共搜索到{{$corps->count()}}家企业</h5>
                </div>
            </div>
        </div>    
    @endif
    
    <!-- 用CHUNK（）每x个分一组 -->
    @foreach ($corps->chunk(1) as $chunk)

        <div class="card-deck">
        <!-- 使用FOREACH遍历 -->
        @foreach ($chunk as $corp)
        <!-- SWITCH 年报状态，已公示的变为透明 -->
            @switch($corp->nian_bao_status)
                @case("未填报")
                    <div class="card">
                    @break
                @case('已公示')
                    <div class="card" style="opacity: 0.2;">
                    @break
                @default
                    <div class="card">
            @endswitch
                <div class="card-body">

                    <h5 class="card-title">{{$corp->Division}}-{{$corp->type}}-{{$corp->div_corp_index}}-{{$corp->CorpName}}</h5>
                    {{-- SWITCH 通知情况以决定颜色 --}}

                    @switch($corp->Status) 
                        @case('已经通知')
                            <h5 class="bg-success text-white">
                            @break
                        @case('无可救药')
                            <h5 class="bg-danger text-white">
                            @break
                        @case('需要跟进')
                            <h5 class="bg-warning text-dark">
                            @break
                        @default
                            <h5>
                            @break
                    @endswitch
                    {{--  催报情况和年报情况  --}}
                    {{$corp->Status}} - {{$corp->nian_bao_status}}</h5>
                    <p class="card-text">{{$corp->RegNum}}</p>
                    <p class="card-text">{{$corp->Addr}}</p>
                    <p class="card-text"><small class="text-muted">历史电话记录：{{$corp->PhoneCallHistoryRecord}}</small></p> 
                    <p class="card-text"><small class="text-muted">今年电话记录：{{$corp->PhoneCallRecord}}</small></p> 
                    @if (isset($is_search_page))
                    {{--  判断是否搜索列表  --}}
                        <a class="btn btn-primary" href="{{route('corp.edit', ['id'=>$corp->RegNum, 'search_content' => $search_content])}}">录入</a>
                    @else
                        <a class="btn btn-primary" href="{{route('corp.edit', $corp->RegNum)}}">录入</a>
                    @endif
                    

                    {{-- <a class="btn btn-info" href="{{route('corp.show', $corp->RegNum)}}">查看</a> --}}
                </div>
            </div>
        @endforeach
        </div>
    @endforeach
    {!! $corps->appends(Request::except('page'))->links() !!}
@endsection