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

    // Update the idea in the database (dummy logic for now)
    // In a real application, you would fetch the idea from the database and update it
    // Example:
    // $idea = Idea::findOrFail($id);
    // $idea->update($request->only('title', 'description'));

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


Route::post('/login', [LoginController::class, 'login'])->name('login');

