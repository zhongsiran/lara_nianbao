@extends('layouts._default') 
@section('content')
    @include('layouts._navi_bar')
    
    @foreach ($corps->chunk(2) as $chunk)
        <div class="card-deck">
        @foreach ($chunk as $corp)
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{$corp->CorpName}}</h5>
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