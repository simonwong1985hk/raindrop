$users = App\User::paginate(15);

{{ $users->links() }}
