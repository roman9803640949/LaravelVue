<template>
  <v-card class="customFontSize">
    <v-card-title>
      <span class="headline">Add Tags</span>
    </v-card-title>

    <v-card-text>
      <v-container>
        <v-row>
          <v-col cols="12" md="6">
            <v-text-field
              label="Tag"
              v-model="formData.tagName"
              :error-messages="tagNameErrors"
              @input="$v.formData.tagName.$touch()"
              @blur="$v.formData.tagName.$touch()"
            ></v-text-field>
          </v-col>
        </v-row>
      </v-container>
    </v-card-text>

    <v-card-actions>
      <v-spacer></v-spacer>
      <v-btn color="red darken-1" text @click="closeForm">Cancel</v-btn>
      <v-btn color="teal darken-1" text @click="saveData">Save</v-btn>
    </v-card-actions>
  </v-card>
</template>

<script>
import { required } from "vuelidate/lib/validators";
//import axios from 'axios'

export default {
  name: "TagsCreate",
  computed: {
    tagNameErrors() {
      const errors = [];
      if (!this.$v.formData.tagName.$dirty) return errors;
      !this.$v.formData.tagName.required &&
        errors.push("Tag Name is required.");
      return errors;
    },
  },
  data() {
    return {
      snackbar: {
        data: {},
        message: "",
        color: "",
        snackbar: false,
      },
      formData: {},
      submitStatus: "",
    };
  },
  methods: {
    closeForm() {
      this.$v.$reset();
      this.$emit("closeDialog");
    },
    async saveData() {
      this.$v.$touch();
      if (this.$v.$invalid) {
        this.submitStatus = "ERROR";
        this.w("Validation Error");
      } else {
        try {
          const { data } = await this.callApi(
            "post",
            "/app/admin/tags",
            this.formData
          );
          if (data.status === 201) {
            this.s("Tags Added Successfully");
            this.closeForm();
          }else{
              console.log('data', data)
              Object.values(data.errors).forEach(element => {
                   element.forEach(t=>{
                       this.w(t)
                   })
              });
          }
        } catch (e) {
          this.e(e);
        }
      }
    },
  },
  created() {},
  validations: {
    formData: {
      tagName: { required },
    },
  },
};
</script>

<style scoped>
</style>
