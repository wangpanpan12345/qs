
window._ = require('lodash');

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

//window.$ = window.jQuery = require('jquery');
require('bootstrap-sass');

/**
 * Vue is a modern JavaScript library for building interactive web interfaces
 * using reactive data binding and reusable components. Vue's API is clean
 * and simple, leaving you to focus on building your next great project.
 */

window.Vue = require('vue');
require('vue-resource');

/**
 * We'll register a HTTP interceptor to attach the "CSRF" header to each of
 * the outgoing requests issued by this application. The CSRF middleware
 * included with Laravel will automatically verify the header's value.
 */

Vue.http.interceptors.push((request, next) => {
    request.headers['X-CSRF-TOKEN'] = Laravel.csrfToken;

    next();
});

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from "laravel-echo"

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: 'your-pusher-key'
// });
import Echo from "laravel-echo"

window.Echo = new Echo({
    broadcaster: 'socket.io',
    host: 'https://qs.geekheal.net:6001',
});
window.Echo.channel('dailynew.static.1')
    .listen('DailyNewsUpdate', function (data) {
        showNotice();
        function showNotice() {
            Notification.requestPermission(function (perm) {
                if (perm == "granted") {
                    var notification = new Notification("每日新闻通知:", {
                        dir: "auto",
                        lang: "hi",
                        tag: "testTag",
                        icon: "http://7xpx9u.com1.z0.glb.clouddn.com/qisu/logo/%20qisu%20logo-02.png",
                        body: "过去一小时更新了"+data.static+"个新闻,请注意查看"
                    });
                }
            })
        }
    });

