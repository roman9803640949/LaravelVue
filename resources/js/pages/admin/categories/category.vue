<template>
  <v-container>
    <v-breadcrumbs :items="breadcrumb"></v-breadcrumbs>
    <v-data-table
      :headers="headers"
      :items="rows"
      show-select
      :footer-props="{
        itemsPerPageOptions: [10, 20, 30, 40, 50],
      }"
      :loading="snackbar.loading"
      class="elevation-1"
      @input="tableSelect"
    >
      <template v-slot:top>
        <v-toolbar flat>
          <v-toolbar-title></v-toolbar-title>
          <v-spacer></v-spacer>
          <v-btn small color="red" dark class="ma-2" @click="createItem"
            >Add categories</v-btn
          >
          <v-btn
            small
            color="warning"
            dark
            class="ma-2"
            v-if="snackbar.selectedRows.length"
            @click="deleteItem"
            >Delete categories</v-btn
          >
        </v-toolbar>
        <v-divider color="red"></v-divider>
      </template>
      <template #item.actions="{ item }">
        <v-icon small class="mr-2" @click="editItem(item)">mdi-pencil</v-icon>
        <v-icon small @click="deleteItem(item)">mdi-delete</v-icon>
      </template>
    </v-data-table>
    <v-dialog
      max-width="700px"
      persistent
      scrollable
      transition="dialog-bottom-transition"
      v-model="snackbar.dialog"
      v-if="snackbar.dialog"
    >
      <createForm v-if="snackbar.createForm" @closeDialog="closeForm" />
      <TheDeleteForm
        v-if="snackbar.deleteForm"
        :delete-url="deleteUrl"
        :title="deleteItemTitle"
        @closeDialog="closeForm"
      />
      <editForm
        v-if="snackbar.editForm"
        :getItemUrl="itemDetailUrl"
        @closeDialog="closeForm"
      />
    </v-dialog>
  </v-container>
</template>

<script>
//import axios from "axios";
import CreateForm from "../../../components/admin/categories/categoriesCreate";
import EditForm from "../../../components/admin/categories/categoriesEdit";
import TheDeleteForm from "../../../components/admin/TheDeleteForm.vue";
export default {
  name: "categoriesGrid",
  computed: {
    breadcrumb() {
      return [
        {
          disabled: false,
          exact: true,
          text: "Dashboard",
          to: ``,
        },
        {
          disabled: true,
          exact: true,
          text: "Tag Lists",
          to: ``,
        },
      ];
    },
    deleteUrl() {
      return this.snackbar.deleteUrl;
    },
    deleteItemTitle() {
      return this.snackbar.deleteItemTitle;
    },
    itemDetailUrl() {
      return this.snackbar.getItemUrl;
    },
  },
  data() {
    return {
      snackbar: {
        loading: true,
        dialog: false,
        createForm: false,
        editForm: false,
        deleteForm: false,
        detailComponent: false,
        getItemUrl: "",
        deleteUrl: "",
        deleteItemTitle: "",
        selectedRows: [],
      },
      headers: [
        {
          text: "ID",
          value: "id",
        },
        {
          text: "Category Name",
          value: "categoryName",
        },
        {
          text: "Actions",
          value: "actions",
        },
      ],
      rows: [],
      totalRows: 0,
      pagination: {
        descending: true,
        page: 1,
        itemsPerPage: 10,
        sortBy: ["categoryName"],
        sortDesc: [true],
      },
      filterParams: {
        tagCode: "",
        categoryName: "",
        branchName: "",
        panNumber: "",
      },
    };
  },
  methods: {
    tableSelect(input) {
      this.snackbar.selectedRows = input.map((t) => t.id);
    },
    editItem({ id }) {
      this.snackbar.dialog = true;
      this.snackbar.editForm = true;
      this.snackbar.getItemUrl = "/app/admin/categories/" + id;
    },
    showItem({ id }) {
      this.snackbar.dialog = true;
      this.snackbar.detailComponent = true;
      this.snackbar.getItemUrl = "/app/admin/categories/" + id;
    },
    deleteItem({ id, categoryName }) {
      this.snackbar.dialog = true;
      this.snackbar.deleteForm = true;
      this.snackbar.deleteUrl = "/app/admin/categories/" + id;
      this.snackbar.deleteItemTitle = "category  " + "   " + categoryName;
    },
    createItem() {
      this.snackbar.dialog = true;
      this.snackbar.createForm = true;
    },
    closeForm() {
      this.snackbar.dialog = false;
      this.snackbar.createForm = false;
      this.snackbar.editForm = false;
      this.snackbar.deleteForm = false;
      this.snackbar.detailComponent = false;
      this.snackbar.getItemUrl = "";
      this.snackbar.deleteUrl = "";
      this.loadItems();
    },
    async loadItems() {
      try {
        const { data } = await this.callApi("get", "/app/admin/categories");
        this.rows = data;
        this.snackbar.loading = false;
        console.log('data',data)
      } catch (error) {
        this.e(error)
      }
    },
  },
  created() {
    this.loadItems();
  },
  watch: {
    pagination: {
      handler() {
        this.loadItems();
      },
      deep: true,
      immediate:false
    },
  },
  components: {
    CreateForm,
    EditForm,
    TheDeleteForm,
  },
};
</script>

<style scoped>
</style>
