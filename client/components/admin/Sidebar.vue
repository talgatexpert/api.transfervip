<template>
  <v-card>

    <v-navigation-drawer
      class="rounded-0 background-sidebar"
      v-model="drawer"
      :mini-variant.sync="mini"
      permanent
      app

      dark
    >
      <v-list-item class="px-2 mt-3">
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

      <v-divider class="mt-0 mb-0"></v-divider>

      <v-list dense>
        <v-list-item v-for="(item,key) in items" :exact="item.exact"  :key="key" :to="item.path">
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
      logout_data: {path: '/panel/logout', title: 'Kapatma', icon: 'mdi-logout'},
      mini: false,
    }
  },




  computed: {

    items(){
      const role =  this.$auth.user.hidden_role ?? this.$auth.user?.user?.hidden_role ?? 'client'
      let items = [];


      items.push({path: '/panel/', title: 'Kontrol paneli', icon: 'mdi-home', exact: true})
      items.push({path: '/panel/account', title: 'Hesabım', icon: 'mdi-account'})
      items.push({path: '/panel/bookings', title: 'Rezervasyonlar', icon: 'mdi-bus'})

      if (role === 'client') {
        items.push({path: '/panel/trips', title: 'Seyahatler', icon: 'mdi-arrow-bottom-right'})
      }
	    console.log(role)
      if (role === 'super_admin') {
        items.push({path: '/panel/cities', title: 'Şehirler', icon: 'mdi-city'})
        items.push({path: '/panel/cars', title: 'Araçlar', icon: 'mdi-car'})
        items.push({path: '/panel/companies', title: 'Şirketler', icon: 'mdi-account-multiple'})
        items.push({path: '/panel/clients', title: 'Müşteriler', icon: 'mdi-account-group-outline'})
        items.push({path: '/panel/transfers', title: 'Aktarmalar', icon: 'mdi-car-outline'})
        items.push({path: '/panel/users', title: 'Kullanıcı', icon: 'mdi-account-outline'})
	      items.push({path: '/panel/settings', title: 'Sistem Ayarları', icon: 'mdi-account-settings-outline'})

      }
      if (role === 'admin') {
        items.push({path: '/panel/cities', title: 'Şehirler', icon: 'mdi-city'})
        items.push({path: '/panel/clients', title: 'Müşteriler', icon: 'mdi-account-group-outline'})
        items.push({path: '/panel/cars', title: 'Araçlar', icon: 'mdi-car'})
        items.push({path: '/panel/transfers', title: 'Aktarmalar', icon: 'mdi-car-outline'})
      }
      if (role === 'company') {
        items.push({path: '/panel/cars', title: 'Araçlar', icon: 'mdi-car'})
        items.push({path: '/panel/transfers', title: 'Aktarmalar', icon: 'mdi-car-outline'})
        items.push({path: '/panel/clients', title: 'Müşteriler', icon: 'mdi-account-group-outline'})
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
	.background-sidebar{
		background: #1e272e;
	}
	.v-list-item__title{
		font-weight: normal !important;
		font-size: 1rem !important;
	}
</style>
