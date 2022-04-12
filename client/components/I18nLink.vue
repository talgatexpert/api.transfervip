
<script>
export default {
  name: 'I18nLink',
  functional: true,
  render(h, { children, data, props, parent }) {
    // Get the current locale
    const locale = parent.$i18n.locale;
    const locales = parent.$i18n.locales;
    const path =
      typeof props.to === 'string'
        ? prependLocaleToPath(locale, props.to, locales)
        : { ...props.to, path: prependLocaleToPath(locale, props.to.path, locales) };
    return h(
      'nuxt-link',
      {
        ...data,
        props: {
          ...props,
          to: path
        }
      },
      children
    );
  }
};
function prependLocaleToPath(locale, path, locales) {
  let localizedPath = path;
  // if the URL doesn't start with the locale code
  if (locales.some(loc => localizedPath.startsWith(`/${loc.code}`))) {
    // prepend the URL
    localizedPath = `/${locale}${path}`;
  }
  return localizedPath;
}
</script>

<style scoped>

</style>
