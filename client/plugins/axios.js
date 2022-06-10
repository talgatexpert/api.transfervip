import {API_URL, DOMAIN_URL, SANCTUM_COOKIES} from "~/routes/main";
import axios from "axios";
import Cookies from "js-cookie";

export default async function ({$axios, app}, inject) {
    // const token = await axios.get(DOMAIN_URL + '/token').then(({data}) => {
    //     return data.csrf
    // }).catch(e => console.log(e))
    //
    // let csrf = await $axios.get(SANCTUM_COOKIES).then(response => {
    //     if (response.headers['set-cookie']) {
    //         const arr = response.headers['set-cookie'][0].split('=');
    //         return arr[1].split(';')[0];
    //     }
    //     return "";
    // })

    // if (csrf)
        // app.$cookies.set('X-CSRF-TOKEN', csrf);

    const api = function () {

        return $axios.create({
            headers: {
                common: {
                    'X-Language': app.i18n.locale,
                    // 'X-CSRF-TOKEN': app.$cookies.get('X-CSRF-TOKEN'),
                    // 'X-Requested-With': 'XMLHttpRequest'
                }
            },
            credential: true,
        })
    }


    // Inject to context as $api
    inject('axios', api())
    app.i18n.onLanguageSwitched = () => {
        api();
    };
}