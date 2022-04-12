export default {
  // Global page headers: https://go.nuxtjs.dev/config-head
  head: {
    title: "Transfers",
    htmlAttrs: {
      lang: "en",
    },
    meta: [
      {charset: "utf-8"},
      {name: "viewport", content: "width=device-width, initial-scale=1"},
      {hid: "description", name: "description", content: ""},
      {name: "format-detection", content: "telephone=no"},
    ],
    link: [
      {rel: "icon", type: "image/x-icon", href: "/favicon.ico"},
      {rel: "stylesheet",  href: "https://cdn.jsdelivr.net/npm/@mdi/font@6.x/css/materialdesignicons.min.css"},

    ],
  },
  target: 'server',


  // Global CSS: https://go.nuxtjs.dev/config-css
  css: ["@/assets/scss/main.scss"],

  // Plugins to run before rendering page: https://go.nuxtjs.dev/config-plugins
  plugins: [
    '~/plugins/vue-gates',
  ],

  // Auto import components: https://go.nuxtjs.dev/config-components
  components: true,
  ssr: true,

  // Modules for dev and build (recommended): https://go.nuxtjs.dev/config-modules
  buildModules: [
    // https://go.nuxtjs.dev/typescript
    "@nuxt/typescript-build",
    '@nuxtjs/vuetify',
  ],

  router: {
    extendRoutes(routes, resolve) {
      routes.push({
        path: "/transfer/:from/:to",
        components: {default: resolve(__dirname, 'pages/transfer')}
      })
    },

  },



  // Modules: https://go.nuxtjs.dev/config-modules
  modules: [
    // https://go.nuxtjs.dev/bootstrap
    "bootstrap-vue/nuxt",
    [
      "@nuxtjs/i18n",
      {
        locales: [
          {
            name: "Türkçe",
            code: "tr",
            iso: "tr",
            file: "tr",
            icon: 'flag-tr.svg',
          },
          {
            name: "English",
            code: "en",
            iso: "en",
            file: "en",
            icon: 'flag-en.svg',

          },
          {
            name: "Русский",
            code: "ru",
            iso: "ru",
            file: "ru",
            icon: 'flag-ru.svg',

          },
        ],
        langDir: "language/",
        defaultLocale: "tr",
        vueI18n: {
          fallbackLocale: "en",
        },
        detectBrowserLanguage: {
          useCookie: true,
          alwaysRedirect: true
        }
      },
    ],
    '@nuxtjs/axios',
    '@nuxtjs/auth-next',
    '@nuxtjs/recaptcha',
  ],
  axios: {
    baseURL: 'http://localhost:8000/api',
    credentials: true,

  },
  recaptcha: {
    /* reCAPTCHA options */
    hideBadge: true, // Hide badge element (v3 & v2 via size=invisible)
    siteKey: '6Ld5g08fAAAAACAmwhg814OMyolq5clbSMxXE7nB',    // Site key for requests
    version: 2,     // Version
  },
  auth: {
    strategies: {

      local: {
        token: {
          property: 'token',
          global: true,
          required: true,
          type: 'Bearer'
        },
      },
      laravelSanctum: {
        provider: 'laravel/sanctum',
        url: 'http://localhost:8000',
        user: "api/user",
        token: {
          property: 'token',
          global: true,
          required: true,
          type: 'Bearer'
        },

      }
    },
    redirect: {
      home: '/panel/',
    }

  },

  image: {
    domains: [
      'localhost:8000'
    ],
    alias: {
      image_path: 'http://localhost:8000'
    }
  },

  // Build Configuration: https://go.nuxtjs.dev/config-build
  build: {},
};
