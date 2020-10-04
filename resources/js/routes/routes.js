import store from '../store'
export default [

    {
        path: '/admin',
        meta:{ auth:true },
        component: () => import('../pages/admin/TheAdminLayout.vue'),
        children:[
            {
                path: '',
                name: 'adminDashboard',
                component: () => import('../pages/admin/home.vue'),
            },
            {
                path: '/dashboard',
                name: 'adminDashboard',
                component: () => import('../pages/admin/home.vue'),
            },
            {
                path: 'tags',
                name: 'tags',
                component: () => import('../pages/admin/tags/Tags.vue'),
            },
            {
                path: 'categories',
                name: 'categories',
                component: () => import('../pages/admin/categories/category.vue'),
            },
            {
                path: '/logout',
                name: 'logout',
                beforeEnter: (to, from, next) => {
                    store.dispatch('logout').then(()=>{
                        window.location.href = '/login'
                    })
                }
            },
        ]
    },
    {
        path: '/login',
        component: () => import('../pages/public/login.vue'),
        name: 'login'
    },

];
