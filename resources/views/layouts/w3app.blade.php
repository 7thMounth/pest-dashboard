<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Pest Dashboard') }}</title>

    <!-- W3.CSS -->
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/5/w3.css">
    
    <!-- Google Fonts - Raleway -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <style>
        body, h1, h2, h3, h4, h5, h6 {
            font-family: 'Raleway', sans-serif;
        }
        .w3-sidebar {
            z-index: 3;
            width: 250px;
            top: 43px;
            bottom: 0;
            height: inherit;
        }
        .w3-main {
            margin-left: 250px;
        }
        @media (max-width: 992px) {
            .w3-sidebar {
                display: none;
                width: 100% !important;
            }
            .w3-main {
                margin-left: 0 !important;
            }
        }
        .w3-theme {color:#fff !important; background-color:#4CAF50 !important;}
        .w3-theme-l5 {color:#000 !important; background-color:#f1f8e9 !important;}
        .w3-theme-l4 {color:#000 !important; background-color:#dcedc8 !important;}
        .w3-theme-l3 {color:#000 !important; background-color:#b9de9e !important;}
        .w3-theme-l2 {color:#000 !important; background-color:#a5d67b !important;}
        .w3-theme-l1 {color:#000 !important; background-color:#8bc34a !important;}
        .w3-theme-d1 {color:#fff !important; background-color:#689f38 !important;}
        .w3-theme-d2 {color:#fff !important; background-color:#5a9216 !important;}
        .w3-theme-d3 {color:#fff !important; background-color:#4b830d !important;}
        .w3-theme-d4 {color:#fff !important; background-color:#3e6b0a !important;}
        .w3-theme-d5 {color:#fff !important; background-color:#2e4d07 !important;}
        .w3-text-theme {color:#4CAF50 !important;}
        .w3-hover-theme:hover {color:#fff !important; background-color:#4CAF50 !important;}
    </style>
    
    @stack('styles')
</head>
<body class="w3-light-grey">
    <!-- Navbar -->
    <div class="w3-top">
        <div class="w3-bar w3-theme w3-card w3-left-align w3-large">
            <a href="{{ url('/') }}" class="w3-bar-item w3-button w3-padding-large w3-theme-d1">
                <i class="fa fa-home w3-margin-right"></i>Pest Dashboard
            </a>
            <a href="{{ route('test-results.index') }}" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white {{ request()->is('test-results*') ? 'w3-white w3-text-theme' : '' }}" title="Test Results">
                <i class="fa fa-tasks w3-margin-right"></i>Test Results
            </a>
        </div>
    </div>

    <!-- Main content -->
    <div class="w3-main" style="margin-top:43px; margin-left: 0;">
        <div class="w3-container w3-padding-16">
            @if(session('success'))
                <div class="w3-panel w3-green w3-display-container w3-card-4">
                    <span onclick="this.parentElement.style.display='none'"
                    class="w3-button w3-green w3-large w3-display-topright">&times;</span>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if($errors->any())
                <div class="w3-panel w3-red w3-display-container w3-card-4">
                    <span onclick="this.parentElement.style.display='none'"
                    class="w3-button w3-red w3-large w3-display-topright">&times;</span>
                    <h3>Error!</h3>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script>
        // Sidebar related functions removed
    </script>
    
    @stack('scripts')
</body>
</html>
