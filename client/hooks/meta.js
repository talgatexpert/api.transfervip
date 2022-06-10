import {useLocale} from "~/hooks/locale";

export const useMeta = ({meta}) => {
    if (meta) {

        return [
            {
                hid: 'description',
                name: 'description',
                content: empty(meta.meta_description)
            },
            {
                hid: 'keyword',
                name: 'keyword',
                content: empty(meta.meta_keyword),
            }

        ]
    }
}
export const useTitle = ({meta, name}, prefix = "", delimiter = "|") => {
    if (name)
        if (prefix)
            return prefix + " " + delimiter + " "  + empty(meta.meta_title) + " " + delimiter + " " + name
        else
            return empty(meta.meta_title) + " " + delimiter + " " + name
    else
        return empty(meta.meta_title)
}
export const useTitlePanel = ({title, name, prefix}) => `${prefix} ${name} | ${empty(title)}`


export const getMetaData = async ($axios, i18n, prefix = "", panel = false) => {
    const locale = useLocale(i18n);
    const meta = await $axios.get('/meta').then(({data}) => {
        if (data.data) {
            return {meta: data.data.meta.filter(item => item.name === locale.name_en)[0], name: data.data?.name};
        }
        return [];
    }).catch(e => {
    })
    if (meta)
        return {
            meta: useMeta(meta) ?? [],
            title: panel === true
                ? useTitlePanel({
                    title: meta.meta.meta_title,
                    name: meta.name,
                    prefix: prefix + ' | ' + i18n.t('panel.control_panel')
                })
                : useTitle(meta, prefix, '|') ?? null
        }
}


const empty = (item) => {
    if (item) {
        return item.trim();
    }
    return ""
}