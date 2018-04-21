
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example-component', require('./components/ExampleComponent.vue'));

const app = new Vue({
    el: '#app',
    data: {
        /* 电联记录 */
        new_phone_call_text: "", 
        old_phone_call_text: $('#old_phone_call_record').text(),
        /* 备注记录 */
        new_bei_zhu_text: "",
        old_bei_zhu_text: $('#old_bei_zhu_record').text(),
        // 取得联络员电话及情况
        cphone: $('#cphone').val(),
        cphone_status: $('#cphone_status').val(),
        // 取得电话及情况
        phone: $('#phone').val(),
        phone_status: $('#phone_status').val()
    },
    methods: {
        set_status: function (msg) {
            alert(msg);
        },
        log_phone_status: function() {
            console.log(this.phone_status);
        }
    },
    computed: {
        preview_new_phone_call: function () {
            return this.old_phone_call_text + this.new_phone_call_text
        },
        preview_new_bei_zhu: function () {
            return this.old_bei_zhu_text + this.new_bei_zhu_text
        }
    }

});
