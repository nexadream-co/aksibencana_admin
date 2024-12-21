<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Models\BranchOffice;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $data = User::role(['user', 'courier'])->orderBy('name', 'asc')->get();

        return view('pages.users.index', compact('data'));
    }

    public function searchUsers(Request $request): array
    {
        $data = [];
        $search = $request->q;
        $limit = $request->limit;

        $users = User::role(['user']);
        if ($search) {
            foreach (explode(",", $search) as $key => $item) {
                if ($key == 0) {
                    $users->where('name', 'LIKE', '%' . $item . '%');
                } else {
                    $users->orWhere('name', 'LIKE', '%' . $item . '%');
                }
            }
        }

        $users = $users->paginate($limit ?? 10);
        foreach ($users as $item) {
            $data[] = [
                "id" => $item->id,
                "value" => $item->name
            ];
        }

        return $data;
    }

    public function searchCouriers(Request $request): array
    {
        $data = [];
        $search = $request->q;
        $limit = $request->limit;

        $users = User::role(['courier']);
        if ($search) {
            foreach (explode(",", $search) as $key => $item) {
                if ($key == 0) {
                    $users->where('name', 'LIKE', '%' . $item . '%');
                } else {
                    $users->orWhere('name', 'LIKE', '%' . $item . '%');
                }
            }
        }

        $users = $users->paginate($limit ?? 10);
        foreach ($users as $item) {
            $data[] = [
                "id" => $item->id,
                "value" => $item->name
            ];
        }

        return $data;
    }

    public function profile()
    {
        return view('pages.users.profile');
    }

    public function profileUpdate(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string'],
        ]);

        $request->user()->name = $request->name;
        $request->user()->save();

        session()->flash('success', 'Profile successfully updated');

        return redirect()->back();
    }

    public function profileChangePassword(Request $request)
    {
        $request->validate([
            'password' => ['required', 'string', 'confirmed'],
        ]);

        $user = $request->user();

        $user->password = Hash::make($request->password);
        $user->save();

        session()->flash('success', 'Password successfully updated');

        return redirect()->back();
    }

    public function create(Request $request)
    {
        $roles = Role::orderBy('name', 'asc')->get();
        $branch_offices = BranchOffice::orderBy('name', 'asc')->get();
        return view('pages.users.create', compact('roles', 'branch_offices'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'unique:users,email'],
            'role_id' => ['required', 'string'],
            'name' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        DB::beginTransaction();

        $role = Role::find($request->role_id);

        if ($role->name != 'user') {
            $request->validate([
                'branch_office_id' => ['required', 'string'],
            ]);
        }

        $user = new User();

        $user->branch_office_id = $request->branch_office_id;
        $user->email = $request->email;
        $user->name = $request->name;
        $user->password = Hash::make($request->password);
        $user->save();

        $user->assignRole(@$role->name ?? 'user');

        DB::commit();

        session()->flash('success', 'User successfully created');

        return redirect()->route('users');
    }

    public function resetPassword($id)
    {
        $user = User::find($id);

        if (!$user) return abort(404);

        $random_string = str()->random();

        $user->password = Hash::make($random_string);
        $user->save();

        session()->flash('success', 'User password successfully reset to ' . $random_string);

        return redirect()->route('users');
    }

    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) return abort(404);

        $user->delete();

        session()->flash('success', 'User successfully deleted');

        return redirect()->route('users');
    }
}
