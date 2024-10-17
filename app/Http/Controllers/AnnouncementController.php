<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Announcement;
use App\Notifications\AnnouncementCreated;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AnnouncementController extends Controller
{
    // //
    // public function viewAnnouncement()
    // {
    //     $users = User::all();
    //     return view('scholar.home', compact('users'));
    // }

    public function showHome()
    {
        if (Auth::guard('staff')->check()) {

            $users = User::all();
            $announcements = Announcement::all();

            return view('staff.home', compact('users', 'announcements'));
        }

        return redirect()->route('login');
    }

    public function storeAnnouncemnt(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string'],
            'description' => ['required', 'string']
        ]);

        // dd($request->all());

        // Create the announcement in the database
        $announcement = Announcement::create([
            'title' => $request->title,
            'description' => $request->description,
        ]);


        // Check if "all" is selected or specific users are selected
        if (in_array('all', $request->recipients)) {
            // Send notification to all users

            $users = User::all();
            foreach ($users as $user) {
                $user->notify(new AnnouncementCreated($announcement));
                Log::info('Sending email to: ' . $user->scEmail);
            }
        } else {
            // Send notification to selected users
            $selectedUsers = User::whereIn('id', $request->recipients)->get(); // Fetch only selected users
            foreach ($selectedUsers as $user) {
                $user->notify(new AnnouncementCreated($announcement));
                Log::info('Sending email to: ' . $user->scEmail);
            }
        }

        // Redirect back to the view
        return redirect('staff/home');
    }
}
