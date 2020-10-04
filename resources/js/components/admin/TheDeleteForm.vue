<template>
    <v-card class="customFontSize">
        <v-card-title class="headline" color="red darken-1">Delete?</v-card-title>
        <v-card-text class="text-left subtitle-1">Do you want to delete
            <span class="font-weight-bold red--text" v-text="itemTitle + ''+'?'"/>
            </v-card-text>
        <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="teal darken-1" text @click="closeForm()">No</v-btn>
            <v-btn color="red darken-1" text @click="saveData()">Yes</v-btn>
        </v-card-actions>
    </v-card>
</template>

<script>
    export default {
        name: "TheDeleteForm",
        props: {
            deleteUrl: {
                type: String,
                required: true,
            },
            title:{
                type: String,
                required:true
            }
        },
        computed: {
            url() {
                return this.deleteUrl;
            },
            itemTitle(){
              return this.title
            }
        },
        data() {
            return {
                snackbar: {
                    data: {},
                    message: "",
                    color: ""
                }
            }
        },
        methods: {
            closeForm() {
                this.$emit('closeDialog')
            },
            async saveData() {
                try {
                    const {data} = await this.callApi('delete',this.url)
                    if(data.status === 200){
                        this.s('Data deleted successfully')
                    }
                    this.closeForm()
                } catch (e) {
                   this.e('Oops! Something went wrong')
                }
            }
        },
    }
</script>

<style scoped>

</style>
