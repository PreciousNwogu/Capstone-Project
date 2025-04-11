<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

Route::get('/', function () {
    return view('index');
});

Route::get('/login', function () {
    return view('login');
});

Route::get('/createaccount', function () {
    return view('createaccount');
});

Route::get('/profile', function () {
    $userId = 1; // Dummy user ID for demonstration
    
    // Try to fetch user data from the API with a shorter timeout
    try {
        // Add a 5-second timeout to prevent long waits
        $response = Http::timeout(5)->get(url("api/users/{$userId}"));
        
        // Check if the request was successful
        if ($response->successful()) {
            $userData = json_decode($response->body());
            
            // If user data is returned as an array with data property, extract it
            if (isset($userData->data)) {
                $user = $userData->data;
            } else {
                $user = $userData;
            }
        } else {
            // If API request fails, fallback to dummy data
            $user = (object) [
                'full_name' => 'John Doe',
                'username' => 'johndoe',
                'email' => 'johndoe@example.com',
            ];
        }
    } catch (\Exception $e) {
        // Fallback to dummy data
        $user = (object) [
            'full_name' => 'John Doe',
            'email' => 'johndoe@example.com',
        ];
    }

    return view('profile', compact('user'));
});

// Updated profile update route with timeout handling
Route::post('/profile/update', function (Request $request) {
    $userId = 1; // Dummy user ID for demonstration
    
    // Validate the form data
    $validated = $request->validate([
        'full_name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'password' => 'nullable|string|min:8',
    ]);
    
    // Prepare the data to be sent to the API
    $updateData = [
        'full_name' => $validated['full_name'],
        'email' => $validated['email'],
    ];
    
    // Only include password if it's provided
    if (!empty($validated['password'])) {
        $updateData['password'] = $validated['password'];
    }
    
    // Define the API endpoint URL with absolute path
    $apiUrl = url("api/users/{$userId}");
    
    // For troubleshooting - update directly without API call
    // Just update the session data for demo purposes
    session(['user_updated' => $updateData]);
    return redirect('/profile')->with('success', 'Profile updated successfully! (API call bypassed)');
    
    // The code below is commented out to prevent timeouts during testing
    /*
    // Send update request to the API with a shorter timeout
    try {
        // Add a 5-second timeout to prevent long waits
        $response = Http::timeout(5)->put($apiUrl, $updateData);
        
        if ($response->successful()) {
            return redirect('/profile')->with('success', 'Profile updated successfully!');
        } else {
            // If PUT fails, try using POST with _method=PUT (Laravel method spoofing)
            $updateData['_method'] = 'PUT';
            $response = Http::timeout(5)->post($apiUrl, $updateData);
            
            if ($response->successful()) {
                return redirect('/profile')->with('success', 'Profile updated successfully!');
            } else {
                // Get more detailed error information
                $statusCode = $response->status();
                $errorBody = $response->body();
                return back()->withErrors([
                    'message' => "API Error (Status {$statusCode}): {$errorBody}"
                ])->withInput($request->except('password'));
            }
        }
    } catch (\Exception $e) {
        // More detailed exception information
        return back()->withErrors([
            'message' => 'Connection Error: ' . $e->getMessage()
        ])->withInput($request->except('password'));
    }
    */
});

Route::post('/profile/upload-cover', function (Request $request) {
    // Validate the uploaded file
    $request->validate([
        'cover_photo' => 'required|mimes:jpg,svg,pdf|max:2048', // Restrict file types and size
    ]);

    // Handle the file upload
    if ($request->hasFile('cover_photo')) {
        $file = $request->file('cover_photo');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $file->storeAs('public/cover_photos', $fileName); // Save the file in the storage directory
    }

    // Redirect back to the profile page with a success message
    return redirect('/profile')->with('success', 'Cover photo uploaded successfully!');
});

// Add API test route with timeout handling
Route::get('/test-api', function() {
    $userId = 1; // Test user ID
    $apiUrl = url("api/users/{$userId}");
    
    try {
        // Add a 5-second timeout to prevent long waits
        $response = Http::timeout(5)->get($apiUrl);
        
        return [
            'url' => $apiUrl,
            'status' => $response->status(),
            'body' => $response->json() ?: $response->body(),
            'connection' => 'successful'
        ];
    } catch (\Exception $e) {
        return [
            'url' => $apiUrl,
            'error' => $e->getMessage(),
            'connection' => 'failed'
        ];
    }
});

Route::get('/+', function () {
    return view('ideas');
});

Route::get('/idea/{id}', function ($id) {
    // We'll use the API endpoint to get the data, so we just need to pass the ID to the view
    return view('viewidea', ['id' => $id]);
});

Route::get('/edit-idea/{id}', function ($id) {
    // Fetch the idea by ID (dummy data for now)
    $idea = (object) [
        'id' => $id,
        'title' => 'Revolutionizing Renewable Energy',
        'description' => 'A groundbreaking idea to harness solar and wind energy more efficiently using AI-driven optimization algorithms.',
    ];

    return view('editidea', compact('idea'));
})->name('edit-idea');

// Route to handle the update request
Route::put('/update-idea/{id}', function ($id, Illuminate\Http\Request $request) {
    // Validate the request
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
    ]);

    return redirect('/')->with('success', 'Idea updated successfully!');
})->name('update-idea');

Route::delete('/delete-idea/{id}', function ($id) {
    // Logic to delete the idea
    return redirect('/')->with('success', 'Idea deleted successfully!');
})->name('delete-idea');

Route::delete('/delete-comment/{id}', function ($id) {
    // Logic to delete the comment
    return redirect('/')->with('success', 'Comment deleted successfully!');
})->name('delete-comment');


// Route::post('/login', [LoginController::class, 'login'])->name('login');