<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>OtaComics</title>
        <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.2/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
        <link rel="stylesheet" href="{{asset('css/style.css')}}">
    </head>
    <body class="bg-dark">
        <div class="container-fluid shadow-lg header">
            <div class="container ">
                <div class="d-flex justify-content-between">
                    <h1 class="text-center"><a href="{{route('home')}}" class="h3 text-white text-decoration-none">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="50" height="50" xml:space="preserve" class="mb-1">
                            <g fill-rule="evenodd" clip-rule="evenodd">
                              <path fill="#1E62D6" d="M128 352c53.023 0 96-42.977 96-96h32c0 70.688-57.309 128-128 128S0 326.688 0 256c0-70.691 57.309-128 128-128 31.398 0 60.141 11.344 82.406 30.117l-.039.059c3.414 2.93 5.625 7.215 5.625 12.082 0 8.824-7.156 16-16 16-3.859 0-7.371-1.434-10.145-3.723l-.039.059C173.109 168.516 151.562 160 128 160c-53.023 0-96 42.977-96 96s42.977 96 96 96z"/>
                              <path fill="#FF0083" d="M352 384c-8.844 0-16-7.156-16-16s7.156-16 16-16c53.023 0 96-42.977 96-96s-42.977-96-96-96-96 42.977-96 96h-32c0-70.691 57.312-128 128-128s128 57.309 128 128c0 70.688-57.312 128-128 128zm-64-48c8.844 0 16 7.156 16 16s-7.156 16-16 16-16-7.156-16-16 7.156-16 16-16z"/>
                            </g>
                          </svg>
                        OtaComics
                    </a></h1>
                    <div class="d-flex align-items-center navigation">
                        @if (Auth::check())
                        <a href="{{route('account.profile')}}" class="text-white">
                                <img src="{{ asset('uploads/profile/'.Auth::user()->image) }}" class="rounded-circle me-2" width="40" height="40" style="object-fit: cover; border-radius: 50%;"  alt="">
                                My Account
                            </a>
                        @else
                            <a href="{{route('account.login')}}" class="text-white">Login</a>
                            <a href="{{route('account.register')}}" class="text-white ps-4">Register</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @yield('main')
        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
        @yield('script')

    </body>
</html>
