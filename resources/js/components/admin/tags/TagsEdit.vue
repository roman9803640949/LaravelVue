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
  props: {
    getItemUrl: {
      type: String,
      required: true,
    },
  },
  computed: {
    ItemDetailUrl() {
      return this.getItemUrl;
    },
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
            "put",
            "/app/admin/tags/"+this.formData.id,
            this.formData
          );
          this.s("Tags Updated Successfully");
          this.closeForm()
        } catch (e) {
          this.e(e);
        }
      }
    },
    async getEditData() {
      try {
        const { data } = await this.callApi("get", this.ItemDetailUrl);
        this.formData = data;
      } catch (e) {
        console.log(e);
      }
    },
  },
  created() {
    this.getEditData();
  },
  validations: {
    formData: {
      tagName: { required },
    },
  },
};
</script>

<style scoped>
</style>
