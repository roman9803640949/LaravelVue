import Vue from 'vue'
import Vuex from 'vuex'
import callApi from '../mixins/common'

Vue.use(Vuex)

export default new Vuex.Store({

    state:{
        snackbars:[],
        user:{}
    },
    mutations: {

        SET_SNACKBAR(state,snackbar){
            state.snackbars = state.snackbars.concat(snackbar);
            console.log(state.snackbars.length)
        },
        CLEAR_SNACKBAR(state){
            setTimeout(()=>{
                if(state.snackbars.length>7){
                    state.snackbars= state.snackbars.filter(f=>!f)
                }
            },5000)
        },
        UPDATE_USER(state,user){
            state.user = user
        }
    },
    actions:{
        Notify({commit, dispatch},snackbar) {
            snackbar.showing = true
            snackbar.color = snackbar.color || 'success'
            commit('SET_SNACKBAR',snackbar)
            commit('CLEAR_SNACKBAR')
        },
        async logout(){
            console.log(callApi)
            return await callApi.methods.callApi('post','/app/logout',{})
        }
    },
    modules:{}

})
