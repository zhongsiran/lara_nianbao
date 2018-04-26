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
            <th>选择登记电话情况</th>
            <td>
                <div class="form-row">
                    <div class="col">
                        <label for="phone_status">{{phone_update_result}}</label>
                        <select @change="log_phone_status" v-model="phone_status" class="form-control" style="width:auto" id="phone_status">
                        <option></option>
                        <option>请选择</option>                            
                        <option>空号</option>
                        <option>停机</option>
                        <option>无人接听</option>
                        <option>表示已不为该企业工作</option>
                        <option>表示与该企业从来没有关系</option>
                        <option>提供了另一个联系电话</option>
                        <option>承诺会报送</option>
                        <option value="">其他</option>
                        </select>
                    </div>
                    <div class="col">
                        <label for="custom_phone_status">如果选择了其他，请手动填写并保存</label>
                        <input class="form-control" type="text" name="custom_phone_status" v-model="phone_status">
                        <input @click="log_phone_status" class="btn btn-primary" type="button" name="save_phone_status" value="保存">
                    </div>
                    </div>
                </div>
            </td>
        </tr>
        <tr v-if="cphone!='无电话记录'" >
            <th>选择联络员电话情况</th>
            <td>
                <div class="form-row">
                    <div class="col">
                        <label for="cphone_status"> {{cphone_update_result}}</label>
                        <select @change="log_cphone_status" v-model="cphone_status" class="form-control" style="width:auto" id="cphone_status">
                        <option></option>
                        <option>请选择</option>                            
                        <option>空号</option>
                        <option>停机</option>
                        <option>无人接听</option>
                        <option>表示已不为该企业工作</option>
                        <option>表示与该企业从来没有关系</option>
                        <option>提供了另一个联系电话</option>
                        <option>承诺会报送</option>
                        <option value="">其他</option>
                        </select>
                    </div>
                    <div class="col">
                        <label for="custom_cphone_status">如果选择了其他，请手动填写并保存</label>
                        <input class="form-control" type="text" name="custom_cphone_status" v-model="cphone_status">
                        <input @click="log_cphone_status" class="btn btn-primary" type="button" name="save_cphone_status" value="保存">
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
                <input v-if="new_phone_call_text" @click="log_text('phone_call')" class="btn btn-success" type="button" value="保存最新电联情况">
                <input v-else class="btn btn-secondary" type="button" value="保存最新电联情况">
                <br />
                <textarea class="form-control" v-model="new_phone_call_text" id="update_phone_call" style="margin:0.4rem 0 0 0">{{new_phone_call_text}}</textarea>
                <br />
                <p style="margin:0 0.4rem 0 0"> 预览最终结果:</p>
                <p>{{preview_new_phone_call}}</p>
            </td>
        </tr>
        <tr>
            <td>记录备注情况</td>
            <td>
                <input v-if="new_bei_zhu_text" @click="log_text('bei_zhu')" class="btn btn-success" type="button" value="保存最新备注情况">
                <input v-else class="btn btn-secondary" type="button" value="保存最新备注情况">
                <br />
                <textarea class="form-control" v-model="new_bei_zhu_text" id="update_bei_zhu" style="margin:0.4rem 0 0 0"></textarea>
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