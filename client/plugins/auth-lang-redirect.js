export default ({app}) => {
    const redirect = app.$auth.$storage.options.redirect;
    for (const key in redirect) {
        // if (app.i18n.locale === 'tr')
        //     redirect[key] =  '/' + redirect[key]
        // else
            redirect[key] = '/' + app.i18n.locale + redirect[key]
    }
    // console.log(redirect)
    app.$auth.$storage.options.redirect = redirect
}