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
                            <input type="checkbox" class="form-check-input">
                            <small>Registrarse</small>
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
                            <img class="d-block img-fluid" src="https://static.pexels.com/photos/33972/pexels-photo.jpg"
                                alt="First slide">
                            <div class="carousel-caption d-none d-md-block">
                                <div class="banner-text">
                                    <h2>Sobre Nosotros</h2>
                                    <p>En Midhco Distribuciones, a través de BeizaNetFlow, optimizamos la gestión de
                                        lotes con AWS, gracias a nuestra alianza como socios de AWS. Juntos,
                                        automatizamos procesos y agilizamos la eficiencia de tu empresa, impulsando el
                                        futuro digital</p>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <img class="d-block img-fluid"
                                src="https://images.pexels.com/photos/7097/people-coffee-tea-meeting.jpg"
                                alt="First slide">
                            <div class="carousel-caption d-none d-md-block">
                                <div class="banner-text">
                                    <h2>Sobre Nosotros</h2>
                                    <p>En Midhco Distribuciones, a través de BeizaNetFlow, optimizamos la gestión de
                                        lotes con AWS, gracias a nuestra alianza como socios de AWS. Juntos,
                                        automatizamos procesos y agilizamos la eficiencia de tu empresa, impulsando el
                                        futuro digital</p>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <img class="d-block img-fluid"
                                src="https://images.pexels.com/photos/872957/pexels-photo-872957.jpeg"
                                alt="First slide">
                            <div class="carousel-caption d-none d-md-block">
                                <div class="banner-text">
                                    <h2>Sobre Nosotros</h2>
                                    <p>En Midhco Distribuciones, a través de BeizaNetFlow, optimizamos la gestión de
                                        lotes con AWS, gracias a nuestra alianza como socios de AWS. Juntos,
                                        automatizamos procesos y agilizamos la eficiencia de tu empresa, impulsando el
                                        futuro digital</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
</section>
