<?php

namespace App\Http\Controllers\API\Notifications;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * List Notifications
     */
    public function index(Request $request)
    {
        $request->validate([
            'page' => ['integer'],
            'limit' => ['integer'],
        ]);

        $user = User::find($request->user()->id);

        $notifications = $user->notifications()->paginate($request->limit);
        $results = [];

        foreach ($notifications as $item) {
            $results[] = [
                "id" => $item->id,
                "type" => $item->type
            ];
        }

        return response()->json([
            "message" => "Notifications sucessfully retrieved",
            "data" => $results
        ], 200);
    }
}
