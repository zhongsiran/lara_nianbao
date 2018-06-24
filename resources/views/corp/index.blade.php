{{--  corp.index 是列出业户名单的页面,显示简要的业户信息.提供进入填报或继进行查询情况的按键  --}}
@extends('layouts._default') 
@section('content')
    @include('layouts._navi_bar')
    {{--  chunk()函数按需要分为子数组  --}}
    @foreach ($corps->chunk(2) as $chunk)
        <div class="card-deck">
        @foreach ($chunk as $corp)
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{$corp->CorpName}}</h5>
                    {{--  根据不同的Status使用不同的颜色  --}}
                    @switch($corp->Status) 
                        @case('已经通知')
                            <h5 style="color:green">
                            @break
                        @case('无可救药')
                            <h5 style="color:red">
                            @break
                        @default
                            <h5>
                            @break
                    @endswitch
                    {{--  催报情况和年报情况  --}}
                    {{$corp->Status}} - {{$corp->nian_bao_status}}</h5>
                    <p class="card-text">{{$corp->RegNum}}</p>
                    {{--  <p class="card-text"><small class="text-muted">{{$corp->PhoneCallRecord}}</small></p>  --}}
                    <a class="btn btn-primary" href="{{route('corp.edit', $corp->RegNum)}}">开始录入</a>
                    <a class="btn btn-info" href="{{route('corp.show', $corp->RegNum)}}">查看详情</a>
                </div>
            </div>
        @endforeach
        </div>
    @endforeach
    {!! $corps->appends(Request::except('page'))->links() !!}
@endsection