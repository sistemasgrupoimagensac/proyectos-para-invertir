<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="icon" href="/img/logo.png" type="image/x-icon">
    <link rel="shortcut icon" href="/img/logo.png" type="image/x-icon">
    <title>G Inversiones</title>
    <link rel="stylesheet" href="{{asset('./css/inicio.css')}}">
    <!--CDN POPPINS FUENTE-->
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <!--css ow carrousel-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <!--fontawesome-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">


    <!--sweet alert-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .item-list {
            border: 1px solid #ccc;
        }
    
        #mensajeria-msj {
            position: fixed;
            bottom: 100px;
            right: 50px;
            border-radius: 50%;
            width: 113px;
            height: 113px;
            font-size: 8rem;
        }
    
        #mensajeria-msj i{
            color: #00a884;
        }
    
        #mensajeria-msj span {
            font-size: 1rem;
            top: 50px;
            right: 0;
        }
    
        @media (max-width: 992px) {
            #mensajeria-msj {
                width: 50px !important;
                height: 50px !important;
                bottom: 170px !important;
                right: 20px !important;
            }
    
            #mensajeria-msj img {
                height: 50px;
            }
    
            #mensajeria-msj span {
                display: none;
            }
        }
        </style>

</head>

<body>
    <div class="container-fluid seccion-1 d-none d-lg-block d-md-block d-sm-none d-xs-none p-3">
        <p class="text-center p-0 m-0">CONECTANDO METAS CON OPORTUNIDADES | GENERA UNA RENTABILIDAD DE HASTA 35% ANUAL</p>
    </div>
      <!--desktop-->
      <div class="container-fluid seccion-2  d-block d-none d-lg-block">
        <div class="row align-items-center justify-content-evenly p-3">
            <div class="col-lg-3">
                <a href="{{route('inicio')}}">
                    <img class="img-fluid logo" src="img/logo.png" alt="">
                </a>
            </div>
            <div class="col-lg-6">
                <div class="container opciones">
                    <form action="{{route('favoritos')}}" name="frm_filtros">
                        <div class="row rounded-pill border border-1  p-1">
                            <div class="col-5 d-flex align-items-center">
                                <select class="js-example-basic-single w-100 border-0" name="provincia" onchange="document.frm_filtros.submit();">
                                    <option value="" selected>Buscar por provincia</option>
                                    @foreach($provincias as $item)
                                        <option value="{{ $item->co_provincia }}" {{ session('provincia') == $item->co_provincia ? 'selected' : '' }}>
                                            {{ $item->no_provincia }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-5 d-flex">
                                <img src="./img/vector/Vector 2.png" class="" alt="">
                                <input type="text" name="monto" class="border-0 w-100" value="{{ session('monto') }}" placeholder="Monto de búsqueda">
                            </div>
                            <div class="col-2 justify-content-end d-flex"> <button type="submit" style="background-color: transparent; border: none;"><img
                                        src="./img/vector/search.svg" alt=""></button></div>
                        </div>
                    </form>
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
                            <img class="rounded-circle img-fluid" src="./img/vector/perfil.png" style="height:3.5rem;" alt="profile">
                        </div>
                    </div>
                </div>
            </div>
            <div class="container-fluid border-1 border-top border-bottom p-3">
                <div class="row justify-content-evenly align-items-center">
                    <div class="col-5 border-end-2 justify-content-start">
                        <p style="color: #F59C1A;">Invierte en:</p>
                        <div class="owl-carousel owl-theme">
    
                        <div class="item justify-content-center">
                            <a href="{{ route('favoritos')}}?co_tipo_garantia=1" style="color: {{ (request('co_tipo_garantia') == 1) ? '#F59C1A' : '#00266A' }} !important;">
                                @if(request('co_tipo_garantia') == 1)
                                    <img src="img/vector/homeamarillo.svg" class="img-fluid">Casas
                                @else
                                    <img src="img/vector/home.svg" class="img-fluid">Casas
                                @endif
                            </a>
                        </div>
                        <div class="item justify-content-center">
                            <a href="{{ route('favoritos')}}?co_tipo_garantia=2" style="color: {{ (request('co_tipo_garantia') == 2) ? '#F59C1A' : '#00266A' }} !important;">
                                @if(request('co_tipo_garantia') == 2)
                                <img src="img/vector/deparmentamarillo.svg" class="img-fluid">Departamento
                                @else
                                <img src="img/vector/department.svg" class="img-fluid">Departamento
                                @endif
                            </a>
                        </div>
                        <div class="item justify-content-center">
                            <a href="{{ route('favoritos')}}?co_tipo_garantia=3" style="color: {{ (request('co_tipo_garantia') == 3) ? '#F59C1A' : '#00266A' }} !important;">
                                @if(request('co_tipo_garantia') == 3)
                                <img src="img/vector/localamarillo.svg" class="img-fluid">Local Comercial
                                @else
                                <img src="img/vector/local.svg" class="img-fluid">Local Comercial
                                @endif
                            </a>
                        </div>
                        <div class="item justify-content-center">
                            <a href="{{ route('favoritos')}}?co_tipo_garantia=4" style="color: {{ (request('co_tipo_garantia') == 4) ? '#F59C1A' : '#00266A' }} !important;">
                                @if(request('co_tipo_garantia') == 4)
                                <img src="img/vector/granjaamarillo.svg" class="img-fluid">Granja/Finca
                                @else
                                <img src="img/vector/granja.svg" class="img-fluid">Granja/Finca
                                @endif
                            </a>
                        </div>
                        <div class="item justify-content-center">
                            <a href="{{ route('favoritos')}}?co_tipo_garantia=5" style="color: {{ (request('co_tipo_garantia') == 5) ? '#F59C1A' : '#00266A' }} !important;">
                                @if(request('co_tipo_garantia') == 5)
                                <img src="img/vector/terrenoamarillo.svg" class="img-fluid">Terreno
                                @else
                                <img src="img/vector/terreno.svg" class="img-fluid">Terreno
                                @endif
                            </a>
                        </div>
                        </div>
                    </div>
                    {{-- <div class="col-2 ">
                        <p style="color: #F59C1A;">Ordenar por:</p>
                        <div class="col 12 justify-content-evenly d-flex ">
                            <div id="btn-lista" style="cursor:pointer"><img src="img/vector/lista.svg" alt=""> Lista</div>
                            <div id="btn-grid" style="cursor:pointer"><img src="img/vector/grilla.svg" alt=""> Grilla</div>
                        </div>
                    </div> --}}
    
                </div>
            </div>

        </div>
    </div>
     <!--mobil-->
     <div class="container-fluid seccion-2  d-lg-none d-md-block d-sm-block d-xs-block menu-mobil bg-light position-fixed fixed-top pt-4 pb-2" style="z-index: 99;">
        <div class="col-12 d-flex pb-4 align-items-center">
            <form action="{{ route('favoritos')  }}" class="col-12 d-flex align-items-center" name="frm_filtros_mobil">
                <div class="col-12 d-flex bg-white rounded-pill border border-1 p-2">
                    <div class="col-2 rounded-circle d-flex align-items-center justify-content-center">
                        <button type="submit" style="background-color: transparent; border: none;">
                            <img src="./img/vector/searchmobil.png" alt="">
                        </button>
                    </div>
                    <div class="col-10">
                        <select class="js-example-basic-single w-100" name="provincia" onchange="document.frm_filtros_mobil.submit();">
                            <option value="" selected>Buscar por provincia</option>
                            @foreach($provincias as $item)
                                <option value="{{ $item->co_provincia }}" {{ session('provincia') == $item->co_provincia ? 'selected' : '' }}>
                                    {{ $item->no_provincia }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
           
            </form>
        </div>
        <div class="col-12 inmuebles-mobil">
            <div class="owl-carousel owl-theme text-center">
                <div class="item">
                    <a href="{{ route('favoritos')}}?co_tipo_garantia=1" style="color: {{ (request('co_tipo_garantia') == 1) ? '#F59C1A' : '#00266A' }} !important;">
                        @if(request('co_tipo_garantia') == 1)
                            <img src="img/vector/homeamarillo.svg" class="img-fluid">Casas
                        @else
                            <img src="img/vector/home.svg" class="img-fluid">Casas
                        @endif
                    </a>
                </div>
                <div class="item">
                    <a href="{{ route('favoritos')}}?co_tipo_garantia=2" style="color: {{ (request('co_tipo_garantia') == 2) ? '#F59C1A' : '#00266A' }} !important;">
                        @if(request('co_tipo_garantia') == 2)
                        <img src="img/vector/deparmentamarillo.svg" class="img-fluid">Departamento
                        @else
                        <img src="img/vector/department.svg" class="img-fluid">Departamento
                        @endif
                    </a>
                </div>
                <div class="item">
                    <a href="{{ route('favoritos')}}?co_tipo_garantia=3" style="color: {{ (request('co_tipo_garantia') == 3) ? '#F59C1A' : '#00266A' }} !important;">
                        @if(request('co_tipo_garantia') == 3)
                        <img src="img/vector/localamarillo.svg" class="img-fluid">Local Comercial
                        @else
                        <img src="img/vector/local.svg" class="img-fluid">Local Comercial
                        @endif
                    </a>
                </div>
                <div class="item">
                    <a href="{{ route('favoritos')}}?co_tipo_garantia=4" style="color: {{ (request('co_tipo_garantia') == 4) ? '#F59C1A' : '#00266A' }} !important;">
                        @if(request('co_tipo_garantia') == 4)
                        <img src="img/vector/granjaamarillo.svg" class="img-fluid">Granja/Finca
                        @else
                        <img src="img/vector/granja.svg" class="img-fluid">Granja/Finca
                        @endif
                    </a>
                </div>
                <div class="item">
                    <a href="{{ route('favoritos')}}?co_tipo_garantia=5" style="color: {{ (request('co_tipo_garantia') == 5) ? '#F59C1A' : '#00266A' }} !important;">
                        @if(request('co_tipo_garantia') == 5)
                        <img src="img/vector/terrenoamarillo.svg" class="img-fluid">Terreno
                        @else
                        <img src="img/vector/terreno.svg" class="img-fluid">Terreno
                        @endif
                    </a>
                </div>
            </div>
        </div>
    </div>
    <style>
    .item-list {
        border: 1px solid #ccc;
    }

    #mensajeria-msj {
        position: fixed;
        bottom: 100px;
        right: 50px;
        border-radius: 50%;
        width: 113px;
        height: 113px;
        font-size: 8rem;
    }

    #mensajeria-msj i{
        color: #00a884;
    }

    #mensajeria-msj span {
        font-size: 1rem;
        top: 50px;
        right: 0;
    }
    </style>
    <!-- contenido-->   
    <div class="container m-auto text-center justify-content-center contenido">


        @foreach ($favoritos as $item)
        <div class="card my-4 card-inmueble shadow border-0 bg-white position-relative">
            <div class="row g-0 h-100">
                <div class="col-lg-4 h-100">
                    <a href="{{ route('detalle', ['co_solicitud_prestamo' => $item->co_solicitud_prestamo]) }}" class="text-decoration-none text-reset">
                        <img src="{{ $item->imagen_principal }}" class="card-inmueble-image rounded" alt="{{ $item->no_distrito }}">
                    </a>
                </div>
                <div class="col-lg-8">
                    <div class="card-body h-100 p-0 d-flex flex-column justify-content-between p-4">
                        <span class="text-start">{{ $item->co_unico_solicitud }}</span>
                        <p class="m-0 mb-3 fw-bold">{{ $item->no_tipo_garantia }}</p>
                        <h3 class="card-title fw-bold text-with-overflow card-inmueble-title text-start">
                            <img class="icono-ubicacion img-producto" src="img/vector/Ubicacion.png" alt="">
                            <span>{{ $item->no_distrito}}</span>
                        </h3>
                        <p class="m-0">
                            <strong>Tasa de Interés Mensual:</strong>
                            <span>{{ (float) $item->nu_tasa_interes_mensual }}%</span>
                        </p>
                        <p class="m-0"><strong>Plazo de Finanaciamiento: </strong>{{$item->no_tiempo_pago}}</p>
                        <p class="m-0"><strong>Tipo de Financiamiento: </strong>{{ $item->no_forma_pago}}</p>
                        <p class="m-0"><strong>Valor Comercial del Inmueble: </strong>{{$item->tipo_moneda_dato_prestamo}} {{ number_format($item->valor_comercial_inmueble ?? 0, 2) }}</p>
                        <div class="d-flex justify-content-end">
                            <div class="col-auto text-end">
                                <span>Monto de financiamiento</span><br>
                                <span class="card-monto-financiamiento fw-bold">{{$item->nc_tipo_moneda}} {{ number_format($item->nu_total_solicitado ?? 0, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="heart text-center likeButton" data-co-prestamo="{{ $item->co_prestamo }}">
                <img src="img/vector/corazonlleno.png" class="heartImage" alt="Corazón lleno">

                <input type="hidden" value="{{ $item->id_like}}" name="id_like">
                <input type="hidden" value="{{ $item->co_prestamo }}" name="co_prestamo">
            </div>
        </div>
        @endforeach
    </div>

    <div class="container-fluid text-center p-3 mt-4"style="background-color:#E3E3E3">
        <a href="https://ginversiones.pe" style="text-decoration: none; color:#01276A">www.ginversiones.pe</a>
    </div>

    <div id="mensajeria-msj">
        <a href="https://wa.me/51{{ $analista->celular ?? '' }}?text=Hola%2C+me+interesa+invertir+en+un+proyecto" target="_blank" class="position-relative">
            <img src="img/Whatsapp.png" height="100" alt="">
            <span class="position-absolute translate-middle badge rounded-pill bg-secondary ">
                Contacte a su analista
            </span>
        </a>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <div class="container-fluid wiguet d-lg-none d-md-block d-sm-block d-xs-block menu-mobil bg-light position-fixed fixed-bottom pt-3 pt-3" style="z-index: 99;">
        <div class="col-12 inmuebles-mobil">
            <div class="row align-items-evenly justify-content-center inmuebles-mobil">
                <div class="col-4">
                    <a href="{{ route('inicio')}}" style="color:#01276A !important" class="d-block">
                        <img src="/img/vector/searchmobil.png" class="img-fluid mx-auto" alt="Casas">
                        <p class="mt-2 text-center"style="color:#01276A !important" >Buscar</p>
                    </a>
                </div>
                <div class="col-4">
                    <a href="{{ route('favoritos')}}" style="color:#F59C1A !important" class="d-block">
                        <img src="/img/vector/corazon.png" class="img-fluid mx-auto" alt="Departamento">
                        <p class="mt-2 text-center"style="color:#F59C1A" >Favoritos</p>
                    </a>
                </div>
                <div class="col-4">
                    <a href="{{ route('perfil')}}?co_tipo_garantia=3" style="color:#01276A !important" class="d-block">
                        <img src="/img/vector/user-mobile.png" class="img-fluid mx-auto" alt="Local Comercial">
                        <p class="mt-2 text-center"style="color:#01276A" >Perfil</p>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/waypoints/2.0.3/waypoints.min.js"></script>
    <script>
    $('.owl-carousel').owlCarousel({
        loop: false,
        margin: 10,
        responsiveClass: true,
        responsive: {
            0: {
                items: 3, // En pantallas menores a 600px, se mostrará 1 elemento
            },
            600: {
                items:5, // En pantallas de 600px o más, se mostrarán 4 elementos
            },
            1000: {
                items: 5, // En pantallas de 1000px o más, se mostrarán 5 elementos
            }
        }
    })
    </script>
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
    $(document).ready(function() {
        $('.js-example-basic-single').select2();
    });
    </script>
    <script>
    document
        .getElementById("btn-lista")
        .addEventListener("click", function() {
            document.getElementById("btn-lista").classList.add("active");
            document.getElementById("btn-grid").classList.remove("active");
            //document.getElementById("items-list").classList.remove("row-cols-3");
            document.querySelectorAll(".item-list").forEach(function(item) {
                item.classList.remove("col-md-4");
            });

            document.querySelectorAll(".div-col-4").forEach(function(item) {
                item.classList.remove("col-4");
                item.classList.add("row");
                item.classList.add("justify-content-center");
            });
            document.querySelectorAll(".div-col-change-1").forEach(function(item) {
                item.classList.remove("col-lg-12");
                item.classList.add("col-lg-4");
            });
            document.querySelectorAll(".div-col-change-2").forEach(function(item) {
                item.classList.remove("col-lg-12");
                item.classList.add("col-lg-6");
            });
        });

        document.getElementById("btn-grid")
        .addEventListener("click", function() {
            document.getElementById("btn-grid").classList.add("active");
            document.getElementById("btn-lista").classList.remove("active");
            //document.getElementById("items-list").classList.add("row-cols-3");
            document.querySelectorAll(".item-list").forEach(function(item) {
                item.classList.add("col-md-4");
            });
            document.querySelectorAll(".div-col-4").forEach(function(item) {
                item.classList.add("col-4");
                item.classList.remove("row");
                item.classList.remove("justify-content-center");
            });
            document.querySelectorAll(".div-col-change-1").forEach(function(item) {
                item.classList.add("col-lg-12");
                item.classList.remove("col-lg-4");
            });
            document.querySelectorAll(".div-col-change-2").forEach(function(item) {
                item.classList.add("col-lg-12");
                item.classList.remove("col-lg-6");
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
        var heartButtons = document.querySelectorAll('.likeButton');

        heartButtons.forEach(function(heartButton) {
        heartButton.addEventListener('click', function() {
            var idLike = heartButton.querySelector('input[name="id_like"]').value;

            Swal.fire({
                title: '¿Estás seguro que quieres quitar de tus favoritos?',
                showCancelButton: true,
                confirmButtonText: 'Sí',
                cancelButtonText: 'No',
                icon: 'warning',
            }).then((result) => {
                if (result.isConfirmed) {
                    // Realizar una solicitud AJAX para cambiar el estado en la base de datos
                    fetch('/quitar_favoritos', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ id_like: idLike })
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Estado cambiado a 0', data.like_actual);

                        // Redireccionar después de cambiar el estado
                        window.location.reload();
                    })
                    .catch(error => {
                        console.error('Error al cambiar el estado:', error);
                    });
                }
            });
        });
    });
});

    </script>

    <!-- Agrega esto en la sección head o antes de cerrar el body -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script> 

</body>

</html>