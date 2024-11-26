<!DOCTYPE html>
<html lang="en">
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
    <link rel="stylesheet" href="{{ asset('./css/inicio.css')}}">
    <link rel="stylesheet" href="{{ asset('../css/seleccion.css')}}">
    <link rel="stylesheet" href="{{ asset('./css/form.css')}}">
    <!--CDN POPPINS FUENTE-->
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <!--css ow carrousel-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" integrity="sha384-8Fj+q6vjA7S+Tfk6JB4WpDl+3s6qOpT0O5r5mzX5FZZBfQ9Sr6P6d7oNiR7fCjNo" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" integrity="sha384-DfXdAeFRuPjoXz6UYLM9pddzR1j9d51zbr5fFpRzsX4q1iRKpIbs15nl8ZnYU5h0" crossorigin="anonymous">
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
        #mapa-all {
          height: 85vh;
          width: 100%;
          margin-top: 2rem;
        }
    </style>
</head>
<body> 
    <div class="container-fluid seccion-1 d-none d-lg-block d-md-block d-sm-none d-xs-none ">
        <p class="text-center">CONECTANDO METAS CON OPORTUNIDADES | GENERA UNA RENTABILIDAD DE HASTA 35% ANUAL</p>
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
                    <form action="{{route('inicio')}}">
                        <div class="row  rounded-pill border border-1  p-1">
                            <div class="col-5 d-flex align-items-center">
                                <select class="js-example-basic-single border-0 w-100" name="distrito">
                                    <option value="" selected>Buscar por provincia</option>
                                    @foreach($provincias as $item)
                                        <option value="{{ $item->co_provincia }}" {{ session('distrito') == $item->co_provincia ? 'selected' : '' }}>
                                            {{ $item->no_provincia }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-5 d-flex">
                                <img src="img/vector/Vector 2.png" class="" alt="">
                                <input type="text" class="border-0 w-100" name="monto" value="{{ session('monto') }}" placeholder="Monto de búsqueda">
                            </div>
                            <div class="col-2 justify-content-end d-flex"> <button type="submit" style="background-color: transparent; border: none;"><img
                                        src="img/vector/search.svg" alt=""></button></div>
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
                            <img class="rounded-circle img-fluid" src="img/vector/perfil.png" style="height:3.5rem;" alt="profile">
                        </div>
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
                        <a href="{{ route('inicio')}}?co_tipo_garantia=1" style="color: {{ (request('co_tipo_garantia') == 1) ? '#F59C1A' : '#00266A' }} !important;">
                            @if(request('co_tipo_garantia') == 1)
                                <img src="img/vector/homeamarillo.svg" class="img-fluid">Casas
                            @else
                                <img src="img/vector/home.svg" class="img-fluid">Casas
                            @endif
                        </a>
                    </div>
                    <div class="item justify-content-center">
                        <a href="{{ route('inicio')}}?co_tipo_garantia=2" style="color: {{ (request('co_tipo_garantia') == 2) ? '#F59C1A' : '#00266A' }} !important;">
                            @if(request('co_tipo_garantia') == 2)
                            <img src="img/vector/deparmentamarillo.svg" class="img-fluid">Departamento
                            @else
                            <img src="img/vector/department.svg" class="img-fluid">Departamento
                            @endif
                        </a>
                    </div>
                    <div class="item justify-content-center">
                        <a href="{{ route('inicio')}}?co_tipo_garantia=3" style="color: {{ (request('co_tipo_garantia') == 3) ? '#F59C1A' : '#00266A' }} !important;">
                            @if(request('co_tipo_garantia') == 3)
                            <img src="img/vector/localamarillo.svg" class="img-fluid">Local Comercial
                            @else
                            <img src="img/vector/local.svg" class="img-fluid">Local Comercial
                            @endif
                        </a>
                    </div>
                    <div class="item justify-content-center">
                        <a href="{{ route('inicio')}}?co_tipo_garantia=4" style="color: {{ (request('co_tipo_garantia') == 4) ? '#F59C1A' : '#00266A' }} !important;">
                            @if(request('co_tipo_garantia') == 4)
                            <img src="img/vector/granjaamarillo.svg" class="img-fluid">Granja/Finca
                            @else
                            <img src="img/vector/granja.svg" class="img-fluid">Granja/Finca
                            @endif
                        </a>
                    </div>
                    <div class="item justify-content-center">
                        <a href="{{ route('inicio')}}?co_tipo_garantia=5" style="color: {{ (request('co_tipo_garantia') == 5) ? '#F59C1A' : '#00266A' }} !important;">
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
    <!--mobil-->
    <div class="container-fluid seccion-2  d-lg-none d-md-block d-sm-block d-xs-block menu-mobil bg-light position-fixed fixed-top pt-4 pb-2" style="z-index: 99;">
        <div class="col-12 d-flex pb-4 align-items-center">
            <form action="{{ route('inicio')  }}" class="col-12 d-flex align-items-center">
                <div class="col-12 d-flex bg-white rounded-pill border border-1 p-2">
                    <div class="col-2 rounded-circle d-flex align-items-center justify-content-center">
                        <button type="submit" style="background-color: transparent; border: none;">
                            <img src="./img/vector/searchmobil.png" alt="">
                        </button>
                    </div>
                    <div class="col-10">
                        <select class="js-example-basic-single w-100" name="distrito">
                            <option value="" selected>Buscar por provincia</option>
                            @foreach($provincias as $item)
                                <option value="{{ $item->co_provincia }}" {{ session('distrito') == $item->co_provincia ? 'selected' : '' }}>
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
                    <a href="{{ route('inicio')}}?co_tipo_garantia=1" style="color: {{ (request('co_tipo_garantia') == 1) ? '#F59C1A' : '#00266A' }} !important;">
                        @if(request('co_tipo_garantia') == 1)
                            <img src="img/vector/homeamarillo.svg" class="img-fluid">Casas
                        @else
                            <img src="img/vector/home.svg" class="img-fluid">Casas
                        @endif
                    </a>
                </div>
                <div class="item">
                    <a href="{{ route('inicio')}}?co_tipo_garantia=2" style="color: {{ (request('co_tipo_garantia') == 2) ? '#F59C1A' : '#00266A' }} !important;">
                        @if(request('co_tipo_garantia') == 2)
                        <img src="img/vector/deparmentamarillo.svg" class="img-fluid">Departamento
                        @else
                        <img src="img/vector/department.svg" class="img-fluid">Departamento
                        @endif
                    </a>
                </div>
                <div class="item">
                    <a href="{{ route('inicio')}}?co_tipo_garantia=3" style="color: {{ (request('co_tipo_garantia') == 3) ? '#F59C1A' : '#00266A' }} !important;">
                        @if(request('co_tipo_garantia') == 3)
                        <img src="img/vector/localamarillo.svg" class="img-fluid">Local Comercial
                        @else
                        <img src="img/vector/local.svg" class="img-fluid">Local Comercial
                        @endif
                    </a>
                </div>
                <div class="item">
                    <a href="{{ route('inicio')}}?co_tipo_garantia=4" style="color: {{ (request('co_tipo_garantia') == 4) ? '#F59C1A' : '#00266A' }} !important;">
                        @if(request('co_tipo_garantia') == 4)
                        <img src="img/vector/granjaamarillo.svg" class="img-fluid">Granja/Finca
                        @else
                        <img src="img/vector/granja.svg" class="img-fluid">Granja/Finca
                        @endif
                    </a>
                </div>
                <div class="item">
                    <a href="{{ route('inicio')}}?co_tipo_garantia=5" style="color: {{ (request('co_tipo_garantia') == 5) ? '#F59C1A' : '#00266A' }} !important;">
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

    <div class="container-fluid m-auto text-center justify-content-center contenido">
        <div class="col-12 mt-4">
            <a href="{{ route('inicio') }}" class="btn btn-primary text-center btnmaps mb-3 p-3" style="color: white;background-color:#01276A">
                <img src="./img/vector/grillablanca.svg" alt="grillablanca" class="m-2">
                Volver
            </a>
        </div>
    </div>

    <div id="mapa-all"></div>

    <div class="container-fluid text-center p-3 mt-4" style="background-color:#E3E3E3">
        <a href="https://ginversiones.pe" style="text-decoration: none; color:#01276A">www.ginversiones.pe</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.min.js"></script>
     <!--modal menu filtro-->
    <div class="container-fluid wiguet d-lg-none d-md-block d-sm-block d-xs-block menu-mobil bg-light position-fixed fixed-bottom pt-3 pb-3" style="z-index: 99;">
        <div class="col-12 inmuebles-mobil">
            <div class="row align-items-evenly justify-content-center inmuebles-mobil">
                <div class="col-4">
                    <a href="{{ route('inicio')}}" style="color:#F59C1A !important" class="d-block">
                        <img src="img/vector/searchmobilamarillo.png" class="img-fluid mx-auto" alt="Casas">
                        <p class="mt-2 text-center"style="color:#F59C1A !important" >Buscar</p>
                    </a>
                </div>
                <div class="col-4">
                    <a href="{{ route('favoritos')}}" style="color:#01276A !important" class="d-block">
                        <img src="img/vector/corazonazul.png" class="img-fluid mx-auto" alt="Departamento">
                        <p class="mt-2 text-center"style="color:#01276A" >Favoritos</p>
                    </a>
                </div>
                <div class="col-4">
                    <a href="{{ route('inicio')}}?co_tipo_garantia=3" style="color:#01276A !important" class="d-block">
                        <img src="/img/vector/user-mobile.png" class="img-fluid mx-auto" alt="Local Comercial">
                        <p class="mt-2 text-center"style="color:#01276A" >Perfil</p>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!--ow carrousel-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/waypoints/2.0.3/waypoints.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js" integrity="sha384-EkEiLB/XwiqW7GOq6CQGxZ9Fqf6GoUjA8jGx3+HHgjnGQQlnarwwy8zoD4lF1zP" crossorigin="anonymous"></script>
    
    <script>
        $(document).ready(function(){
            $('.owl-carousel').owlCarousel({
                loop: false,
                margin: 0,
                responsiveClass: true,
                nav: false,
                dots: false,
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
            });
        });
    </script>
    <script>
        var lastScrollTop = 0;
        $(window).scroll(function() {
          var st = $(this).scrollTop();
          // Verifica si el scroll es hacia abajo y la distancia es mayor a 50 píxeles
          if (st > lastScrollTop && st > 50) {
            // Oculta el elemento al hacer scroll hacia abajo
            $('.wiguet').slideUp();
          } else {
            // Muestra el elemento al hacer scroll hacia arriba
            $('.wiguet').slideDown();
          }
          lastScrollTop = st;
        });
      </script>
    <script>
        $(document).ready(function() {
            $('.js-example-basic-single').select2();
        });
    </script>

    <script>
        (g=>{var h,a,k,p="The Google Maps JavaScript API",c="google",l="importLibrary",q="__ib__",m=document,b=window;b=b[c]||(b[c]={});var d=b.maps||(b.maps={}),r=new Set,e=new URLSearchParams,u=()=>h||(h=new Promise(async(f,n)=>{await (a=m.createElement("script"));e.set("libraries",[...r]+"");for(k in g)e.set(k.replace(/[A-Z]/g,t=>"_"+t[0].toLowerCase()),g[k]);e.set("callback",c+".maps."+q);a.src=`https://maps.${c}apis.com/maps/api/js?`+e;d[q]=f;a.onerror=()=>h=n(Error(p+" could not load."));a.nonce=m.querySelector("script[nonce]")?.nonce||"";m.head.append(a)}));d[l]?console.warn(p+" only loads once. Ignoring:",g):d[l]=(f,...n)=>r.add(f)&&u().then(()=>d[l](f,...n))})({
        key: "AIzaSyDEqrm40w_9z8RNaWY9h-P_tFvJP8m9tK0",
        v: "weekly",
        });
    
        
        let position = {
            lat: -12.0973182,
            lng: -77.0233135
        };

        let locations = @json($solicitantes);
        locations = locations.map(location => {
            if (location.latitud && location.longitud) {
                return {lat: Number(location.latitud), lng: Number(location.longitud)}
            }
            
            return position;
        });
    
        let map;
    
        async function initMap() {
            const { Map, Circle } = await google.maps.importLibrary("maps");
            // Create the map.
            map = new Map(document.getElementById("mapa-all"), {
                center: position,
                zoom: 10,
                gestureHandling: 'greedy',
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

            var circles = [];
    
            // Add the circle for this city to the map.
            locations.forEach(location => {
                var circle = new Circle({
                    strokeColor: "#F59C1A",
                    strokeOpacity: 0.8,
                    strokeWeight: 2,
                    fillColor: "#F59C1A",
                    fillOpacity: 0.35,
                    map,
                    center: location,
                    radius: calculateRadius(map.getZoom()),
                });
                circles.push(circle);
            });
            
            map.addListener('zoom_changed', function() {
                var zoom = map.getZoom();
                circles.forEach(function(circle) {
                    circle.setRadius(calculateRadius(zoom));
                });
            });
        }

        function calculateRadius(zoom) {
            var dynamicRadius = Math.pow(2, 20 - zoom) * 20;
            return Math.max(400, Math.min(dynamicRadius, 5000));
        }
    
        initMap();
    </script>
</body>
</html>
