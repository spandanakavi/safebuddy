<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <title>Safe Buddy -Stay Geo connected with your Friends and Family ! </title>

    <style type="text/css">
        table {
            border-collapse: collapse;
            width: 100%;
            font: 62.5%/1.3 normal Helvetica, sans-serif;
            font-size: 14px;
        }
         
        td, th { 
           text-align: center; 
           border: 1px solid #ddd; 
           padding:.5em 5px;
           font-size: 1.2em;
        }
          th { 
            background-color:#f4f4f4;
            font-weight: normal;
          }
          
          tr.sos, tr.sos a{
              color:red;
          }
      body {
        font-family: arial, helvetica, sans-serif;
        color: #606060;
      }
      .title-bar {
        border-bottom: 1px solid #c0c0c0;
        width:100%;
        height:5%;
      }
      .map-canvas {
        width:100%;
        height:93%;
        margin-top: 5px;
      }
      .title {
        display: inline-block;
        width: 90%;
        font-size: 1.4em;
      }
      .reset-btn {
        display: inline-block;
        width: 9%;
        text-align: right;
      }
      .search-box {
        float:right;
        margin:10px 0;
       }
    </style>

    <!-- Styles -->
    <!-- <link href="/css/app.css" rel="stylesheet"> -->
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <!-- Scripts -->
    <script>
        window.Laravel = <?php
echo json_encode([
        'csrfToken' => csrf_token(),
]);
?>
    </script>
    <script src="http://52.66.141.118:8090/socket.io/socket.io.js"></script>
    <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyD8x7f9Yxj3ydTDR7XSeEHhaH4t-4P2a68&sensor=false">
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script>
        $(document).on("click", "#map", function(){
            $.ajax({
                type: "GET",
                url: "{{ URL::to('/admin/home') }}",
                data: $( this ).serialize(),
                success: function(data) {
                    $('#map-view').html(data);
                }
            }); 
        });
    </script>
</head>
<body>
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span>Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="{{ url('/') }}">
                    Safe Buddy
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    &nbsp;
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        <li><a href="{{ url('/login') }}">Login</a></li>
                        <li><a href="{{ url('/register') }}">Register</a></li>
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::user()->first_name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <a href="{{ url('/logout') }}"
                                        onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>

                                    <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')
   
    <!-- Scripts -->
    <script src="/js/app.js"></script>
    <footer class="col-md-12"> 
        <span> © <?php echo  date("Y"); ?> Safe Buddy. All rights reserved </span>
        <span style="float: right;"> Contact Us : safebuddy-cluster@aspiresysinc.onmicrosoft.com 
        </span>
    </footer> 
</body>
</html>
