<!DOCTYPE html>
<html lang="es">
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
                <form method="POST" action="{{ route('enviar-password-reset') }}">
                    @csrf
                    <div class="tab">
                        <img src="{{ asset('./img/logo.png')}}" class="img-fluid" alt="">
                        <p>¿Olvido su contraseña?</p>
                        <div class="mb-3">
                            <div class="text-start mb-3">
                                <label>Digite su correo electrónico registrado como inversionista</label>
                            </div>
                            <input type="email" class="form-control" name="email" value="{{ old('email') }}" required id="email" placeholder="Correo electrónico">
                            @error('usuario_invalido')
                            <div class="text-start mt-3">
                                <small class="text-danger">
                                    {{ $message }}
                                </small>
                            </div>
                            @enderror
                        </div>
                        <div class="col-auto mt-3">
                            <button type="submit" class="btn btn-primary mb-3">Enviar contraseña</button>
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
    
</body>
</html>
