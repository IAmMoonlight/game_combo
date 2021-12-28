import Vue from "vue";
import AdminPanel from "./vue/AdminPanel";
import UserPanel from "./vue/UserPanel";
import TableResults from "./vue/TableResults";

require('./bootstrap');

if(document.querySelector('#adminPanel')){
    new Vue(AdminPanel).$mount('#adminPanel');
}
if(document.querySelector('#userPanel')){
    new Vue(UserPanel).$mount('#userPanel');
}
if(document.querySelector('#tableResults')){
    new Vue(TableResults).$mount('#tableResults');
}

