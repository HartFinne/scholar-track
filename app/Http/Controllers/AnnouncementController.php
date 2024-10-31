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
        $users = User::with('education')->get();
        $announcements = Announcement::all();
        $worker = Auth::guard('staff')->user();

        return view('staff.home', compact('users', 'announcements', 'worker'));
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

        // Initialize the Guzzle client
        $client = new \GuzzleHttp\Client();

        // Track failed and successful notifications
        $failedSMS = [];
        $failedEmail = [];
        $successfulSMS = 0;
        $successfulEmail = 0;
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

                    $responseBody = $response->getBody()->getContents();
                    $decodedResponse = json_decode($responseBody, true);

                    Log::info('Movider SMS Response', ['response' => $decodedResponse]);
                    // If SMS sending was successful, increment success counter
                    if (isset($decodedResponse['phone_number_list']) && is_array($decodedResponse['phone_number_list']) && count($decodedResponse['phone_number_list']) > 0) {
                        $successfulSMS++;
                    } else {
                        $failedSMS[] = $user->scPhoneNum; // Track failed SMS
                    }
                } catch (\Exception $e) {
                    // Catch and handle any exception
                    $failedSMS[] = $user->scPhoneNum;
                    Log::info('Movider SMS Error', ['error' => $e->getMessage()]);
                }
            } else {
                // Send an email notification
                try {
                    $user->notify(new AnnouncementCreated($announcement));
                    $successfulEmail++;
                } catch (\Exception $e) {
                    // If email notification failed, add to failed list
                    $failedEmail[] = $user->email;
                    Log::info('Email Notification Error', ['error' => $e->getMessage()]);
                }
            }
        }

        // Prepare messages based on failed and successful notifications
        $smsErrorMessage = count($failedSMS) > 0 ? 'Failed to send SMS to: ' . implode(', ', $failedSMS) : '';
        $emailErrorMessage = count($failedEmail) > 0 ? 'Failed to send email to: ' . implode(', ', $failedEmail) : '';
        $successMessage = "Announcement created. Successfully sent $successfulSMS SMS and $successfulEmail email notifications.";

        // Combine error messages if both SMS and email errors occurred
        $errorMessage = trim($smsErrorMessage . ' ' . $emailErrorMessage);

        // Redirect back to the view with a success or failure message
        if ($errorMessage) {
            return redirect('staff/home')->with('failure', $errorMessage)->with('success', $successMessage);
        } else {
            return redirect('staff/home')->with('success', $successMessage);
        }
    }
}
