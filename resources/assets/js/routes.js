const router = new VueRouter();

router.map({
    '/': {
        name: 'index',
        component: require('./pages/Dashboard.vue')
    },
    
    '/language': {
        name: 'language',
        component: require('./pages/language/Index.vue')
    },
    '/language/create': {
        name: 'language_create',
        component: require('./pages/language/Create.vue')
    },
    '/language/edit/:languageId': {
        name: 'language_edit',
        component: require('./pages/language/Edit.vue')
    },
    
    '/page': {
        name: 'page',
        component: require('./pages/page/Index.vue')
    },
    '/page/create': {
        name: 'page_create',
        component: require('./pages/page/Create.vue')
    },
    '/page/edit/:pageId': {
        name: 'page_edit',
        component: require('./pages/page/Edit.vue')
    },
});

var App = Vue.extend({});

router.start(App, '#app');
