<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

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
}
