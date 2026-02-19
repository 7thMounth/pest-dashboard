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
        .w3-text-truncate {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            display: inline-block;
        }
    </style>
    
    @stack('styles')
</head>
<body class="w3-light-grey">

<!-- Top container -->
<div class="w3-bar w3-top w3-black w3-large" style="z-index:4">
  <button class="w3-bar-item w3-button w3-hide-large w3-hover-none w3-hover-text-light-grey" onclick="w3_open();"><i class="fa fa-bars"></i>  Menu</button>
  <span class="w3-bar-item w3-right">Pest Dashboard</span>
</div>

<!-- Sidebar/menu -->
<nav class="w3-sidebar w3-collapse w3-white w3-animate-left" style="z-index:3;width:300px;" id="mySidebar"><br>
  <div class="w3-container w3-row">
    <div class="w3-col s4">
      <img src="https://www.w3schools.com/w3images/avatar2.png" class="w3-circle w3-margin-right" style="width:46px">
    </div>
    <div class="w3-col s8 w3-bar">
      <span>Welcome, <strong>Testers</strong></span><br>
    </div>
  </div>
  <hr>
  <div class="w3-container">
    <h5>Dashboard</h5>
  </div>
  <div class="w3-bar-block">
    <a href="#" class="w3-bar-item w3-button w3-padding-16 w3-hide-large w3-dark-grey w3-hover-black" onclick="w3_close()" title="close menu"><i class="fa fa-remove fa-fw"></i>  Close Menu</a>
    <a href="{{ route('test-results.index') }}" class="w3-bar-item w3-button w3-padding {{ request()->routeIs('test-results.index') ? 'w3-blue' : '' }}"><i class="fa fa-dashboard fa-fw"></i>  Overview</a>
    <a href="{{ route('test-results.bulk.create') }}" class="w3-bar-item w3-button w3-padding {{ request()->routeIs('test-results.bulk.create') ? 'w3-blue' : '' }}"><i class="fa fa-list fa-fw"></i>  Bulk Test</a>
  </div>
</nav>


<!-- Overlay effect when opening sidebar on small screens -->
<div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>

<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:300px;margin-top:43px;">

  <!-- Header -->
  <header class="w3-container" style="padding-top:22px">
    <h5><b><i class="fa fa-dashboard"></i> My Dashboard</b></h5>
  </header>

  @if(session('success'))
    <div class="w3-panel w3-green w3-display-container w3-card-4 w3-margin">
        <span onclick="this.parentElement.style.display='none'"
        class="w3-button w3-green w3-large w3-display-topright">&times;</span>
        <p>{{ session('success') }}</p>
    </div>
  @endif

  @yield('content')

  <!-- Footer -->
  <footer class="w3-container w3-padding-16 w3-light-grey">
    <h4></h4>
    <p>Powered by <a href="https://www.w3schools.com/w3css/default.asp" target="_blank">w3.css</a></p>
  </footer>

  <!-- End page content -->
</div>

<script>
// Get the Sidebar
var mySidebar = document.getElementById("mySidebar");

// Get the DIV with overlay effect
var overlayBg = document.getElementById("myOverlay");

// Toggle between showing and hiding the sidebar, and add overlay effect
function w3_open() {
  if (mySidebar.style.display === 'block') {
    mySidebar.style.display = 'none';
    overlayBg.style.display = "none";
  } else {
    mySidebar.style.display = 'block';
    overlayBg.style.display = "block";
  }
}

// Close the sidebar with the close button
function w3_close() {
  mySidebar.style.display = "none";
  overlayBg.style.display = "none";
}
</script>

@stack('scripts')

</body>
</html>
