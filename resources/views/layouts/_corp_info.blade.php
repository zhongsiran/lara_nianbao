{{--  企业详情显示页面的部分  --}}
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
                <td class="name_and_phone" id="corp_name">{{$corp->CorpName}}({{$corp->nian_bao_status}}) </td>
            </tr>

            <tr>
                <th scope="row">地址</th>
                <td id="corp_address">{{$corp->Addr}} </td>
            </tr>            

            <tr>
                <th scope="row">备注信息</th>
                <td id="old_bei_zhu_record">{{$corp->InspectionStatus}}</td>
            </tr>

            <tr>
                <th scope="row">往年电话记录</th>
                <td id="phone_call_record_of_history">{{$corp->PhoneCallHistoryRecord}}</td>
            </tr>

            <tr>
                <th scope="row">当年电话记录</th>
                <td id="phone_call_record_of_the_year">{{$corp->PhoneCallRecord}}</td>
            </tr>

            <tr>
                <th scope="row">法人及登记电话</th>
            <td>{{$corp->RepPerson}} : <span class="name_and_phone">{{preg_replace("/^(\d{3})(\d{4})(\d{4})$/", "$1-$2-$3", $corp->Phone)}}({{$corp->phone_status}})（出现次数：{{$phone_list->count()}}）</span> </td>
                    <input id="phone" type="hidden" value="{{$corp->Phone}}">
                    <input id="phone_status" type="hidden" value="{{$corp->phone_status}}">
            </tr>
            <tr>
                <th scope="row">联络员及电话</th>
                <td>
                    {{$corp->ContactPerson}} :
                    <span class="name_and_phone">
                        {{preg_replace("/^(\d{3})(\d{4})(\d{4})$/", "$1-$2-$3", $corp->ContactPhone)}}({{$corp->cphone_status}})
                        {{-- $contact_phone_list可能为0，则不显示 --}}
                        @if ($contact_phone_list->count() != 0)
                            （出现次数：{{$contact_phone_list->count()}}）    
                        @endif
                    </span>
                    <input id="cphone" type="hidden" value="{{$corp->ContactPhone}}">
                    <input id="cphone_status" type="hidden" value="{{$corp->cphone_status}}">
                </td>
            </tr>

            <tr>
                <th scope="row">状态</th>
                    
                @switch ($corp->Status)
                    @case("已经通知")
                        <td class="bg-success text-white">催报状态：{{$corp->Status}}
                        @break
                    @case("需要跟进")
                        <td class="bg-warning text-dark">催报状态：{{$corp->Status}}
                        @break
                    @case("无可救药")
                        <td class="bg-danger text-white">催报状态：{{$corp->Status}}
                        @break                        
                @endswitch

                </td>
            </tr>
        </tbody>
    </table>

    @yield('input_area')

    {{-- 电话重复企业显示区 --}}
    @if($phone_list->count() > 1)
    <h4 id="phone_repeat_corps">登记电话重复企业名单</h4>
    <table class="table table-dark table-bordered">
        <thead>
            <tr>
                <th scope="col">片区</th>
                <th scope="col">名称</th>
                <th scope="col">电话联络情况</th>
                <th scope="col">年报情况</th>
                <th scope="col">操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($phone_list as $phone_list_item)
            <tr>
                <td>{{$phone_list_item->Division . '-' . $phone_list_item->type .'-'. $phone_list_item->div_corp_index}}
                </td>
                <td>{{$phone_list_item->CorpName}}</td>
                <td>{{$phone_list_item->PhoneCallRecord}}</td>
                <td>{{$phone_list_item->nian_bao_status}}</td>
                <td><a class="btn btn-primary" href="{{route('corp.edit', $phone_list_item->RegNum)}}">跳转</a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    @if ($contact_phone_list->count() > 1)
    <h4 id="contact_phone_repeat_corps">联络员电话重复企业名单</h4>
    <table class="table table-dark table-bordered">
        <thead>
            <tr>
                <th scope="col">片区</th>
                <th scope="col">名称</th>
                <th scope="col">电话联络情况</th>
                <th scope="col">年报情况</th>
                <th scope="col">操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($contact_phone_list as $contact_phone_list_item)
            <tr>
                <td>{{$contact_phone_list_item->Division . '-' . $contact_phone_list_item->type .'-'. $contact_phone_list_item->div_corp_index}}
                </td>
                <td>{{$contact_phone_list_item->CorpName}}</td>
                <td>{{$contact_phone_list_item->PhoneCallRecord}}</td>
                <td>{{$contact_phone_list_item->nian_bao_status}}</td>
                <td><a class="btn btn-primary" href="{{route('corp.edit', $contact_phone_list_item->RegNum)}}">跳转</a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
    {{-- 电话重复企业显示区结束 --}}
@endsection
