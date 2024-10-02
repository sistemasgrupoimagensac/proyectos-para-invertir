<div class="container-fluid seccion-1 d-none d-lg-block d-md-block d-sm-none d-xs-none ">
        <p class="text-center">CONECTANDOMETAS CON OPORTUNIDADES | GENERA UNA RENTABILIDAD ANUAL DEA HASTA 35% ANUAL</p>
</div>
<!--desktop-->
<div class="container-fluid seccion-2  d-lg-none d-md-none d-sm-block d-xs-block menu-mobil bg-light position-fixed fixed-top" style="z-index: 99;">
    <div class="col-12 d-flex pb-4 align-items-center">
        <form action="" class="col-12 d-flex align-items-center">
            <div class="col-10 d-flex bg-white rounded-pill border border-1 p-2">
                <div class="col-2 rounded-circle d-flex align-items-center justify-content-center">
                    <img src="img/vector/searchmobil.png" alt="" class="img-fluid">
                </div>
                <div class="col-10">
                    <input type="text" id="disabledTextInput" class="form-control border-0" placeholder="Buscar..." style="border-radius: 0;">
                </div>
            </div>
            <div class="col-2 text-center  ">
                <button class="btn rounded-circle bg-white pt-2 pb-2 pl-3 pr-3"  type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasTop" aria-controls="offcanvasTop"><i class="fa-solid fa-filter"></i></button> 
            </div>
        </form> 
        
    </div>
    
    
</div>
<!--mobil-->
<div class="container-fluid seccion-2 p-3 d-block d-none d-lg-block">
    <div class="row align-items-center justify-content-evenly">
        <div class="col-lg-3">
            <img class="img-fluid logo" src="img/logo.png" alt="">
        </div>
        <div class="col-lg-4">
            <form action="{{ route('inicio') }}" method="GET">
                <div class="container opciones">
                    <div class="row rounded-pill border border-1 p-1">
                        <div class="col-5 d-flex">
                            <select class="js-example-basic-single w-100" name="distrito">
                                @foreach($distritos as $item)
                                    <option value="{{ $item->co_distrito }}">{{ $item->no_distrito }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-5 d-flex">
                            <input class="w-100 h-100 border-0 ms-4" type="text" placeholder="Monto a invertir" name="monto" value="{{ request('monto') }}">
                        </div>
                        <div class="col-2 justify-content-end d-flex">
                            <button type="submit"><img src="img/vector/search.svg" alt=""></button>
                        </div>
                    </div>
                </div>
            </form>
        </div>        
        
        <div class="row col-lg-2">
            <div class="container burger p-3">
                <div class="row justify-content-center align-items-center rounded-pill border border-1 p-2">
                    <div class="row col dropdown">
                        <button class="btn dropdown-toggle" type="button" id="burgerMenu" data-bs-toggle="dropdown" aria-expanded="false">
                            &#9776;
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="burgerMenu">
                            <li><a class="dropdown-item" href="#">Action</a></li>
                            <li><a class="dropdown-item" href="#">Another action</a></li>
                            <li><a class="dropdown-item" href="#">Something else here</a></li>
                        </ul>
                    </div>
                    <div class="row col">
                        <img class="rounded-circle" src="img/vector/perfil.png" style="height:3.5rem;" alt="profile">
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid border-1 border-top border-bottom p-3">
            <div class="row">
                <div class="col-8">
                    <div class="owl-carousel owl-theme">
                        <div class="item"><a href="{{ route('inicio')}}?co_tipo_garantia=1">
                            <img src="img/vector/home.svg"class="img-fluid">Casas</a>
                        </div>
                        <div class="item"><a href="{{ route('inicio')}}?co_tipo_garantia=2">
                            <img src="img/vector/department.svg"class="img-fluid">Departamento</a></div>
                        <div class="item"><a href="{{ route('inicio')}}?co_tipo_garantia=3"><img src="img/vector/local.svg"class="img-fluid">Local Comercial</a></div>
                        <div class="item"><a href="{{ route('inicio')}}?co_tipo_garantia=4"><img src="img/vector/granja.svg"class="img-fluid">Granja/Finca</a></div>
                        <div class="item"><a href="{{ route('inicio')}}?co_tipo_garantia=4"><img src="img/vector/terreno.svg"class="img-fluid">Terreno</a></div>
                    </div>
                </div>
                <div class="justify-content-eve col-4 d-flex align-items-center">
            
                    <div class=""><img src="img/vector/lista.svg" alt=""> Lista</div>
                    <div><img src="img/vector/grilla.svg" alt=""> Grilla</div>
                </div>
            </div>
        </div>
        
    </div>
</div>