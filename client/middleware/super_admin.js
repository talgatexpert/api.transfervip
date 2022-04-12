export default async ({$gates, redirect}) => {
  $gates.setRoles(store.$auth.user.hidden_role);

  if (!$gates.hasRole('super_admin')) {
    return redirect('/panel')
  }

}
