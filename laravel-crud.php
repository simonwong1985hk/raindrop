# Create Update
<form @isset($user) action="{{ route('users.update', $user) }}" @else action="{{ route('users.store') }}" @endisset method="POST">
  @csrf
  @isset($user) @method('PUT') @endisset
  <div class="row">
    <div class="form-group col-md-4">
      <input type="text" name="name" placeholder="name" @isset($user) value="{{ $user->name }}" @endisset class="form-control" />
    </div>
    <div class="form-group col-md-6">
      <input type="email" name="email" placeholder="email" @isset($user) value="{{ $user->email }}" @endisset class="form-control" />
    </div>
    <div class="form-group col-md-2">
      <button type="submit" class="btn btn-success form-control">@isset($user) Update @else Create @endisset </button>
    </div>
  </div>
</form>
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

# Read
<div class="mb-4">
  <form class="form-inline" action="{{ route('users.index') }}" method="GET">
    <div class="form-group">
      <input type="text" name="query" placeholder="name,email" class="form-control" />
    </div>
    <button type="submit" class="btn btn-primary ml-2">Search</button>
  </form>
</div>
@isset($users)
  <table class="table table-hover">
    <thead>
      <tr>
        <th>Name</th>
        <th>Email</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($users as $user)
      <tr>
        <td>{{ $user->id }}</td>
        <td>{{ $user->name }}</td>
        <td>{{ $user->email }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <div class="d-flex justify-content-center">
    {{ $users->links() }}
  </div>
@endisset

# Update
<a href="{{ route('users.edit', $user) }}" class="btn btn-outline-info">Update</a>

# Delete
<form action="{{ route('users.destroy', $user) }}" method="POST">
  @csrf
  @method('DELETE')
  <button type="submit" class="btn btn-outline-danger">Delete</button>
</form>

php artisan make:controller UserController --model=User -r

# routers/web.php
Route::resource('users', 'UserController');

# app/Http/Controllers/UserController.php
class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('query')) {
            $query = $request->input('query');

            $users = User::search($query)->orderBy('id', 'desc')->paginate(10);

            return view('users.index', compact('users', 'query'));
        } else {
            $users = User::orderBy('id', 'desc')->paginate(10);

            return view('users.index', compact('users'));
        }
    }

    public function create()
    {
        return redirect()->route('users.index');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|unique:users|email',
        ]);

        User::create($validatedData);

        return back();
    }

    public function show(User $user)
    {
        return redirect()->route('users.index');
    }

    public function edit(User $user)
    {
        return view('users.index', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|unique:users|email',
        ]);

        User::update($validatedData);

        return redirect()->route('users.index');
    }
    
    public function destroy(User $user)
    {
        $user->delete();
        
        return back();
    }
}
