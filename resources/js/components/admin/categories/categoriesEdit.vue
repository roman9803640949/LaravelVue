<template>
  <v-card class="customFontSize">
    <v-card-title>
      <span class="headline">Add category</span>
    </v-card-title>

    <v-card-text>
      <v-container>
        <v-row>
          <v-col cols="12" md="6">
            <v-text-field
              label="category"
              v-model="formData.categoryName"
              :error-messages="categoryNameErrors"
              @input="$v.formData.categoryName.$touch()"
              @blur="$v.formData.categoryName.$touch()"
            ></v-text-field>
          </v-col>
          <v-col cols="12" md="6">
            <div id="preview">
              <v-img
                v-if="formData.imageLink"
                :src="imageURL || formData.imageLink "
                alt="Upload Image"
              />
            </div>
            <v-file-input
              @change="onFileChange"
              chips
              clearable
              label="Choose Image"
              v-model="formData.iconImage"
              :error-messages="iconImageErrors"
              @input="$v.formData.iconImage.$touch()"
              @blur="$v.formData.iconImage.$touch()"
            ></v-file-input>
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
import helper from "../../../helpers/helpers";
//import axios from 'axios'
const isTrueImage = (value) => {
  if (!value) {
      console.log('!value inside',value)
    return true;
  }
  return value.type.startsWith("image");
};

export default {
  name: "categoryEdit",
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
    categoryNameErrors() {
      const errors = [];
      if (!this.$v.formData.categoryName.$dirty) return errors;
      !this.$v.formData.categoryName.required &&
        errors.push("Category Name is required.");
      return errors;
    },
    iconImageErrors() {
      const errors = [];
      if (!this.$v.formData.iconImage.$dirty) return errors;
      !this.$v.formData.iconImage.isTrueImage &&
        errors.push("Image is invalid.");
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
      imageURL: "",
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
            this.formData._method = 'PUT'
          let FormData = await helper.convertModelToFormData(this.formData);
          const { data } = await this.callApi(
            "post",
            "/app/admin/categories/" + this.formData.id,
           FormData
          );
          this.s("Categories Updated Successfully");
          this.closeForm();
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
    onFileChange(e) {
        if(e){
            this.imageURL = URL.createObjectURL(e);
        }else{
            this.imageURL=''
        }
    },
  },
  created() {
    this.getEditData();
  },
  validations: {
    formData: {
      categoryName: { required },
      iconImage: { isTrueImage },
    },
  },
};
</script>

<style scoped>
</style>
