export default async ({$gates, redirect, store}) => {
  $gates.setRoles(store.$auth.user.hidden_role);

  if (!$gates.hasRole('admin')) {
    return redirect('/panel')
  }

}
