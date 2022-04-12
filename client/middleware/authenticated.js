export default function ({app, redirect, $auth}) {
  console.log($auth)
  if (app.$auth.loggedIn) {
    return redirect(app.localePath({path: '/panel'}))
  }
}
