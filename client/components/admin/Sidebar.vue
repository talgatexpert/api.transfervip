<template>
  <v-card>

    <v-navigation-drawer
      class="rounded-0"
      v-model="drawer"
      :mini-variant.sync="mini"
      permanent
      app

      dark
    >
      <v-list-item class="px-2">
        <v-list-item-avatar>
          <v-img src="https://randomuser.me/api/portraits/men/85.jpg"></v-img>
        </v-list-item-avatar>

        <v-list-item-title>{{ username }}</v-list-item-title>

        <v-btn
          icon
          @click.stop="mini = !mini"
        >
          <v-icon>mdi-chevron-left</v-icon>
        </v-btn>
      </v-list-item>

      <v-divider></v-divider>

      <v-list dense>
        <v-list-item v-for="(item,key) in items"   :key="key" :to="item.path">
          <v-list-item-icon> <v-icon>{{item.icon}}</v-icon> </v-list-item-icon>
          <v-list-item-content>
            <v-list-item-title>{{item.title}}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>




        <v-list-item
          link
          @click="logout"
        >
          <v-list-item-icon>
            <v-icon>{{ logout_data.icon }}</v-icon>
          </v-list-item-icon>

          <v-list-item-content>
            <v-list-item-title>{{ logout_data.title }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>

      </v-list>
    </v-navigation-drawer>

  </v-card>
</template>
<script>

export default {
  name: "Sidebar",


  data() {
    return {
      drawer: true,
      logout_data: {path: '/panel/logout', title: 'Logout', icon: 'mdi-logout'},
      mini: false,
    }
  },




  computed: {

    items(){
      const role =  this.$auth.user.hidden_role ?? this.$auth.user?.user?.hidden_role ?? 'client'
      let items = [];


      items.push({path: '/panel/account', title: 'My Account', icon: 'mdi-account'})
      items.push({path: '/panel/bookings', title: 'Bookings', icon: 'mdi-bus'})

      if (role === 'client') {
        items.push({path: '/panel/trips', title: 'Trips', icon: 'mdi-arrow-bottom-right'})
      }
      if (role === 'super_admin') {
        items.push({path: '/panel/settings', title: 'Settings', icon: 'mdi-account-settings-outline'})
        items.push({path: '/panel/cities', title: 'Cities', icon: 'mdi-city'})
        items.push({path: '/panel/cars', title: 'Cars', icon: 'mdi-car'})
        items.push({path: '/panel/companies', title: 'Companies', icon: 'mdi-account-multiple'})
        items.push({path: '/panel/clients', title: 'Clients', icon: 'mdi-account-group-outline'})
        items.push({path: '/panel/transfers', title: 'Transfers', icon: 'mdi-car-outline'})
        items.push({path: '/panel/users', title: 'Users', icon: 'mdi-account-outline'})
      }
      if (role === 'admin') {
        items.push({path: '/panel/cities', title: 'Cities', icon: 'mdi-city'})
        items.push({path: '/panel/clients', title: 'Clients', icon: 'mdi-account-group-outline'})
        items.push({path: '/panel/cars', title: 'cars', icon: 'mdi-car'})
        items.push({path: '/panel/transfers', title: 'Transfers', icon: 'mdi-car-outline'})
      }
      if (role === 'company') {
        items.push({path: '/panel/cars', title: 'cars', icon: 'mdi-car'})
        items.push({path: '/panel/transfers', title: 'Transfers', icon: 'mdi-car-outline'})
        items.push({path: '/panel/clients', title: 'Clients', icon: 'mdi-account-group-outline'})
      }

      return items;
    },
    username() {
      const name = this.$auth?.user?.name ?? this.$auth.user?.user?.name;
      return name || '';
    }

  },
  methods: {
    async logout() {
      await this.$auth.logout({
        url: 'logout'
      }).then(async result => {
        await this.$store.dispatch('CLEAR_ROLE')

      })
    },
  }
}
</script>

<style scoped>

</style>
