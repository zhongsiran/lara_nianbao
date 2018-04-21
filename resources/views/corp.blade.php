@extends('layouts._corp_info')
@section('input_area')
    @verbatim
    <h4 id="input_area_title">录入区域</h2>
    <table class="table table-dark table-bordered table-sm">
        <thead>
            <td>录入项目</td>
            <td>录入或操作内容</td>
        </thead>
        <tr>
            <th>选择电话情况</th>
            <td>
                <div class="form-row">
                    <div class="col">
                        <label for="phone_status">选择企业电话情况</label>
                        <select @change="log_phone_status" v-model="phone_status" class="form-control" id="phone_status">
                        <option></option>
                        <option>空号</option>
                        <option>停机</option>
                        <option>无人接听</option>
                        <option>有效电话</option>
                        </select>
                    </div>
                    <div v-if="cphone!='无电话记录'" class="col">
                        <label for="cphone_status">选择联络电话情况</label>
                        <select class="form-control" id="cphone_status">
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
                <input class="btn btn-primary" id="generate_phone_call" type="button" value="生成登记电话情况">
                <input v-if="cphone!='无电话记录'" class="btn btn-primary" id="generate_cphone_call" type="button" value="生成联络电话情况">
                <input v-if="new_phone_call_text" class="btn btn-success" type="button" value="保存最新电联情况">
                <input v-else class="btn btn-secondary" type="button" value="保存最新电联情况">
                <br />
                <textarea v-model="new_phone_call_text" id="update_phone_call" style="width:35rem;margin:0.4rem 0 0 0"></textarea>
                <br />
                <p style="margin:0 0.4rem 0 0"> 预览:</p>
                <p style="width:35rem;">{{preview_new_phone_call}}</p>
            </td>
        </tr>
        <tr>
            <td>记录备注情况</td>
            <td>
                <input v-if="new_bei_zhu_text" class="btn btn-success" type="button" value="保存最新备注情况">
                <input v-else class="btn btn-secondary" type="button" value="保存最新备注情况">
                <br />
                <textarea v-model="new_bei_zhu_text" id="update_bei_zhu" style="width:35rem;margin:0.4rem 0 0 0"></textarea>
                <br />
                <p style="margin:0 0.4rem 0 0"> 预览:</p>
                <p style="width:35rem;">{{preview_new_bei_zhu}}</p>
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