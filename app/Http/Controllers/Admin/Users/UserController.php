<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function searchUsers(Request $request): array
    {
        $data = [];
        $search = $request->q;
        $limit = $request->limit;

        $users = User::role('user');
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
}
