@extends('layouts._default', $corp)
@section('update-link')
<meta name="update-link" content="{{ route('corp.update', $corp->RegNum)}}">
@endsection

@section('content')
    @include('layouts._navi_bar')
    <h4 style="margin:0" id="corp_info_title">企业信息 - {{$corp->Division}} - {{$corp->div_corp_index}}  </h4>
    <table class="table table-dark table-striped table-bordered table-sm" id="corp_info_table">
        <thead>
            <th scope="col">项目</th>
            <th scope="col">数据</th>
        </thead>
        <tbody>
            <tr>
                <th scope="row">注册号</th>
                <td> {{$corp->RegNum}}</td>
            </tr>

            <tr>
                <th scope="row">字号</th>
                <td class="name_and_phone" id="corp_name">{{$corp->CorpName}} </td>
            </tr>

            <tr>
                <th scope="row">备注信息</th>
                <td id="old_bei_zhu_record">{{$corp->InspectionStatus}}</td>
            </tr>

            <tr>
                <th scope="row">电话记录</th>
                <td id="old_phone_call_record">{{$corp->PhoneCallRecord}}</td>
            </tr>

            <tr>
                <th scope="row">法人及登记电话</th>
                    <td>{{$corp->RepPerson}} : <span class="name_and_phone">{{preg_replace("/^(\d{3})(\d{4})(\d{4})$/", "$1-$2-$3", $corp->Phone)}}({{$corp->phone_status}})</span> </td>
                    <input id="phone" type="hidden" value="{{$corp->Phone}}">
                    <input id="phone_status" type="hidden" value="{{$corp->phone_status}}">
            </tr>
            <tr>
                <th scope="row">联络员及电话</th>
                <td>
                    {{$corp->ContactPerson}} :
                    <span class="name_and_phone">
                        {{preg_replace("/^(\d{3})(\d{4})(\d{4})$/", "$1-$2-$3", $corp->ContactPhone)}}({{$corp->cphone_status}})
                    </span>
                    <input id="cphone" type="hidden" value="{{$corp->ContactPhone}}">
                    <input id="cphone_status" type="hidden" value="{{$corp->cphone_status}}">
                </td>
            </tr>

            <tr>
                <th scope="row">状态</th>
                    
                @switch($corp->Status)
                    @case("已经通知")
                        <td style="color:green">催报状态：{{$corp->Status}}
                        @break
                    @case("需要跟进")
                        <td style="color:yellow">催报状态：{{$corp->Status}}
                        @break
                    @case("无可救药")
                        <td style="color:red">催报状态：{{$corp->Status}}
                        @break                        
                @endswitch
                    |年报状态：{{$corp->nian_bao_status}}
                    |负责人员：<span id="designated_person">{{$corp->designated_person}}</span>
                </td>
            </tr>
        </tbody>
    </table>
    @yield('input_area')
@endsection