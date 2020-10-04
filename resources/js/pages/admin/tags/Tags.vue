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
            >Add Tags</v-btn
          >
          <v-btn
            small
            color="warning"
            dark
            class="ma-2"
            v-if="snackbar.selectedRows.length"
            @click="deleteItem"
            >Delete Tags</v-btn
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
import CreateForm from "../../../components/admin/tags/TagsCreate.vue";
import EditForm from "../../../components/admin/tags/TagsEdit.vue";
import TheDeleteForm from "../../../components/admin/TheDeleteForm.vue";
export default {
  name: "TagsGrid",
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
          text: "Tag Name",
          value: "tagName",
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
        sortBy: ["tagName"],
        sortDesc: [true],
      },
      filterParams: {
        tagCode: "",
        tagName: "",
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
      this.snackbar.getItemUrl = "/app/admin/tags/" + id;
    },
    showItem({ id }) {
      this.snackbar.dialog = true;
      this.snackbar.detailComponent = true;
      this.snackbar.getItemUrl = "/app/admin/tags/" + id;
    },
    deleteItem({ id, tagName }) {
      this.snackbar.dialog = true;
      this.snackbar.deleteForm = true;
      this.snackbar.deleteUrl = "/app/admin/tags/" + id;
      this.snackbar.deleteItemTitle = "tag " + "   " + tagName;
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
        const { data } = await this.callApi("get", "/app/admin/tags");
        this.rows = data;
        console.log("rows", this.rows);
        this.snackbar.loading = false;
      } catch (error) {
        console.log("error", error);
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
