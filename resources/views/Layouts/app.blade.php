<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SpaceShare')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background-color: black;
            color: white;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        main {
            flex: 1; 
        }
        .btn-primary {
            background-color: red;
            border-color: red;
        }
        .btn-primary:hover {
            background-color: darkred;
            border-color: darkred;
        }
        .btn-outline-primary {
            color: red;
            border-color: red;
        }
        .btn-outline-primary:hover {
            background-color: red;
            color: white;
        }
        .card {
            background-color: #222;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark" style="box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
            <div class="container">
                <a class="navbar-brand" href="#">
                    <img src="{{ asset('/storage/images/') }}/spacelogo.svg" alt="SpaceShare Logo" height="50">
                </a>
                <div class="collapse navbar-collapse">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link text-light hover-effect" href="{{ url('/') }}">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-light hover-effect" href="{{ url('/profile') }}">Profile</a>
                        </li>
                    </ul>
                    <a class="nav-link text-light hover-effect me-4" href="{{ url('/+') }}">
                        <img src="{{ asset('/storage/images/') }}/add.jpg" alt="add icon" height="20">
                    </a>
                    <a class="btn btn-primary me-2" href="{{ url('/login') }}">Login</a>
                    <a class="btn btn-outline-primary" href="{{ url('/createaccount') }}">Sign Up</a>
                </div>
            </div>
</nav>
    </header>

    <!-- Main Content -->
    <main class="container my-4">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-center text-white py-3">
        <p>&copy; {{ date('Y') }} SpaceShare. All rights reserved.</p>
    </footer>

    <script>
        function showLoginModal() {
            var loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
            loginModal.show();
        }
    </script>
</body>
</html>


