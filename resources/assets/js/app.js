import Axios from 'axios'

/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap')

window.Vue = require('vue')

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// Vue.component('example-component', require('./components/ExampleComponent.vue'))

const app = new Vue({
    el: '#app',
    data: {

        // 电联记录
        // new_phone_call_text: "", 
        new_phone_call_text: $('#phone_call_record_of_the_year').text(),
        // old_phone_call_text: $('#old_phone_call_record').text(),
        // 备注记录 
        new_bei_zhu_text: "",
        old_bei_zhu_text: $('#old_bei_zhu_record').text(),
        // 取得联络员电话及情况
        cphone: $('#cphone').val(),
        cphone_status: $('#cphone_status').val(),
        cphone_update_result: '选择联络员电话情况',
        // 取得电话及情况
        phone: $('#phone').val(),
        phone_status: $('#phone_status').val(),
        phone_update_result: '选择企业登记电话情况',
        // ajax接收URI,在meta中设定
        update_url: '',
        // 文字更新情况反馈
        phone_call_update_status: '预览结果',
        bei_zhu_update_status: '预览结果',
        // 简要状态更新反馈
        setting_status_result: ''
    },
    methods: {
        set_status: function (msg) {
            this.utils_get_update_link()
            var vm = this
            axios.patch(vm.update_url, {
                Status: msg
            })
            .then(function(res){
                switch (res.data.result) {
                    case 'success':
                        vm.setting_status_result = '更新成功！！'
                        break
                    default:
                        vm.setting_status_result = '更新失败！！'
                        break
                }
            })
        },
        log_text: function (column) {
            this.utils_get_update_link()
            var vm = this
            switch (column) {
                case 'PhoneCallRecord':
                    axios.patch(vm.update_url, {
                        PhoneCallRecord: vm.preview_new_phone_call
                    })
                    .then(function(res){
                        switch (res.data.result) {
                            case 'success':
                                vm.phone_call_update_status = '更新成功！！'
                                break
                            default:
                                vm.phone_call_update_status = '更新失败！！'
                                break
                        }
                    })
                    break
                case 'InspectionStatus':
                    console.log('ins')
                    axios.patch(vm.update_url, {
                        InspectionStatus: vm.preview_new_bei_zhu
                    })
                    .then(function(res){
                        switch (res.data.result) {
                            case 'success':
                                vm.bei_zhu_update_status = '更新成功！！'
                                break
                            default:
                                vm.bei_zhu_update_status = '更新失败！！'
                                break
                        }
                    })
                    break
            }
            
        },
        log_phone_status: function() {
            this.utils_get_update_link()
            var vm = this
            axios.patch(vm.update_url,{
                phone_status: vm.phone_status
            })
            .then(function (res) {
                switch (res.data.result) {
                    case 'success':
                        vm.phone_update_result = '更新成功！！'
                        break
                    default:
                        vm.phone_update_result = '更新失败！！'
                        break
                }
            })
            .catch(function (error) {
                vm.answer = 'Error! Could not reach the API. ' + error
            })
        },
        log_cphone_status: function () {
            this.utils_get_update_link()
            var vm = this
            axios.patch(vm.update_url, {
                cphone_status: vm.cphone_status
            })
            .then(function (res) {
                switch (res.data.result) {
                    case 'success':
                        vm.cphone_update_result = '更新成功！！'
                        break
                    default:
                        vm.cphone_update_result = '更新失败！！'
                        break
                }
            })
            .catch(function (error) {
                vm.answer = 'Error! Could not reach the API. ' + error
            })
        },
        /**
         *  根据传入的类型,生成对应的打电话情况,用于更新到数据库中.
         *  @param string type
         *  @var string phone_called 实际拨打的电话
         *  @var string person_called 实际拨打的人员(方面)
         *  @var string called_status 最新的拨打情况
         */
        generate_call_text:function (type) {
            switch (type) {
                case 'phone':
                    var phone_called = this.phone
                    var person_called = '登记电话'
                    var called_status = this.phone_status
                    break
                case 'cphone':
                    var phone_called = this.cphone
                    var person_called = '联络员电话'
                    var called_status = this.cphone_status
                    break
                default:
                    console.log(type)
                    break
            }
            var designated_person = $('#designated_person').text()
            var corp_name = $('#corp_name').text().trim()

            // let computed_new_called_text = this.utils_chn_date() + ',' + designated_person + '拨打' + corp_name + person_called + phone_called + '，结果为' + called_status + ' |'
            let computed_new_called_text = this.utils_chn_date() + '拨打' + person_called + phone_called + ':' + called_status + ' |'

            // todo 补回原来的生成结果代码
            this.new_phone_call_text += computed_new_called_text
        },
        utils_chn_date: function () {
            var mydate = new Date()
            var year = mydate.getFullYear()
            var month = mydate.getMonth() + 1
            var date = mydate.getDate()
            var hours = mydate.getHours()
            var minute = mydate.getMinutes()
            var chn_starttime = year + '年' + month + '月' + date + '日' + hours + '时' + minute + '分'
            return chn_starttime
        },
        utils_get_update_link: function () {
            try {
                this.update_url = (document.head.querySelector('meta[name="update-link"]').content)
            }
            catch(TypeError) {
                console.log('Cannot find a meta with the name "update-link')
            }
        }
    },
    computed: {
        preview_new_phone_call: function () {
            if (!this.new_phone_call_text) {
                return '无新增内容'
            } else {
                // return this.old_phone_call_text + this.new_phone_call_text    
                return this.new_phone_call_text    
            }
            
        },
        preview_new_bei_zhu: function () {
            if (!this.new_bei_zhu_text) {
                return '无新增内容'
            } else {
                return this.old_bei_zhu_text + this.new_bei_zhu_text                
            }
        },
    }
})
