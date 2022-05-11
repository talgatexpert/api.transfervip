export default function ({app, redirect, $auth}) {
  if (app.$auth.loggedIn) {
    return redirect(app.localePath({path: '/panel'}))
  }
}
