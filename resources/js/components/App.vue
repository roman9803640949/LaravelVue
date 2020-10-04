<template>
  <v-app id="inspire">
    <router-view></router-view>
  </v-app>
</template>

<script>
//import TheSnackbar from "../components/TheSnackbar";
import { mapState } from "vuex";
export default {
  props: ["user"],
  data: () => ({
    drawer: null,
    isLoggedIn: false,
  }),
  computed: {
    ...mapState(["snackbars"]),
  },
  created() {
    this.$store.commit("UPDATE_USER", this.user);
    this.$vuetify.theme.dark = true;
  },
  components: {
    //TheSnackbar,
  },
};
</script>
