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
        $users = User::all();
        $announcements = Announcement::all();

        return view('staff.home', compact('users', 'announcements'));
    }

    // storeannouncement
    public function storeAnnouncement(Request $request)
    {
        $worker = Auth::guard('staff')->user();

        $request->validate([
            'title' => ['required', 'string'],
            'description' => ['required', 'string']
        ]);


        // Create the announcement in the database
        $announcement = Announcement::create([
            'title' => $request->title,
            'description' => $request->description,
            'author' => $worker->name,
            'recipients' => json_encode($request->recipients),
        ]);


        // Prepare the API key and secret from the .env file
        $api_key = env('MOVIDER_API_KEY');
        $api_secret = env('MOVIDER_API_SECRET');

        $users = [];
        if (in_array('all', $request->recipients)) {
            $users = User::all(); // Select all users
        } else {
            $users = User::whereIn('caseCode', $request->recipients)->get(); // Select only the chosen users
        }

        // dd($request->recipients);

        // Initialize the Guzzle client
        $client = new \GuzzleHttp\Client();

        // Track failed SMS and failed email notifications
        $failedSMS = [];
        $failedEmail = [];
        $message = $request->description;

        foreach ($users as $user) {
            if ($user->notification_preference === 'sms') {
                // Send the SMS using the Movider API
                try {
                    $response = $client->post('https://api.movider.co/v1/sms', [
                        'form_params' => [
                            'api_key' => $api_key,
                            'api_secret' => $api_secret,
                            'to' => $user->scPhoneNum,
                            'text' => $message,
                        ],
                    ]);

                    // dd($response);

                    // Get the full response body
                    $responseBody = $response->getBody()->getContents();
                    $decodedResponse = json_decode($responseBody, true);


                    Log::info('Movider SMS Response', ['response' => $decodedResponse]);
                    // If SMS sending failed, add it to the failed list
                    if (!isset($decodedResponse['phone_number_list']) || count($decodedResponse['phone_number_list']) == 0) {
                        $failedSMS[] = $user->scPhoneNum; // Track failed SMS
                    }
                } catch (\Exception $e) {
                    // Catch and handle any exception
                    $failedSMS[] = $user->scPhoneNum;
                    Log::info('Movider SMS Response', ['response' => $failedSMS]);
                }
            } else {
                // Send an email notification
                try {
                    $user->notify(new AnnouncementCreated($announcement));
                    // dd($user);
                } catch (\Exception $e) {
                    // If email notification failed, add to failed list
                    $failedEmail[] = $user->email;
                    // dd($failedEmail[]);
                }
            }
        }

        // Prepare messages based on failed notifications
        $smsErrorMessage = count($failedSMS) > 0 ? 'Some SMS failed to send to: ' . implode(', ', $failedSMS) : '';
        $emailErrorMessage = count($failedEmail) > 0 ? 'Some emails failed to send to: ' . implode(', ', $failedEmail) : '';

        // Combine error messages
        $errorMessage = trim($smsErrorMessage . ' ' . $emailErrorMessage);

        // Redirect back to the view with a success or failure message
        if ($errorMessage) {
            return redirect('staff/home')->with('error', $errorMessage);
        } else {
            return redirect('staff/home')->with('success', 'Announcement created and notifications sent successfully!');
        }
    }
}
