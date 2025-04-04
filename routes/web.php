<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Route::get('/', function () {
//     return view('welcome');
// });

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
    $user = (object) [
        'full_name' => 'John Doe',
        'username' => 'johndoe',
        'email' => 'johndoe@example.com',
    ];

    return view('profile', compact('user'));
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

        // Optionally, save the file path to the database (if needed)
        // $user = auth()->user();
        // $user->cover_photo = $fileName;
        // $user->save();
    }

    // Redirect back to the profile page with a success message
    return redirect('/profile')->with('success', 'Cover photo uploaded successfully!');
});

Route::get('/+', function () {
    return view('ideas');
});


