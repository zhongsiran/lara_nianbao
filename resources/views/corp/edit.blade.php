@extends('layouts._corp_info')
@section('input_area')
    @verbatim
    <h4 id="input_area_title">录入区域</h2>
    <table id="input-table" class="table table-dark table-bordered table-sm">
        <thead>
            <td>项目</td>
            <td>操作</td>
        </thead>
        <tr>
            <th>选择电话情况</th>
            <td>
                <div class="form-row">
                    <div class="col">
                        <label for="phone_status">{{phone_update_result}}</label>
                        <select @change="log_phone_status" v-model="phone_status" class="form-control" id="phone_status">
                        <option></option>
                        <option>空号</option>
                        <option>停机</option>
                        <option>无人接听</option>
                        <option>有效电话</option>
                        </select>
                    </div>
                    <div v-if="cphone!='无电话记录'" class="col">
                        <label for="cphone_status"> {{cphone_update_result}}</label>
                        <select @change="log_cphone_status" v-model="cphone_status" class="form-control" id="cphone_status">
                        <option></option>
                        <option>空号</option>
                        <option>停机</option>
                        <option>无人接听</option>
                        <option>有效电话</option>
                        </select>
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <th>记录电联情况</th>
            <td>
                <input @click="generate_call_text('phone')" class="btn btn-primary" type="button" value="生成登记电话情况">
                <input @click="generate_call_text('cphone')" v-if="cphone!='无电话记录'" class="btn btn-primary" type="button" value="生成联络电话情况">
                <!-- 下面判断输入框中是否空白,如非空白,则按键变为绿色,否则是灰色 -->
                <input v-if="new_phone_call_text" class="btn btn-success" type="button" value="保存最新电联情况">
                <input v-else class="btn btn-secondary" type="button" value="保存最新电联情况">
                <br />
                <textarea v-model="new_phone_call_text" id="update_phone_call" style="margin:0.4rem 0 0 0">{{new_phone_call_text}}</textarea>
                <br />
                <p style="margin:0 0.4rem 0 0"> 预览最终结果:</p>
                <p>{{preview_new_phone_call}}</p>
            </td>
        </tr>
        <tr>
            <td>记录备注情况</td>
            <td>
                <input v-if="new_bei_zhu_text" class="btn btn-success" type="button" value="保存最新备注情况">
                <input v-else class="btn btn-secondary" type="button" value="保存最新备注情况">
                <br />
                <textarea v-model="new_bei_zhu_text" id="update_bei_zhu" style="margin:0.4rem 0 0 0"></textarea>
                <br />
                <p style="margin:0 0.4rem 0 0"> 预览最终结果:</p>
                <p>{{preview_new_bei_zhu}}</p>
            </td>
        </tr>
        <tr>
            <td>记录催报状态<br />（三选一）</td>
            <td>
                <input @click="set_status('已经通知')" class="btn btn-primary" type="button" value="已经通知">
                <input @click="set_status('需要跟进')" class="btn btn-primary" type="button" value="需要跟进">
                <input @click="set_status('无可救药')" class="btn btn-primary" type="button" value="无可救药">
                <div id="setting_status_result"></div>
            </td>
        </tr>
    </table>
    @endverbatim
@endsection