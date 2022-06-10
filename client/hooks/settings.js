import {SETTINGS_URL} from "~/routes/main";
import {prepareSettings} from "~/store/setting";

export const useSetting = async ($axios, key) => {
    return await $axios.get(SETTINGS_URL + key).then(({data}) => {
        return prepareSettings({key}, data, null, false)
    }).catch(e => {
        console.log(e)
    })
}