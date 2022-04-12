<template>
  <div>
      <header-admin :breadcrumbs-list="breadcrumbsList" :show-update="true" :page-title="pageTitle" :update="update"></header-admin>
    <v-form v-model="valid">

      <v-card class="rounded-0">
        <v-tabs
          v-model="tab"
          background-color="transparent"
          color="basil"
          grow
        >
          <v-tab

            v-for="(item, key) in items"
            :key="key"
          >
            {{ item.name }}
          </v-tab>
        </v-tabs>

        <v-tabs-items v-model="tab" class="mt-5">
          <v-tab-item
            v-for="(item, key) in items"
            :key="key"
          >
            <v-container>
              <v-card
                class="meta-information"
                color="basil"
                flat>
                <v-text-field
                  v-model="item.meta_title"
                  label="Meta title"
                  required>

                </v-text-field>
                <v-text-field
                  v-model="item.meta_description"
                  label="Meta description"
                  required
                ></v-text-field>
                <v-text-field
                  v-model="item.meta_keyword"
                  label="Meta keyword"
                  required
                ></v-text-field>

              </v-card>
            </v-container>
          </v-tab-item>
        </v-tabs-items>

        <v-container>
          <div class="d-lg-flex align-items-center">
            <v-file-input
              accept="image/*"
              small-chips
              label="Site logo"
              v-model="logo_image"
              class="pr-2"

              @change="previewImage"
            ></v-file-input>
            <v-img
              class="mx-auto"
              max-height="150"
              max-width="250"
              :src="logo_url"></v-img>
          </div>

        </v-container>
      </v-card>
    </v-form>
  </div>
</template>

<script>
import Breadcrumbs from "../../components/Breadcrumbs";
import MetaData from "../../components/admin/MetaData";
import HeaderAdmin from "../../components/admin/HeaderAdmin";

export default {
  name: "Settings",
  layout: "admin",
  components: {HeaderAdmin, Breadcrumbs, MetaData},

  data: () => ({
    valid: false,
    tab: null,
    title: '',
    items: [
      {
        name: 'Turkish',
        meta_description: '',
        meta_title: '',
        meta_keyword: '',
      },
      {
        name: 'Russian',
        meta_description: '',
        meta_title: '',
        meta_keyword: '',
      },
      {
        name: 'English',
        meta_description: '',
        meta_title: '',
        meta_keyword: '',
      },
    ],
    firstname: '',
    lastname: '',
    nameRules: [
      v => !!v || 'Name is required',
      v => v.length <= 10 || 'Name must be less than 10 characters',
    ],
    email: '',
    emailRules: [
      v => !!v || 'E-mail is required',
      v => /.+@.+/.test(v) || 'E-mail must be valid',
    ],
    breadcrumbsList: [
      {
        text: 'Dashboard',
        disabled: false,
        href: '/panel',
      },
      {
        text: 'Settings',
        disabled: true,
        href: '/settings',
      },
    ],
    logo_url: null,
    logo_image: [],
    pageTitle: 'Edit system settings',
  }),

  middleware: ['admin', 'super_admin'],


  async asyncData({app}) {
    await app.store.dispatch('setting/GET_META')
    await app.store.dispatch('setting/GET_LOGO')
    return {
      items: JSON.parse(JSON.stringify(await app.store.getters['setting/GET_META'])),
      logo_url: await app.store.getters['setting/GET_LOGO']
    };
  },

  methods: {
    update() {
      this.$store.dispatch('setting/SET_META', this.$data.items)
    },

    previewImage() {
      this.logo_url = URL.createObjectURL(this.logo_image)
      this.$store.dispatch('setting/UPLOAD_IMAGE', {
        key: 'logo',
        image: this.logo_image,
      })
    },
  }
}
</script>

<style scoped>

</style>
