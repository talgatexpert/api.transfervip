export const useLocale = (i18n) => {

    return i18n?.locales.filter(i => i18n.locale === i.iso)[0]
}
export const useLocaleCode = (i18n) => {
    return useLocale(i18n)['name_en'].toLowerCase();
}