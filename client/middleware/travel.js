export default async ({$gates, redirect}) => {
  $gates.setRoles(store.$auth.user.hidden_role);

  if (!$gates.hasRole('travel')) {
    return redirect('/panel')
  }

}
