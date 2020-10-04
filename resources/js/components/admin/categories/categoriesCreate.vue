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
                v-if="formData.iconImage"
                :src="imageURL"
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
import helper from '../../../helpers/helpers'
import { required } from "vuelidate/lib/validators";
const isTrueImage = (value) => {
  if (!value) {
    return true;
  }
  return value.type.startsWith("image");
};
export default {
  name: "categoryCreate",
  computed: {
    categoryNameErrors() {
      const errors = [];
      if (!this.$v.formData.categoryName.$dirty) return errors;
      !this.$v.formData.categoryName.required &&
        errors.push("category Name is required.");
      return errors;
    },
    iconImageErrors() {
      const errors = [];
      if (!this.$v.formData.iconImage.$dirty) return errors;
      !this.$v.formData.iconImage.required && errors.push("Image is required.");
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
          let FormData = helper.convertModelToFormData(this.formData)
          const { data } = await this.callApi(
            "post",
            "/app/admin/categories",
            FormData
          );
          if (data.status === 201) {
            this.s("categorys Added Successfully");
            this.closeForm();
          } else {
            console.log("data", data);
            Object.values(data.errors).forEach((element) => {
              element.forEach((t) => {
                this.w(t);
              });
            });
          }
        } catch (e) {
          this.e(e);
        }
      }
    },
    onFileChange(e) {
      this.imageURL = URL.createObjectURL(e);
    },
  },
  created() {},
  validations: {
    formData: {
      categoryName: { required },
      iconImage: { required, isTrueImage },
    },
  },
};
</script>

<style scoped>
</style>
