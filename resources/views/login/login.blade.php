{{-- @extends('layouts.app') --}}

{{-- @section('content') --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>G Inversiones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('./css/login.css')}}">
    <link rel="icon" href="/img/logo.png" type="image/x-icon">
    <link rel="shortcut icon" href="/img/logo.png" type="image/x-icon">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid login-bg d-flex align-items-center justify-content-center">
        <div class="row col-lg-3 col-md-4 col-sm-11">
            <div class="form col-12" data-aos="zoom-in-up">
                <form method="POST" action="{{ route('login.post') }}">
                    @csrf
                    <div class="tab">
                        <img src="{{ asset('./img/logo.png')}}" class="img-fluid" alt="">
                        <p>Inicio de Sesión</p>
                        @if(session('password_enviado'))
                        <div class="alert alert-success text-start" role="alert">
                            {{ session('password_enviado') }}
                        </div>
                        @endif
                        <div class="mb-3">
                            <input type="email" class="form-control" name="email" value="{{ old('email') }}" required autocomplete="email" id="email" placeholder="Usuario">
                        </div>
                        <div class="input-group input-group-flat">
                            <input type="password" class="form-control" placeholder="Contraseña" name="password" id="password" required autocomplete="current-password" autocomplete="off">
                            <span class="input-group-text">
                                <a href="#" class="link-secondary" id="togglePassword">
                                    <img src="img/mostrar-password.png" alt="Mostrar contraseña" id="togglePasswordIcon">
                                </a>
                            </span>
                        </div>
                        <div class="my-2">
                            <a href="{{ route('reset.password') }}" class="mt-3">Olvide mi contraseña</a>
                        </div>
                        <div class="col-auto mt-3">
                            <button type="submit" class="btn btn-primary mb-3">Ingresar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init(
            {
                duration: 1000 // Cambia 800 a la duración deseada en milisegundos
            }
        );
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.min.js"></script>

    <script>
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');
    const togglePasswordIcon = document.querySelector('#togglePasswordIcon');

    togglePassword.addEventListener('click', function (e) {
        e.preventDefault();

        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);

        if (type === 'password') {
            togglePasswordIcon.setAttribute('src', 'img/mostrar-password.png');
            togglePasswordIcon.setAttribute('alt', 'Mostrar contraseña');
            togglePassword.setAttribute('aria-label', 'Mostrar contraseña');
            togglePassword.setAttribute('data-bs-original-title', 'Mostrar contraseña');
        } else {
            togglePasswordIcon.setAttribute('src', 'img/ocultar-password.png');
            togglePasswordIcon.setAttribute('alt', 'Ocultar contraseña');
            togglePassword.setAttribute('aria-label', 'Ocultar contraseña');
            togglePassword.setAttribute('data-bs-original-title', 'Ocultar contraseña');
        }
    });

</script>
    
</body>
</html>
{{-- <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> --}}
{{-- @endsection --}}
