<!DOCTYPE html>
<html lang="en">
<head>
    <meta lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <title>OFIMS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    @vite(['resources/sass/app.scss','resources/css/app.css', 'resources/js/app.js'])
</head>
<body style="background-color: #550000 ">
    <div class="container text-center mt-5 mb-3">
        <h1 class="fs-1 fw-bolder text-danger">ONLINE FIRE INCIDENT MAPPING SYSTEM</h1>
        <p class="fs-3 fw-bolder text-danger">FIRE ALERTNESS</p>
        <hr class="text-white">
    </div>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-lg">
          <div class="navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                @if (Auth::user()->usertype == 'Admin')
                    @if ($title == 'user')
                        <li id="user" class="nav-item bg-danger fw-bolder">
                            <a class="nav-link" href="{{ route('user.user') }}">User</a>
                        </li>
                    @else
                        <li id="user" class="nav-item">
                            <a class="nav-link" href="{{ route('user.user') }}">User</a>
                        </li>
                    @endif
                    @if ($title == 'devices')
                        <li id="devices" class="nav-item bg-danger fw-bolder">
                            <a class="nav-link" href="{{ route('user.devices') }}">Devices</a>
                        </li>
                    @else
                        <li id="devices" class="nav-item">
                            <a class="nav-link" href="{{ route('user.devices') }}">Devices</a>
                        </li>
                    @endif
                    @if ($title == 'alarm')
                        <li id="alarm" class="nav-item bg-danger fw-bolder">
                            <a class="nav-link" href="{{ route('user.alarm') }}">Alarm</a>
                        </li>
                    @else
                        <li id="alarm" class="nav-item">
                            <a class="nav-link" href="{{ route('user.alarm') }}">Alarm</a>
                        </li>
                    @endif
                @elseif (Auth::user()->usertype == 'Firefighter')
                    @if ($title == 'alarm')
                        <li id="alarm" class="nav-item bg-danger fw-bolder">
                            <a class="nav-link" href="{{ route('user.alarm') }}">Alarm</a>
                        </li>
                    @else
                        <li id="alarm" class="nav-item">
                            <a class="nav-link" href="{{ route('user.alarm') }}">Alarm</a>
                        </li>
                    @endif
                @elseif (Auth::user()->usertype == 'Owner')
                    @if ($title == 'devices')
                            <li id="devices" class="nav-item bg-danger fw-bolder">
                                <a class="nav-link" href="{{ route('user.devices') }}">Devices</a>
                            </li>
                        @else
                            <li id="devices" class="nav-item">
                                <a class="nav-link" href="{{ route('user.devices') }}">Devices</a>
                            </li>
                        @endif
                @endif
                <li id="alarm" class="nav-item">
                    <a class="nav-link" href="{{ route('user.logout') }}">Logout</a>
                </li>
            </ul>
              <button class="btn btn-outline-danger" type="button"  data-toggle="modal" data-target="#form">
                <i class="fa-regular fa-bell"></i>
              </button>
          </div>
        </div>
      </nav>
      <x-modal-notif>
        
      </x-modal-notif>
    <section class="home">
        {{$slot}}
    </section>
</body>
</html>