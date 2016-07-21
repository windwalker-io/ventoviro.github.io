---

layout: rad.twig
title: Vue

---

## Include Vue

Phoenix includes `vue.js` and some official vue plugins to help you build a modern single page application.

``` php
\Phoenix\Script\VueScript::core();
```

Now you can easily use vue in your HTML page:

``` html
<!-- In <form id="admin-form"> -->

    <input name="foo" type="text" v-model="foo">

    <script>
    new Vue({
        el: '#admin-form',
        data: {
            foo: 'bar'
        }
    });
    </script>
```

Or integrate with jQuery:

``` php
@php(Phoenix\Script\JQueryScript::core())
@php(Phoenix\Script\VueScript::core())

<script>
    // Run after dom ready
    jQuery(function ($) {
        new Vue({
            el: '#admin-form',
            data: {
                foo: 'bar'
            }
        });
    })
</script>

<input name="foo" type="text" v-model="foo">
```

## Vue Router

Use Vue router to handle page switch without refresh browser:

``` php
@php(\Phoenix\Script\VueScript::router())

<div id="app">
    <h1>Hello App!</h1>
    <p>
        <a v-link="{ path: '/foo' }">Go to Foo</a>
        <a v-link="{ path: '/bar' }">Go to Bar</a>
    </p>
    <router-view></router-view>
</div>

<script>
    var Foo = Vue.extend({
        template: '<p>This is foo!</p>'
    })

    var Bar = Vue.extend({
        template: '<p>This is bar!</p>'
    })

    var App = Vue.extend({})

    var router = new VueRouter()

    router.map({
        '/foo': {
            component: Foo
        },
        '/bar': {
            component: Bar
        }
    })

    router.start(App, '#app')
</script>
```

See [Vue Router](http://router.vuejs.org/)

## Vue Resource

Use vue resource to fetch server data by ajax:

``` php
@php(\Phoenix\Script\VueScript::resource())

<script>
// global Vue object
Vue.http.get('/flower/sakura', [options]).then(successCallback, errorCallback);
Vue.http.post('/flower/sakura', [body], [options]).then(successCallback, errorCallback);

// in a Vue instance
new Vue({
    ready() {
        // GET
        this.$http.get('/flower/sakura').then((response) => {
            // success callback
        }, (response) => {
            // error callback
        });
    }
})
</script>
```

Add custom options and global headers:

``` php
\Phoenix\Script\VueScript::resource(['root' => $uri->path], [
    'common' => ['X-Foo' => 'Bar'],
    'post' => ['X-Baz' => 'Yoo'],
]);
```

See [Vue Resource](https://github.com/vuejs/vue-resource)

## VueStrap

Use VueStrap to integrate Bootstrap:

``` php
\Phoenix\Script\VueScript::strap();
```

See [VueStrap](http://yuche.github.io/vue-strap/)

## Vuex

Vuex is an application architecture for centralized state management in Vue.js applications.

``` php
\Phoenix\Script\VueScript::vuex();

// Add initial state

\Phoenix\Script\VueScript::vuex([
    'state' => ['count' => 0],
    'mutations' => [
        'INCREMENT' => '\\function (state) { state.count++ }'
    ]
]);
```

In HTML

``` js
export default new Vuex.Store({
  state,
  mutations
});
```

See [Vuex](http://vuex.vuejs.org/)
