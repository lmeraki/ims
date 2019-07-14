
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

// importing dependencies
import VueRouter from 'vue-router';
import { Form, HasError, AlertError } from 'vform';
import moment from 'moment';
import VueProgressBar from 'vue-progressbar';
import Swal from 'sweetalert2';
import vSelect from 'vue-select';
// end importing dependencies

// using vue router
Vue.use(VueRouter);
// end using vue router

// creating vue component
// Vue.component('Dashboard', require('./components/Dashboard.vue'));
// end creating vue component

// using v-form
window.Form = Form;
Vue.component(HasError.name, HasError);
Vue.component(AlertError.name, AlertError);
// end using v-form

// creating moment.js function for formatting date
Vue.filter('created_at', function(date) {
	return moment(date).format('MMMM Do YYYY');
});

Vue.filter('created_at_time', function(date) {
	return moment(date).format('MMMM Do YYYY hh:mm:ss');
});

Vue.filter('days_left', function(date) {
	return moment(date).toNow(true);
});
// end moment.js

// using vue progressbar
Vue.use(VueProgressBar, {
	color: '#38c172',
	failedColor: 'red',
	height: '5px'
});
// end using vue progressbar

// initiating sweetalet2
window.swal = Swal;
// end initiating sweetalert2

// initiating sweetalert2 toast
const toast = swal.mixin({
	toast: true,
	position: 'top-end',
	showConfirmButton: false,
	timer: 3000
});
window.toast = toast;
// end initiating sweetalert2 toast

// creating global variable for custom event
Vue.fire = new Vue();
// end creating global variable for custom event

// using the pagination component
Vue.component('pagination', require('laravel-vue-pagination'));
// end using the pagination component

// using vue-select component
Vue.component('v-select', vSelect);
// end using vue-select component

// creating vue routes
const routes = [
// Admin Routes
	{ path: '/admin_dashboard', component: require('./components/AdminDashboard.vue') },
	{ path: '/admin_category', component: require('./components/Category.vue') },
	{ path: '/admin_userslist', component: require('./components/Users.vue') },
	{ path: '/admin_itemslist', component: require('./components/Items.vue') },
	{ path: '/admin_stockin', component: require('./components/Stockin.vue') },
	{ path: '/admin_pending', component: require('./components/PendingItems.vue') },
// End Admin Routes
// User Routes
	{ path: '/user_dashboard', component: require('./components/UserDashboard.vue') },
	{ path: '/user_itemslist', component: require('./components/UserItems.vue') },
// End User Routes
// Account Settings Route
	{ path: '/account_settings', component: require('./components/AccountSettings.vue') },
// 
];
// end creating vue routes

// creating vue router and history mode
const router = new VueRouter({
	mode: 'history',
	routes
});
// end creating vue router and history mode

// initiating vue app and router
const app = new Vue({
    el: '#app',
    router
});
// end initiating vue app and router
