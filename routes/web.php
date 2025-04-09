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
    // Dummy data for ideas
    $ideas = [
        1 => [
            'id' => 1,
            'title' => 'Revolutionizing Renewable Energy',
            'description' => 'A groundbreaking idea to harness solar and wind energy more efficiently using AI-driven optimization algorithms.',
            'likes' => 45,
            'comments' => [
                ['author' => 'John Doe', 'content' => 'This is a fantastic idea!'],
                ['author' => 'Jane Smith', 'content' => 'I think this could really work.'],
            ],
        ],
        2 => [
            'id' => 2,
            'title' => 'Smart Agriculture with IoT',
            'description' => 'Leveraging IoT devices to monitor soil health, weather conditions, and crop growth in real-time.',
            'likes' => 32,
            'comments' => [
                ['author' => 'Mark Lee', 'content' => 'This is a game-changer for farmers!'],
                ['author' => 'Sarah Connor', 'content' => 'Can this work in arid regions?'],
            ],
        ],
        3 => [
            'id' => 3,
            'title' => 'AI-Powered Personal Health Assistant',
            'description' => 'An AI-driven app that tracks your daily activities, diet, and exercise routines to provide personalized health recommendations.',
            'likes' => 58,
            'comments' => [
                ['author' => 'Emily Davis', 'content' => 'This could really help people stay healthy!'],
                ['author' => 'Michael Brown', 'content' => 'Can it integrate with fitness trackers?'],
            ],
        ],
        4 => [
            'id' => 4,
            'title' => 'Virtual Reality for Education',
            'description' => 'Using VR technology to create immersive learning experiences for students.',
            'likes' => 74,
            'comments' => [
                ['author' => 'Chris Green', 'content' => 'This would make learning so much fun!'],
                ['author' => 'Anna White', 'content' => 'Can this be used for remote learning?'],
            ],
        ],
        5 => [
            'id' => 5,
            'title' => 'Eco-Friendly Packaging Solutions',
            'description' => 'Developing biodegradable and reusable packaging materials to replace single-use plastics.',
            'likes' => 39,
            'comments' => [
                ['author' => 'David Black', 'content' => 'This is a great step toward sustainability!'],
                ['author' => 'Sophia Blue', 'content' => 'How can we scale this globally?'],
            ],
        ],
    ];

    // Fetch the idea by ID
    $idea = $ideas[$id] ?? null;

    // If the idea doesn't exist, return a 404 page
    if (!$idea) {
        abort(404, 'Idea not found');
    }

    return view('viewidea', compact('idea'));
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
