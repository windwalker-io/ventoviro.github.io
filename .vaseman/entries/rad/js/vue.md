---

layout: rad.twig
title: Vue

---

## Include Vue

Phoenix includes `vue.js` 2.0 and some official vue plugins to help you build a modern single page application.

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

### Create a Vue Instance in PHP

``` php
VueScript::instance(
    '#app',
    ['item' => null],
    [
        'methods' => [
            'addItem' => "\\function () { ... }"
        ]
    ]
);
```

This will generate JS code looks like:

``` html
<script>
    window.vueInstances.app = new Vue({el:"#app",data:{item:null},methods:{addItem:function () { ... }}});
</script>
```

## Vue Animate

Use `vue2-animate` package to add transition effects by [animate.css](https://github.com/daneden/animate.css).

``` php
// Include JS file
VueScript::animate();
```

Now you can add transition name to your template:

``` html
<transition-group name="fadeLeft" tag="ul">
    <li v-for="item in items" v-bind:key="item">
        {{ item }}
    </li>
</transition-group>
```

See [vue2-animate](https://github.com/asika32764/vue2-animate/) and [Vue.js Transitions](http://vuejs.org/guide/transitions.html)

## Integrate with Form Builder

Use `attr()`, `controlAttr()` and `labelAttr()` to directly add directives to input HTML.

This is an example to show how to bind `alias` input with `title` input.

``` php
// In Form Definition class

VueScript::animate();
VueScript::instance('#admin-form', ['title' => null, 'alias' => null], ['watch' => [
    'title' => "\\function () {
        this.alias = this.title.toLowerCase().replace(/[\\s+]/g, '-');
    }"
]]);

// Title
$this->text('title')
    ->label(Translator::translate('flower.sakura.field.title'))
    ->attr('v-model', 'title');

// Alias
$this->text('alias')
    ->label(Translator::translate('flower.sakura.field.alias'))
    ->controlAttr('v-if', 'title')
    ->controlAttr('transition', 'fade')
    ->attr(':value', 'alias');
```

![vue-form](https://cloud.githubusercontent.com/assets/1639206/19294082/4edfdd1a-905c-11e6-89de-174acd181068.gif)

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
