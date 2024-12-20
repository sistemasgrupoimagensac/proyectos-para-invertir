<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="/img/logo.png" type="image/x-icon">
    <link rel="shortcut icon" href="/img/logo.png" type="image/x-icon">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <link rel="stylesheet" href="{{asset('./css/inicio.css')}}">
    <title>G Inversiones</title>
</head>
<body>
    <div class="container-fluid seccion-1 d-none d-lg-block d-md-none d-sm-none d-xs-none ">
        <p class="text-center">CONECTANDO METAS CON OPORTUNIDADES | GENERA UNA RENTABILIDAD ANUAL DE HASTA 35% ANUAL</p>
    </div>
    <!--desktop-->
    <div class="container-fluid seccion-2 p-3 d-block d-none d-lg-block">
        <div class="row align-items-center justify-content-evenly">
            <div class="col-lg-2">
                <a href="{{route('inicio')}}">
                    <img class="img-fluid logo" src="img/logo.png" alt="">
                </a>
            </div>
            <div class="col-lg-6">
                <div class="col-12 inmuebles-mobil">
                    <div class="row align-items-evenly justify-content-center inmuebles-mobil">
                        <div class="col-4">
                            <a href="{{ route('inicio')}}" style="color:#01276A !important; text-decoration:none;" class="d-block">
                                <img src="img/vector/searchmobil.png" class="img-fluid mx-auto" alt="Casas">
                                <p class="mt-2 text-center"style="color:#01276A !important" >Buscar</p>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="{{ route('favoritos')}}" style="color:#01276A !important; text-decoration:none;" class="d-block">
                                <img src="img/vector/corazonazul.png" class="img-fluid mx-auto" alt="Departamento">
                                <p class="mt-2 text-center"style="color:#01276A" >Favoritos</p>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="{{ route('perfil')}}?co_tipo_garantia=3" style="color:#F59C1A !important; text-decoration:none;" class="d-block">
                                <img src="/img/vector/user-mobileamarillo.png" class="img-fluid mx-auto" alt="Local Comercial">
                                <p class="mt-2 text-center"style="color:#F59C1A" >Perfil</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row col-2 justify-content-center align-items-center m-0">
                <div class="container burger p-0">
                    <div class="row justify-content-center align-items-center rounded-pill border border-1 p-2 m-0">
                        <div class="row justify-content-center d-flex col-6 dropdown m-0 text-center">
                            <button class="btn dropdown-toggle" type="button" id="burgerMenu" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="/img/vector/menuboton.svg" alt="Burger Icon" style="width: 30px; height: 30px;">
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="burgerMenu">
                                <li><a class="dropdown-item" href="{{ route('inicio') }}" onclick="event.preventDefault(); document.getElementById('perfil-form').submit();">Inicio</a>
                                    <form id="perfil-form" action="{{ route('inicio') }}" method="GET" style="display: none;">
                                        @csrf
                                    </form>
                                </li>
                                <li><a class="dropdown-item" href="{{ route('perfil') }}" onclick="event.preventDefault(); document.getElementById('perfil-form').submit();">Perfil</a>
                                    <form id="perfil-form" action="{{ route('perfil') }}" method="GET" style="display: none;">
                                        @csrf
                                    </form>
                                </li>
                                <li><a class="dropdown-item" href="{{ route('favoritos') }}" onclick="event.preventDefault(); document.getElementById('favoritos-form').submit();">Mis Favoritos</a>
                                    <form id="favoritos-form" action="{{ route('favoritos') }}" method="GET" style="display: none;">
                                        @csrf
                                    </form>
                                </li>
                                <li><a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Cerrar Sesión</a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </div>
                        <div class="d-flex col-6 m-0 justify-content-center">
                            <img class="rounded-circle img-fluid" src="img/vector/perfil.png" style="height:3.5rem;" alt="profile">
                        </div>
                    </div>
                </div>
            </div>
            
            
        </div>
    </div>
    <!--mobil-->
    <section class="h-100 gradient-custom-2">
        <div class="container h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col col-lg-12 col-xl-12">
                    <div class="card">
                        <div class="rounded-top text-white d-flex flex-row" style="background-color: #01276A; height:200px;">
                            <div class="ms-4 mt-5 d-flex flex-column" style="width: 150px;">
                                <img src="./img/vector/usuario.png"
                                    alt="Generic placeholder image" class="img-fluid img-thumbnail mt-4 mb-2"
                                    style="width: 150px; z-index: 1">
                                    {{-- <button type="button" class="btn btn-outline-dark" 
                                    data-mdb-ripple-color="dark"
                                    style="z-index: 1;">Editar perfil</button> --}}
                            </div>
                            <div class="ms-3" style="margin-top: 130px;">
                                <h5>{{ strtoupper(Auth::user()->name) }}</h5>
                            </div>
                        </div>
                        <div class="p-4 text-black" style="background-color: #f8f9fa;">
                            <div class="d-flex justify-content-end text-center py-1">
                                <div>
                                {{-- <p class="mb-1 h5">253</p>
                                <p class="small text-muted mb-0">Photos</p> --}}
                                </div>
                                <div class="px-3">
                                {{-- <p class="mb-1 h5">1026</p>
                                <p class="small text-muted mb-0">Followers</p> --}}
                                </div>
                                <div>
                                    {{-- <p class="mb-1 h5">478</p>
                                    <p class="small text-muted mb-0">Following</p> --}}
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4 text-black">
                            <div class="mb-5">
                                <p class="lead fw-normal mb-1">Información</p>
                                <div class="p-4" style="background-color: #f8f9fa;">
                                    <p class="font-italic mb-1"><strong>Correo:</strong> {{ Auth::user()->email}}</p>
                                    <p class="font-italic mb-1"><strong>Celular:</strong> {{ Auth::user()->persona->nu_celular}}</p>
                                    <p class="font-italic mb-1"><strong>DNI:</strong> {{ Auth::user()->persona->nu_documento_identidad }}</p>
                                    <p class="font-italic mb-1"><strong>Dirección:</strong> {{ Auth::user()->persona->no_direccion_ultima }}</p>
                                </div>
                            </div>

                            <form action="{{ route('cambiar-password') }}" method="post" class="my-3">
                                @csrf
                                <div class="form-group mb-3">
                                    <label>Cambiar password</label>
                                    <input type="password" required class="form-control" name="password">
                                </div>
                                <div class="form-group">
                                    <input type="submit" class="btn btn-primary" value="Guardar password">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="container-fluid wiguet d-lg-none d-md-block d-sm-block d-xs-block menu-mobil bg-light position-fixed fixed-bottom pt-3 pt-3" style="z-index: 99;">
        <div class="col-12 inmuebles-mobil">
            <div class="row align-items-evenly justify-content-center inmuebles-mobil">
                <div class="col-4">
                    <a href="{{ route('inicio')}}" style="color:#01276A !important" class="d-block">
                        <img src="img/vector/searchmobil.png" class="img-fluid mx-auto" alt="Casas">
                        <p class="mt-2 text-center"style="color:#01276A !important" >Buscar</p>
                    </a>
                </div>
                <div class="col-4">
                    <a href="{{ route('favoritos')}}" style="color:#01276A !important" class="d-block">
                        <img src="img/vector/corazonazul.png" class="img-fluid mx-auto" alt="Departamento">
                        <p class="mt-2 text-center"style="color:#01276A" >Favoritos</p>
                    </a>
                </div>
                <div class="col-4">
                    <a href="{{ route('perfil')}}?co_tipo_garantia=3" style="color:#F59C1A !important" class="d-block">
                        <img src="/img/vector/user-mobileamarillo.png" class="img-fluid mx-auto" alt="Local Comercial">
                        <p class="mt-2 text-center"style="color:#F59C1A !important" >Perfil</p>
                    </a>
                </div>
            </div>
        </div>
    </div>

<script>
    $(document).ready(function() {
    var previousScrollPosition = 0;
    var menuHeight = $('.wiguet').height();
        $(window).scroll(function() {
            var currentScrollPosition = $(window).scrollTop();

            if (currentScrollPosition > previousScrollPosition) {
                // Scrolling down - hide the menu
                $('.wiguet').addClass('hidden');
            } else {
                // Scrolling up or at the top - show the menu
                if (currentScrollPosition <= menuHeight) {
                    $('.wiguet').addClass('visible'); // Added "visible" class
                } else {
                    $('.wiguet').removeClass('hidden'); // Removed "hidden" class check
                }
            }
            previousScrollPosition = currentScrollPosition;
        });
    });
</script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.min.js"></script>
</body>
</html>