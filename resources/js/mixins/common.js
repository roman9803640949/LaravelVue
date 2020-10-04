export default {
    data() {
        return {

        }
    },
    methods: {
        async callApi(method,url,dataObj){
                try {
                    return await axios({
                        method:method,
                        url:url,
                        data:dataObj
                    })
                } catch (error) {
                    console.log('catches error :',error)
                    return error.response
                }
        },
        i(text){
            this.$store.dispatch('Notify',{
                text:text,
                color:'info'
            })
        },
        s(text){
            this.$store.dispatch('Notify',{
                text:text,
                color:'success'
            })
        },
        w(text){
            this.$store.dispatch('Notify',{
                text:text,
                color:'warning'
            })
        },
        e(text){
            this.$store.dispatch('Notify',{
                text:text,
                color:'error'
            })
        },
    },
}
