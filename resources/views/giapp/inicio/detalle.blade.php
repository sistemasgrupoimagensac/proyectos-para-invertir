<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="icon" href="/img/logo.png" type="image/x-icon">
    <link rel="shortcut icon" href="/img/logo.png" type="image/x-icon">
    <title>G Inversiones</title>
    <!-- <link rel="stylesheet" href="css/inicio.css"> -->
    <link rel="stylesheet" href="{{ asset('./css/seleccion.css')}}">
    <link rel="stylesheet" href="{{ asset('./css/form.css')}}">
    <!--CDN POPPINS FUENTE-->
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <!--css ow carrousel-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <!--lightbox-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>

<!--fontawesome-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        #mapa_gi {
          height: 65vh;
          width: 100%;
        }

        .btn-back {
            background-color: #003366; /* Azul oscuro similar al header */
            color: white; /* Texto en blanco */
            border: 2px solid #003366; /* Borde del mismo color */
            border-radius: 8px; /* Bordes redondeados */
            padding: 0.5rem 1rem; /* Espaciado interno */
            font-size: 1rem; /* Tamaño del texto */
            font-family: 'Arial', sans-serif; /* Fuente profesional */
            cursor: pointer; /* Cambia el cursor al pasar sobre el botón */
            transition: all 0.3s ease; /* Animación para hover */
        }

        .btn-back:hover {
            background-color: white; /* Fondo blanco al pasar el mouse */
            color: #003366; /* Texto en azul oscuro */
            border-color: #003366; /* Mantiene el borde en azul */
        }

        #back-btn {
            display: none;
        }

        @media (max-width: 992px) {
            #back-btn {
                display: flex;
                align-items: center;
                margin: 1rem 0;
            }
        }
    </style>
</head>
<body> 
<div class="container-fluid seccion-1 d-none d-lg-block  d-sm-none d-xs-none p-3 ">
    <p class="text-center m-0 p-0">CONECTANDO METAS CON OPORTUNIDADES | GENERA UNA RENTABILIDAD DE HASTA 35% ANUAL</p>
</div>
<!--desktop-->
<div class="container-fluid seccion-2 p-3 d-block d-none d-lg-block ">
    <div class="row align-items-center justify-content-evenly">
        <div class="col-lg-1">
            <a href="{{route('inicio')}}">
                <img class="img-fluid logo p-2" src="../img/logo.png" alt="">
            </a>
        </div>
        <div class="col-lg-1" style="display: flex; align-items: center; margin: 1rem 0;">
            <button class="btn-back" onclick="window.history.back()">⬅ Regresar</button>
        </div>
        <div class="col-lg-6">
            <div class="container opciones">
                <form action="{{route('inicio')}}" name="frm_filtros">
                    <div class="row  rounded-pill border border-1  p-1">
                        <div class="col-5 d-flex align-items-center">
                            <select class="js-example-basic-single w-100 " name="provincia" onchange="document.frm_filtros.submit();">
                                <option value="" selected>Buscar por provincia</option>
                                @foreach($provincias as $item)
                                <option value="{{ $item->co_provincia }}">{{ $item->no_provincia }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-5 d-flex">
                            <img src="/img/vector/Vector 2.png" class="" alt="">
                            <input class="w-100 h-100 border-0 ms-4" type="text" placeholder="Monto a invertir" name="monto">
                        </div>
                        <div class="col-2 justify-content-end d-flex"> 
                            <button type="submit" style="background-color: transparent; border: none;">
                                <img src="/img/vector/search.svg" alt="">
                            </button>
                        </div>
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
                        <img class="rounded-circle img-fluid" src="/img/vector/perfil.png" style="height:3.5rem;" alt="profile">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid border-1 border-top border-bottom p-3">
        <div class="row justify-content-evenly align-items-center">
            <div class="col-5 border-end-2 justify-content-start">
                <p style="color: #F59C1A;">Invierte en:</p>
                <div class="owl-carousel owl-theme" id="menu-tipo-garantia">
                    <div class="item justify-content-center"><a href="{{ route('inicio')}}?co_tipo_garantia=1">
                        <img src="../img/vector/home.svg" class="img-fluid">Casas</a>
                    </div>
                    <div class="item"><a href="{{ route('inicio')}}?co_tipo_garantia=2">
                        <img src="../img/vector/department.svg" class="img-fluid">Departamento</a>
                    </div>
                    <div class="item"><a href="{{ route('inicio')}}?co_tipo_garantia=3">
                        <img src="../img/vector/local.svg" class="img-fluid">Local Comercial</a>
                    </div>
                    <div class="item"><a href="{{ route('inicio')}}?co_tipo_garantia=4">
                        <img src="../img/vector/granja.svg" class="img-fluid">Granja/Finca</a>
                    </div>
                    <div class="item"><a href="{{ route('inicio')}}?co_tipo_garantia=4">
                        <img src="../img/vector/terreno.svg" class="img-fluid">Terreno</a>
                    </div>
                </div>
            </div>
            {{-- <div class="col-2 ">
                <p style="color: #F59C1A;">Ordenar por:</p>
                <div class="col 12 justify-content-evenly d-flex ">
                    <div id="btn-lista" style="cursor:pointer"><img src="../img/vector/lista.svg" alt=""> Lista</div>
                    <div id="btn-grid" style="cursor:pointer"><img src="../img/vector/grilla.svg" alt=""> Grilla</div>
                </div>
            </div> --}}

        </div>
    </div>
</div>
<!--mobil-->
<div class="container-fluid seccion-2  d-lg-none d-md-block d-sm-block d-xs-block menu-mobil bg-light position-fixed fixed-top pt-4" style="z-index: 99;">
    <div class="col-12 d-flex pb-3 align-items-center">
        <form action="{{ route('inicio')  }}" class="col-12 d-flex align-items-center" name="frm_filtros_mobil">
            <div class="col-12 d-flex bg-white rounded-pill border border-1 p-2">
                <div class="col-2 rounded-circle d-flex align-items-center justify-content-center">
                    <button type="submit" style="background-color: transparent; border: none;"><img
                        src="../img/vector/searchmobil.png" alt="">
                    </button>
                </div>
                <div class="col-10">
                    <select class="js-example-basic-single w-100" name="provincia" onchange="document.frm_filtros_mobil.submit();">
                        <option value="" selected>Buscar por Provincia</option>
                        @foreach($provincias as $item)
                        <option value="{{ $item->co_provincia }}">{{ $item->no_provincia }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>
    </div>
    <div class="col-12 inmuebles-mobil">
        <div class="owl-carousel owl-theme text-center" id="menu-tipo-garantia">
            <div class="item">
                <a href="{{ route('inicio')}}?co_tipo_garantia=1" style="color:#01276A !important" class="d-block">
                    <img src="../img/vector/home.svg" class="img-fluid mx-auto" alt="Casas">
                    <p class="mt-2 text-center"style="color:#01276A" >Casas</p>
                </a>
            </div>
            <div class="item">
                <a href="{{ route('inicio')}}?co_tipo_garantia=2" style="color:#01276A !important" class="d-block">
                    <img src="../img/vector/department.svg" class="img-fluid mx-auto" alt="Departamento">
                    <p class="mt-2 text-center"style="color:#01276A" >Departamento</p>
                </a>
            </div>
            <div class="item">
                <a href="{{ route('inicio')}}?co_tipo_garantia=3" style="color:#01276A !important" class="d-block">
                    <img src="../img/vector/local.svg" class="img-fluid mx-auto" alt="Local Comercial">
                    <p class="mt-2 text-center"style="color:#01276A" >Local Comercial</p>
                </a>
            </div>
            <div class="item">
                <a href="{{ route('inicio')}}?co_tipo_garantia=4" style="color:#01276A !important" class="d-block">
                    <img src="../img/vector/granja.svg" class="img-fluid mx-auto" alt="Granja/Finca">
                    <p class="mt-2 text-center"style="color:#01276A" >Granja/Finca</p>
                </a>
            </div>
            <div class="item">
                <a href="{{ route('inicio')}}?co_tipo_garantia=5" style="color:#01276A !important" class="d-block">
                    <img src="../img/vector/terreno.svg" class="img-fluid mx-auto" alt="Terreno">
                    <p class="mt-2 text-center"style="color:#01276A" >Terreno</p>
                </a>
            </div>
        </div>
    </div>
 </div>
  
    <div class="container pb-5 contenido">
        @if(Auth::user()->id == 281)
        <div style="position: absolute; top:0px; right:0px" class="btns-proyecto p-3">
            <div class="text-end">
                <span style="font-size: .8rem;">Vendedor</span><br>
                <span>{{ $detalle->vendedor }}</span>
            </div>
        </div>
        @else
        <div style="position: absolute; top: 0; right: 0;">
            <div class="cajamegusta likeButton" data-co-prestamo="{{ $detalle->co_prestamo }}">
                @if(optional($detalle->interesado)->estado == 1)
                    <img src="../img/vector/corazonlleno.png" alt="No me gusta" class="heartImage">
                @else
                    <img src="../img/vector/corazon.png" alt="Me gusta" class="heartImage">
                @endif
              
            </div>
            {{-- @if ( $detalle->co_ocurrencia_actual == 34 ) --}}
            @if ( $aprobadoPorUserActual )
                    {{-- <p style="position:relative; display: flex; justify-content: space-between; align-items: center; right:1.8rem;" class="pt-4">
                            <img src="{{ asset('img/proyecto-aprobado.png') }}" style="height: 3rem;" alt="Desaprobar" style="margin: 0 10px 20px 0; cursor: pointer;">
                    </p> --}}
                {{-- @endif --}}
            @else
                <p style="position:relative; display: flex; justify-content: space-between; align-items: center; right:1.8rem;" class="pt-4">
                    {{-- <span style="display: inline-block; white-space: pre-line">Aprobar<br>proyecto</span>  --}}
                    <a href="#" style="margin-right:20px;" class="btnAceptarProyecto" data-co-prestamoAceptar="{{ $detalle->co_prestamo }}">
                        <img src="{{ asset('img/proyecto-aprobar.png') }}" style="height: 3rem;" alt="Aprobar" style="margin: 0 10px 20px 0; cursor: pointer;">
                    </a>
                </p>
            @endif
        </div>
        @endif
        <div class="col-lg-1" id="back-btn">
            <button class="btn-back" onclick="window.history.back()">⬅ Regresar</button>
        </div>
        <p class="m-0 fs-3">{{ $detalle->no_tipo_garantia }}</p>
        <span class="fs-5">{{ $detalle->co_unico_solicitud }}</span>
        <h1><img src="../img/vector/Ubicacion.png" alt="">{{ $detalle->no_provincia }} - {{ $detalle->no_distrito }}</h1>
        <div class="row justify-content-center col-12 imagen-sitio mt-4">
            <div class="owl-carousel justify-content-center d-flex" id="img-inmuebles">
                @foreach($imagenes as $imagen)
                <div class="">
                    <a href="{{ $imagen->url_evidencia }}" data-lightbox="roadtrip">
                        <img src="{{ $imagen->url_evidencia }}" class="img-fluid" alt="{{ $detalle->no_distrito }}">
                    </a>
                </div>
                @endforeach
            </div>
            @if(sizeof($imagenes) > 3)
            <div class="d-flex justify-content-end mt-3">
                <a class="list-style-none" href="javascript:void(0)" onclick="document.querySelector('#img-inmuebles a').click();"><i class="far fa-eye me-1"></i>Ver más fotos</a>
            </div>
            @endif
        </div>
    </div>
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-lg-8 pe-5" style="text-align: justify" >
                    <h3 class="mb-3"><strong>Perfil del Empresario</strong></h3>
                    <p><strong>Motivo del préstamo requerido.</strong></p>

                    {!! $detalle->motivo_prestamo !!}

                    <p class="m-0 fw-bold mt-5">Monto de Financiamiento</p>
                    <span class="fs-1 fw-bold">{{$detalle->nc_tipo_moneda}} {{ number_format($detalle->nu_total_solicitado, 2) }}</span>
                </div>
                @if(Auth::user()->id != 281)
                <div class="col-lg-4 col-md-6">
                    <form class="form m-auto border-1 border" action="">
                        <div class="d-flex m-auto justify-content-center align-items-center p-3">
                            <img src="/img/vector/formicon.svg" alt="">
                            <span class="ms-3 fs-3 fw-bold">Contacta su <br>Analista</span>
                        </div>
                        <textarea style="font-size: 0.8rem;" class="form-control mb-3" id="mensaje_form_detalle" rows="6">Hola, me interesa invertir en el proyecto con código de solicitud: {{ $detalle->co_unico_solicitud }}, ubicado en: {{ $detalle->no_provincia }} - {{ $detalle->no_distrito }} y con monto de financiamiento: {{$detalle->nc_tipo_moneda}} {{ number_format($detalle->nu_total_solicitado, 2) }}</textarea>
                        <button id="btn_form_detalle" type="button" class="btn btn-block btn-primary mb-4 p-2" onclick="enviarMensajeWhts()">Enviar</button>
                        <span style="text-transform: capitalize;">{{ mb_strtolower($analista_inversion->analista_nombre ?? '', 'UTF-8') }}</span><br>
                        <div class="mt-2">
                            <p class="fs-4 m-0">{{ $analista_inversion->analista_celular ?? '' }}</p>
                            <a href="mailto:{{ $analista_inversion->analista_email ?? '' }}" class="text-dark">{{ $analista_inversion->analista_email ?? '' }}</a>
                        </div>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
    <div class="container-fluid pt-5 detalle-2">
        <div class="container">
            <div class="row align-items-center justify-content-center">
                <div class="col-lg-4 col-md-6 col-6 mb-3">
                    <img src="../img/vector/tasa.svg" alt="">
                    <p class="m-0 mt-3">tasa de interés Mensual:</p>
                    <h3>{{ (float) $detalle->nu_tasa_interes_mensual }}%</h3>
                </div>
                <div class="col-lg-4 col-md-6 col-6 mb-3">
                    <img src="../img/vector/plazo.svg" alt="">
                    <p class="m-0 mt-3">Plazo de Financiamiento:</p>
                    <h3>{{ $detalle->no_tiempo_pago }}</h3>
                </div>
                <div class="col-lg-4 col-md-6 col-6 mb-3">
                    <img src="../img/vector/financiamiento.svg" alt="">
                    <p class="m-0 mt-3">Tipo de Financiamiento</p>
                    <h3>{{ $detalle->no_forma_pago }}</h3>
                </div>
                <div class="col-lg-4 col-md-6 col-6 mb-3">
                    <img src="../img/vector/Comercial.svg" alt="">
                    <p class="m-0 mt-3">Valor Comercial del inmueble:</p>
                    <h3>{{$detalle->tipo_moneda_dato_prestamo}} {{ number_format($detalle->valor_comercial_inmueble ?? 0, 2) }}</h3>
                </div>
                <div class="col-lg-4 col-md-6 col-6 mb-3">
                    <img src="../img/vector/interesados.png" alt="">
                    <p class="m-0 mt-3">Interesados:</p>
                    <h3 id="cantidad_interes">{{ $detalle->interesados }}</h3>
                </div>
                <div class="col">
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid m-auto text-center justify-content-center my-5">
        <div class="container">
            <div id="mapa_gi"></div>
        </div>
    </div>
    <!--modal menu filtro-->
    <div class="offcanvas offcanvas-top" tabindex="-1" id="offcanvasTop" aria-labelledby="offcanvasTopLabel">
        <div class="offcanvas-header">
          <h5 id="offcanvasTopLabel">Filtros</h5>
          <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <form action="">
                <div class="row mt-2">
                    <div class="col-5">
                        <select class="form-select" aria-label="Default select example">
                            <option selected>Seleccione filtro</option>
                            <option value="1">Por provincia</option>
                            <option value="2">Monto invertir</option>
                        </select>
                    </div>
                    <div class="col-7">
                        <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <div class="container-fluid wiguet d-lg-none d-md-block d-sm-block d-xs-block menu-mobil bg-light position-fixed fixed-bottom pt-3 pt-3" style="z-index: 99;">
        <div class="col-12 inmuebles-mobil">
            <div class="row align-items-evenly justify-content-center inmuebles-mobil">
                <div class="col-4">
                    <a href="{{ route('inicio')}}" style="color:#01276A !important; text-decoration:none;" class="d-block">
                        <img src="/img/vector/searchmobil.png" class="img-fluid mx-auto" alt="Casas">
                        <p class="mt-2 text-center"style="color:#01276A !important" >Buscar</p>
                    </a>
                </div>
                <div class="col-4">
                    <a href="{{ route('favoritos')}}" style="color:#F59C1A !important; text-decoration:none;" class="d-block">
                        <img src="/img/vector/corazon.png" class="img-fluid mx-auto" alt="Departamento">
                        <p class="mt-2 text-center"style="color:#F59C1A !important" >Favoritos</p>
                    </a>
                </div>
                <div class="col-4">
                    <a href="{{ route('perfil')}}?co_tipo_garantia=3" style="color:#01276A !important; text-decoration:none;" class="d-block">
                        <img src="/img/vector/user-mobile.png" class="img-fluid mx-auto" alt="Local Comercial">
                        <p class="mt-2 text-center"style="color:#01276A" >Perfil</p>
                    </a>
                </div>
            </div>
        </div>
    </div>
<!--ow carrousel-->
<div class="container-fluid text-center p-3"style="background-color:#E3E3E3">
    <a href="https://ginversiones.pe" style="text-decoration: none; color:#01276A">www.ginversiones.pe</a>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
<script src="http://cdnjs.cloudflare.com/ajax/libs/waypoints/2.0.3/waypoints.min.js"></script>
<script>
    $(document).ready(function(){
        $('#img-inmuebles').owlCarousel({
            loop: false,
            margin: 10,
            responsiveClass: true,
            responsive: {
                0: {
                    items: 1,
                },
                600: {
                    items: 2,
                },
                1000: {
                    items: 5,
                }
            }
        });
    });
</script>
<script>
    $('#menu-tipo-garantia').owlCarousel({
        loop:false,
        margin:10,
        responsiveClass:true,
        responsive: {
                0: {
                    items: 3,
                },
                600: {
                    items: 5,
                },
                1000: {
                    items: 5,
                }
        }
    })
    $('#img-inmuebles').owlCarousel({
        loop:false,
        margin:10,
        responsiveClass:true,
        responsive: {
                0: {
                    items: 2,
                },
                600: {
                    items: 2,
                },
                1000: {
                    items: 3,
                }
        }
    })
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/headroom/0.12.0/headroom.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/headroom/0.12.0/jQuery.headroom.min.js"></script>
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
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    axios.defaults.headers.common = {
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN' : document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    };

    document.querySelectorAll('.likeButton').forEach(function(likeButton) {
        likeButton.addEventListener('click', function () {
            var co_prestamo = this.getAttribute('data-co-prestamo');
            likeAction(co_prestamo, this);
        });
    });
    function likeAction(co_prestamo, buttonElement) {
        axios.post('/me_interesa', {
            co_prestamo: co_prestamo,
        })
        .then(function (response) {
            if (response.data.like_actual.estado === 1) {
                buttonElement.querySelector('.heartImage').src = '/img/vector/corazonlleno.png';
            } 
            else{
                buttonElement.querySelector('.heartImage').src = '/img/vector/corazon.png';
            }

            var likesCountElement = document.getElementById('cantidad_interes');
            if (likesCountElement) {
                likesCountElement.textContent = response.data.cantidad;
            }
        })
        .catch(function (error) {
            console.error('Error al dar like:', error);
        });
    }
</script>
<script>
    lightbox.option({
        'resizeDuration': 50,
        'wrapAround': true,
        'showImageNumberLabel':true
    })
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.js-example-basic-single').select2();
    });
</script>
<script>

  (g=>{var h,a,k,p="The Google Maps JavaScript API",c="google",l="importLibrary",q="__ib__",m=document,b=window;b=b[c]||(b[c]={});var d=b.maps||(b.maps={}),r=new Set,e=new URLSearchParams,u=()=>h||(h=new Promise(async(f,n)=>{await (a=m.createElement("script"));e.set("libraries",[...r]+"");for(k in g)e.set(k.replace(/[A-Z]/g,t=>"_"+t[0].toLowerCase()),g[k]);e.set("callback",c+".maps."+q);a.src=`https://maps.${c}apis.com/maps/api/js?`+e;d[q]=f;a.onerror=()=>h=n(Error(p+" could not load."));a.nonce=m.querySelector("script[nonce]")?.nonce||"";m.head.append(a)}));d[l]?console.warn(p+" only loads once. Ignoring:",g):d[l]=(f,...n)=>r.add(f)&&u().then(()=>d[l](f,...n))})({
    key: "AIzaSyDEqrm40w_9z8RNaWY9h-P_tFvJP8m9tK0",
    v: "weekly",
    // Use the 'v' parameter to indicate the version to use (weekly, beta, alpha, etc.).
    // Add other bootstrap parameters as needed, using camel case.
  });

    
    let position = {
        lat: -12.0973182,
        lng: -77.0233135
    };

    @if($detalle->latitud && $detalle->longitud)
    position = {
        lat: Number({{ $detalle->latitud }}),
        lng: Number({{ $detalle->longitud }})
    };
    @endif

    let map;

    async function initMap() {
        const { Map, Circle } = await google.maps.importLibrary("maps");
        // Create the map.
        map = new Map(document.getElementById("mapa_gi"), {
            center: position,
            zoom: 16,
            styles: [
                {
                    featureType: 'poi.business',
                    elementType: 'labels',
                    stylers: [{ visibility: 'off' }]
                },
                {
                    featureType: 'poi.attraction',
                    elementType: 'labels',
                    stylers: [{ visibility: 'off' }]
                },
                {
                    featureType: 'poi.place_of_worship',
                    elementType: 'labels',
                    stylers: [{ visibility: 'off' }]
                },
                {
                    featureType: 'poi.school',
                    elementType: 'labels',
                    stylers: [{ visibility: 'off' }]
                },
                {
                    featureType: 'poi.sports_complex',
                    elementType: 'labels',
                    stylers: [{ visibility: 'off' }]
                }
            ]
        });

        // Add the circle for this city to the map.
        const cityCircle = new Circle({
            strokeColor: "#F59C1A",
            strokeOpacity: 0.8,
            strokeWeight: 2,
            fillColor: "#F59C1A",
            fillOpacity: 0.35,
            map,
            center: position,
            radius: 400,
        });        
    }

    initMap();

    function enviarMensajeWhts() {
        const mensaje_chat = document.getElementById('mensaje_form_detalle').value;
        window.open(`https://wa.me/51{{ $analista_inversion->analista_celular ?? '' }}?text=${ encodeURI(mensaje_chat) }`, '_blank');
    }
</script>

<script>
    const agregarEventoDesaprobarProyecto = codProyecto => {
        const $btnDesaprobarProyecto = document.querySelector(`.btnDesaprobarProyecto[data-co-prestamoDesaprobar='${codProyecto}']`);

        if ( $btnDesaprobarProyecto ) {
            $btnDesaprobarProyecto.addEventListener('click', function(event) {
                event.preventDefault();
                desaprobarProyecto(codProyecto);
            });
        }
    };

    const agregarEventoAceptarProyecto = codProyecto => {
        const $btnAceptarProyecto = document.querySelector(`.btnAceptarProyecto[data-co-prestamoAceptar='${codProyecto}']`);
        if ($btnAceptarProyecto) {
            $btnAceptarProyecto.addEventListener('click', function(event) {
                event.preventDefault();
            $btnAceptarProyecto.style.pointerEvents = 'none';
            $btnAceptarProyecto.style.opacity = '0.6';
                aceptarProyecto($codigoProyecto, $btnAceptarProyecto)
            });
        }
    };

    const aceptarProyecto = (codProyecto, $btnAceptarProyecto) => {
        axios.post('/aceptar-proyecto', {
            codigo_prestamo: codProyecto,
        })
        .then(function (response) {

            if ( response.data.http_code === 200 ) {
                const $contenedorBoton = $btnAceptarProyecto.closest('p');
                const nuevoBotonHTML = `
                    <p style="position:relative; display: flex; justify-content: space-between; align-items: center; right:1.8rem;" class="pt-4">
                        <img src="${window.location.origin}/img/proyecto-aprobado.png" style="height: 3rem;" alt="Desaprobar" style="margin: 0 10px 20px 0; cursor: pointer;">
                    </p>
                `;
                $contenedorBoton.outerHTML = nuevoBotonHTML;
                // agregarEventoDesaprobarProyecto(codProyecto);
            } else {
                alert( response.data.message )
                $btnAceptarProyecto.style.pointerEvents = '';
                $btnAceptarProyecto.style.opacity = '';
            }
        })
        .catch(function (error) {
            console.error('Error al aprobar el proyecto:', error);
            $btnAceptarProyecto.style.pointerEvents = '';
            $btnAceptarProyecto.style.opacity = '';
        });
    }

    const desaprobarProyecto = codProyecto => {
        axios.post('/desaprobar-proyecto', {
            codigo_prestamo: codProyecto,
        })
        .then(function (response) {

            if ( response.data.http_code === 200 ) {
                const $btnDesaprobarProyecto = document.querySelector(`.btnDesaprobarProyecto[data-co-prestamoDesaprobar='${codProyecto}']`);
                const $contenedorBoton = $btnDesaprobarProyecto.closest('p');
                const nuevoBotonHTML = `
                    <p style="position:relative; display: flex; justify-content: space-between; align-items: center; right:1.5rem;" class="pt-4">
                        <span style="display: inline-block; white-space: pre-line">Aprobar<br>proyecto</span> 
                        <a href="#" style="margin-right:20px;" class="btnAceptarProyecto" data-co-prestamoAceptar="${codProyecto}">
                            <img src="${window.location.origin}/img/aproved-white.png" style="height: 3rem;" alt="Aprobar" style="margin: 0 10px 20px 0; cursor: pointer;">
                        </a>
                    </p>
                `;
                $contenedorBoton.outerHTML = nuevoBotonHTML;
                agregarEventoAceptarProyecto(codProyecto);
            } else {
                alert( response.data.message )
            }
        })
        .catch(function (error) {
            console.error('Error al aprobar el proyecto:', error);
        });
    }

    const $btnsAceptarProyecto = document.querySelectorAll('.btnAceptarProyecto')
    $btnsAceptarProyecto.forEach( ($btnAceptarProyecto) => {
        $btnAceptarProyecto.addEventListener('click', function() {
            event.preventDefault();
            const $codigoProyecto = this.getAttribute('data-co-prestamoAceptar')
            $btnAceptarProyecto.style.pointerEvents = 'none';
            $btnAceptarProyecto.style.opacity = '0.6';
            aceptarProyecto($codigoProyecto, $btnAceptarProyecto)
        });
    });

    const $btnsDesaprobarProyecto = document.querySelectorAll('.btnDesaprobarProyecto')
    $btnsDesaprobarProyecto.forEach( ($btnDesaprobarProyecto) => {
        $btnDesaprobarProyecto.addEventListener('click', function() {
            event.preventDefault();
            const $codigoProyecto = this.getAttribute('data-co-prestamoDesaprobar')
            desaprobarProyecto($codigoProyecto)
        });
    });
</script>
</body>
</html>
