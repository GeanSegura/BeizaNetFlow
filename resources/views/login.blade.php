<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<link rel="stylesheet" href="CSS/loginStyle.css">
<!------ Include the above in your HEAD tag ---------->

<section class="login-block">
    <div class="container">
        <div class="row">
            <div class="col-md-4 login-sec">
                <h2 class="text-center">BeizaNetFlow</h2>


                <form class="login-form" action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="exampleInputEmail1" class="text-uppercase">Usuario</label>
                        <input type="text" class="form-control" placeholder="" name="usuario">

                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1" class="text-uppercase">Contreseña</label>
                        <input type="password" class="form-control" placeholder="" name="contrasena">
                    </div>


                    <div class="form-check">
                        <label class="form-check-label">
                            <a href={{ route('registrar')}} id="registrar-a" class="registrarse-a">Registrarse</a>
                        </label>
                        <button type="submit" class="btn btn-login float-right">Ingresar</button>
                    </div>

                </form>

                <div class="copy-text"> Gestión de lotes <i class="fa fa-heart"></i> BeizaNetFlow</div>
            </div>
            <div class="col-md-8 banner-sec">
                <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                    <ol class="carousel-indicators">
                        <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                        <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                        <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                    </ol>
                    <div class="carousel-inner" role="listbox">
                        <div class="carousel-item active">
                            <img class="d-block img-fluid" src="./img/Fondo2025.png"
                                alt="First slide">
                            <div class="carousel-caption d-none d-md-block">
                                <div class="banner-text-m">
                                    <h2>Sobre Nosotros</h2>
                                    <p>Midhco Distribuciones es una empresa con 20 años en el mercado farmacéutico, siendo líder en la zona centro del Perú y ofreciendo productos de la más alta calidad y certificación. Está guiada bajo nuestro eslogan “COMPROMISO DE INTEGRIDAD”, concepto que ha sido fundamental en la incursión de toda la familia del grupo Midhco, fundada por el matrimonio conyugal Vergara Eizaguirre.</p>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <img class="d-block img-fluid"
                                src="./img/FondoBeizaFlow.png"
                                alt="First slide">
                            <div class="carousel-caption d-none d-md-block">
                                <div class="banner-text">
                                    <h2>Sobre Nosotros</h2>
                                    <p>Midhco Distribuciones es una empresa con 20 años en el mercado farmacéutico, siendo líder en la zona centro del Perú y ofreciendo productos de la más alta calidad y certificación. Está guiada bajo nuestro eslogan “COMPROMISO DE INTEGRIDAD”, concepto que ha sido fundamental en la incursión de toda la familia del grupo Midhco, fundada por el matrimonio conyugal Vergara Eizaguirre.</p>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <img class="d-block img-fluid"
                                src="./img/Fondo2025.png"
                                alt="First slide">
                            <div class="carousel-caption d-none d-md-block">
                                <div class="banner-text">
                                    <h2>Sobre Nosotros</h2>
                                    <p>Midhco Distribuciones es una empresa con 20 años en el mercado farmacéutico, siendo líder en la zona centro del Perú y ofreciendo productos de la más alta calidad y certificación. Está guiada bajo nuestro eslogan “COMPROMISO DE INTEGRIDAD”, concepto que ha sido fundamental en la incursión de toda la familia del grupo Midhco, fundada por el matrimonio conyugal Vergara Eizaguirre.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
</section>
