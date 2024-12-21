<?php

namespace App\Http\Controllers\API\Notifications;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Volunteer;
use App\Notifications\TestNotification;
use App\Notifications\VolunteerStatusUpdated;
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
                "title" => @$item->data['title'],
                "description" => @$item->data['body'],
                "data" => @$item->data['data'],
                "type" => @$item->data['type'],
                "created_at" => @$item->created_at->format('Y-m-d h:i:s')
            ];
        }

        return response()->json([
            "message" => "Notifications sucessfully retrieved",
            "data" => $results
        ], 200);
    }

    /**
     * Test Notification
     */
    public function testNotification(Request $request)
    {
        $request->validate([
            'user_id' => ['integer', 'nullable'],
        ]);

        $user = User::find($request->user_id ?? $request->user()->id);

        if (!@$user) {
            return response()->json([
                "message" => "User not found",
            ], 404);
        }

        $user->notify(new TestNotification());

        return response()->json([
            "message" => "Notification sucessfully sent",
        ], 200);
    }

    /**
     * Test Volunteer Notification
     */
    public function testVolunteeerNotification(Request $request)
    {
        $request->validate([
            'user_id' => ['integer', 'nullable'],
            'volunteer_id' => ['integer', 'required'],
        ]);

        $user = User::find($request->user_id ?? $request->user()->id);

        if (!@$user) {
            return response()->json([
                "message" => "User not found",
            ], 404);
        }

        $user = User::find($request->user_id ?? $request->user()->id);
        @$user->notify(new VolunteerStatusUpdated(Volunteer::find($request->volunteer_id)));

        return response()->json([
            "message" => "Notification volunteer sucessfully sent",
        ], 200);
    }
}
