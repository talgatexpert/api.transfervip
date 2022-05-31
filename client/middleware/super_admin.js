export default async ({$gates, redirect, store}) => {
  $gates.setRoles(store.$auth.user.hidden_role);
  if (!$gates.hasRole('super_admin')) {
    return redirect('/panel')
  }

}
