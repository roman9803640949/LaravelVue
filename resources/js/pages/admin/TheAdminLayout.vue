<template>
  <v-app id="inspire">
    <template v-if="$store.state.user">
      <v-navigation-drawer v-model="drawer" app clipped>
        <v-list active-class="customActiveClass">
                <v-list-item active-class="customActiveClass" two-line class="border">
                    <v-list-item-avatar>
                        <img src="https://randomuser.me/api/portraits/men/81.jpg" />
                    </v-list-item-avatar>

                    <v-list-item-content class="text-left">
                        <v-list-item-title>{{user.name}}</v-list-item-title>
                        <v-list-item-subtitle>{{user.email}}</v-list-item-subtitle>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item
                        active-class="customActiveClass"
                        link
                        :to="user.userDashboard"
                >
                    <v-list-item-icon>
                        <v-icon>mdi-view-dashboard</v-icon>
                    </v-list-item-icon>
                    <v-list-item-content class="text-left">
                        <v-list-item-title>Dashboard</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <template v-for="(item,index) in items">
                    <v-list-item
                            v-if="!item.menuList.length"
                            :key="index"
                            link
                            :to="{ name:item.parentMenu.link }"
                            active-class="customActiveClass"
                    >
                        <v-list-item-icon>
                            <v-icon>{{'mdi-'+item.parentMenu.icon}}</v-icon>
                        </v-list-item-icon>
                        <v-list-item-content class="text-left">
                            <v-list-item-title>{{item.parentMenu.menuName}}</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                    <v-list-group
                            :key="index"
                            v-else
                            :prepend-icon="item.parentMenu?'mdi-'+item.parentMenu.icon:''"
                            append-icon="mdi-menu-down"
                            color="teal"
                    >
                        <template v-slot:activator>
                            <v-list-item-content class="text-left">
                                <v-list-item-title v-text="item.parentMenu.menuName"></v-list-item-title>
                            </v-list-item-content>
                        </template>

                        <v-list-item
                                v-for="subItem in item.menuList"
                                :key="subItem.menuName"
                                link
                                :to="{ name: subItem.link }"
                                :class="subItem.link === $route.path.name ? 'customActiveClass' : 'teal lighten-4'"
                                class="pl-9"
                                active-class="customActiveClass"
                        >
                            <v-list-item-action v-if="subItem.icon">
                                <v-icon>{{ 'mdi-'+subItem.icon }}</v-icon>
                            </v-list-item-action>
                            <v-list-item-content class="text-left">
                                <v-list-item-title>
                                    {{subItem.menuName}}
                                </v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </v-list-group>
                </template>

                <!--
                <v-list-item
                        v-for="subItem in item.items"
                        :key="subItem.title"

                >
                    <v-list-item-content>
                        <v-list-item-title v-text="subItem.title"></v-list-item-title>
                    </v-list-item-content>
                </v-list-item>-->
            </v-list>
      </v-navigation-drawer>

      <v-app-bar app clipped-left color="red">
        <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
        <v-icon class="mx-4" large>mdi-youtube</v-icon>
        <v-toolbar-title class="mr-12 align-center">
          <span class="title">Youtube</span>
        </v-toolbar-title>
        <v-spacer></v-spacer>
        <v-row align="center" style="max-width: 650px">
          <v-text-field
            :append-icon-cb="() => {}"
            placeholder="Search..."
            single-line
            append-icon="mdi-magnify"
            color="white"
            hide-details
          ></v-text-field>
        </v-row>
      </v-app-bar>
    </template>
    <v-main>
      <v-container>
        <v-snackbar
          v-for="(snackbar, index) in snackbars.filter((s) => s.showing)"
          :key="index"
          v-model="snackbar.showing"
          absolute
          timeout="5000"
          top
          right
          outlined
          :color="snackbar.color"
          :style="`top: ${index * 90 + 60}px;z-index:500`"
          @change="console.log(change)"
        >
          {{ snackbar.text }}
        </v-snackbar>
        <router-view></router-view>
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
//import TheSnackbar from "../components/TheSnackbar";
import { mapState } from "vuex";
export default {
  name: "TheAdminLayout",
  data: () => ({
    drawer: null,
    items: [
        {
          icon: 'mdi-apps',
          parentMenu: {
            icon: 'cog',
            menuName: 'Tags',
            link: 'tags'
          },
          menuList: []
        },
        {
          icon: 'mdi-apps',
          parentMenu: {
            icon: 'cog',
            menuName: 'Categories',
            link: 'categories'
          },
          menuList: []
        },
        {
          icon: 'mdi-off',
          parentMenu: {
            icon: 'off',
            menuName: 'logout',
            link: 'logout'
          },
          menuList: []
        },
      ],
    isLoggedIn: false,
  }),
  computed: {
    ...mapState(["snackbars",'user']),
  },
  created() {
    console.log("this.user", this.user);
    this.$vuetify.theme.dark = true;
  },
  components: {
    //TheSnackbar,
  },
};
</script>

<style lang="scss">
$grey1: #eeeeee99;
  ::-webkit-scrollbar {
    width: 6px;
  }

  /* Track */
  ::-webkit-scrollbar-track {
    background: $grey1;
  }

  /* Handle */
  ::-webkit-scrollbar-thumb {
    background: #4e434361;
  }

  /* Handle on hover */
  ::-webkit-scrollbar-thumb:hover {
    background: #4e434361;
  }
  ::v-deep.customActiveClass{
    background: #009688 !important;
    color: white !important;
  }
  .v-navigation-drawer--right{
    z-index: 4
  }
  ::v-deep.customFontSize ::v-deep.v-card__text ::v-deep.v-text-field input ,::v-deep.customFontSize ::v-deep.v-card__text ::v-deep.v-input--selection-controls input {
    font-size: 0.8em;
    text-transform: capitalize;
  }
  ::v-deep.customFontSize ::v-deep.v-card__text ::v-deep.v-text-field label, ::v-deep.customFontSize ::v-deep.v-card__text ::v-deep.v-list-item__content ::v-deep.v-list-item__title ,::v-deep.customFontSize .v-input--selection-controls label {
    font-size: 0.8em !important;
  }
</style>
