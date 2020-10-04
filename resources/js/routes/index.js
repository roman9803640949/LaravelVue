import Vue from 'vue';
import VueRouter from 'vue-router';
import store from '../store';

Vue.use(VueRouter);
import routes from "./routes";
let router = new VueRouter({
    base: '/',
    mode: 'history',
    routes: routes
});

router.beforeEach((to,from,next)=>{
    if(to.matched.some(route=>route.meta.auth)){
        if(!store.state.user){
            next('/login')
        }
        next()
    }
    next()
})
export default router

