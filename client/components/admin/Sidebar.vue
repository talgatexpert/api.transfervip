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
      logout_data: {path:  '/' + this.$i18n.locale + '/panel/logout', title: this.$t('panel.menu.logout'), icon: 'mdi-logout'},
      mini: false,
    }
  },




  computed: {

    items(){
      const role =  this.$auth.user.hidden_role ?? this.$auth.user?.user?.hidden_role ?? 'client'
      let items = [];


      items.push({path:  '/' + this.$i18n.locale + '/panel/', title: this.$t('panel.dashboard'), icon: 'mdi-home', exact: true})
      items.push({path:  '/' + this.$i18n.locale + '/panel/account', title: this.$t('panel.menu.account'), icon: 'mdi-account'})
      items.push({path:  '/' + this.$i18n.locale + '/panel/bookings', title: this.$t('panel.menu.bookings'), icon: 'mdi-bus'})

      if (role === 'client') {
        items.push({path:  '/' + this.$i18n.locale + '/panel/trips', title: 'Seyahatler', icon: 'mdi-arrow-bottom-right'})
      }
      if (role === 'super_admin') {
        items.push({path:  '/' + this.$i18n.locale + '/panel/cities', title: this.$t('panel.menu.cities'), icon: 'mdi-city'})
        items.push({path:  '/' + this.$i18n.locale + '/panel/cars', title: this.$t('panel.menu.cars'), icon: 'mdi-car'})
        items.push({path:  '/' + this.$i18n.locale + '/panel/companies', title: this.$t('panel.menu.companies'), icon: 'mdi-account-multiple'})
        items.push({path:  '/' + this.$i18n.locale + '/panel/clients', title: this.$t('panel.menu.clients'), icon: 'mdi-account-group-outline'})
        items.push({path:  '/' + this.$i18n.locale + '/panel/transfers', title: this.$t('panel.menu.transfers'), icon: 'mdi-car-outline'})
        items.push({path:  '/' + this.$i18n.locale + '/panel/users', title: this.$t('panel.menu.users'), icon: 'mdi-account-outline'})
	      items.push({path:  '/' + this.$i18n.locale + '/panel/settings', title: this.$t('panel.menu.settings'), icon: 'mdi-account-settings-outline'})

      }
      if (role === 'admin') {
        items.push({path:  '/' + this.$i18n.locale + '/panel/cities', title: this.$t('panel.menu.cities'), icon: 'mdi-city'})
        items.push({path:  '/' + this.$i18n.locale + '/panel/clients', title: this.$t('panel.menu.clients'), icon: 'mdi-account-group-outline'})
        items.push({path:  '/' + this.$i18n.locale + '/panel/cars', title:this.$t('panel.menu.cars'), icon: 'mdi-car'})
        items.push({path:  '/' + this.$i18n.locale + '/panel/transfers', title: this.$t('panel.menu.transfers'), icon: 'mdi-car-outline'})
      }
      if (role === 'company') {
        items.push({path:  '/' + this.$i18n.locale + '/panel/cars', title: this.$t('panel.menu.cars'), icon: 'mdi-car'})
        items.push({path:  '/' + this.$i18n.locale + '/panel/transfers', title: this.$t('panel.menu.transfers'), icon: 'mdi-car-outline'})
        items.push({path:  '/' + this.$i18n.locale + '/panel/clients', title: this.$t('panel.menu.clients'), icon: 'mdi-account-group-outline'})
      }
	    if (role === 'travel') {
		    items.push({path:  '/' + this.$i18n.locale + '/panel/clients', title: this.$t('panel.menu.clients'), icon: 'mdi-account-group-outline'})
	    }

      return items;
    },
    username() {
      const name = this.$auth?.user?.name ?? this.$auth.user?.user?.name;
      return name || '';
    }

  },
  methods: {
     logout() {
          this.$emit('logout');
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
