import {API_URL, DOMAIN_URL} from "./routes/main";

export default {
    // Global page headers: https://go.nuxtjs.dev/config-head
    head: {
        title: "Lux Elit Travel Transfers",
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
            {rel: "stylesheet", href: "https://cdn.jsdelivr.net/npm/@mdi/font@6.x/css/materialdesignicons.min.css"},

        ],
    },
    target: 'server',


    // Global CSS: https://go.nuxtjs.dev/config-css
    css: ["@/assets/scss/main.scss"],

    // Plugins to run before rendering page: https://go.nuxtjs.dev/config-plugins
    plugins: [
        '~/plugins/vue-gates',
        '~/plugins/axios.js'
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
            routes.push({
                path: "/tr/transfer/:from/:to",
                components: {default: resolve(__dirname, 'pages/transfer')}
            })
            routes.push({
                path: "/ru/transfer/:from/:to",
                components: {default: resolve(__dirname, 'pages/transfer')}
            })
            routes.push({
                path: "/en/transfer/:from/:to",
                components: {default: resolve(__dirname, 'pages/transfer')}
            })
            routes.push([

                {
                    path: "/checkout",
                    components: {default: resolve(__dirname, 'pages/checkout')}
                },
                {
                    path: "/tr/checkout",
                    components: {default: resolve(__dirname, 'pages/checkout')}
                },
                {

                    path: "/en/checkout",
                    components: {default: resolve(__dirname, 'pages/checkout')}
                },
                {
                    path: "/ru/checkout",
                    components: {default: resolve(__dirname, 'pages/checkout')}
                },]
            )
        },

    },


    // Modules: https://go.nuxtjs.dev/config-modules
    modules: [
        // https://go.nuxtjs.dev/bootstrap
        "bootstrap-vue/nuxt",
        ['cookie-universal-nuxt'],

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
                        name_en: 'Turkish',
                    },
                    {
                        name: "English",
                        code: "en",
                        iso: "en",
                        file: "en",
                        icon: 'flag-en.svg',
                        name_en: 'English',


                    },
                    {
                        name: "Русский",
                        code: "ru",
                        iso: "ru",
                        file: "ru",
                        icon: 'flag-ru.svg',
                        name_en: 'Russian',


                    },
                ],
                langDir: "language/",
                defaultLocale: "tr",
                seo: true,
                vueI18n: {
                    fallbackLocale: "en",
                },
                strategy: 'prefix',
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
        baseURL: API_URL,
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

            laravelSanctum: {
                provider: 'laravel/sanctum',
                url: DOMAIN_URL,
                endpoints: {
                    user: {
                        url: API_URL + '/panel/user'
                    }
                },
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
        },
        plugins: ['@/plugins/auth.js'],
    },

    image: {
        domains: [
            DOMAIN_URL
        ],
        alias: {
            image_path: DOMAIN_URL
        }
    },

    // Build Configuration: https://go.nuxtjs.dev/config-build
    build: {
        babel: {
            compact: true
        }
    },
};
