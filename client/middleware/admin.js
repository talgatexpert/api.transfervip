export default async ({$gates, redirect}) => {
  if (!$gates.hasRole('admin')) {
    return redirect('/panel')
  }

}
