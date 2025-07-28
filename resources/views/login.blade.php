<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<link rel="stylesheet" href="CSS/loginStyle.css">
<!------ Include the above in your HEAD tag ---------->

<section class="login-block">
    <div class="container">
        <div class="row">
            <div class="col-md-4 login-sec">
                <h2 class="text-center">BeizaNetFlow</h2>


                <form id="form-login" class="login-form" action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="exampleInputEmail1" class="text-uppercase">Usuario</label>
                        <input type="text" class="form-control" placeholder="" name="usuario" id="input-usuario">

                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1" class="text-uppercase">Contreseña</label>
                        <input type="password" class="form-control" placeholder="" name="contrasena" id="input-contrasena">
                    </div>

                     <input type="hidden" name="opcionFuncionalidad" id="opcionFuncionalidad" >

                    <div class="form-check">
                        <label class="form-check-label">
                            <a href={{ route('registrar')}} id="registrar-a" class="registrarse-a">Registrarse</a>
                        </label>
                        <button type="submit" class="btn btn-login float-right" id="btn-ingresar">Ingresar</button>
                    </div>

                    <div class="p-3 text-danger bg-primary-subtle rounded-3 validacion-div" id="div-mensaje-validacion">
                        @isset($mensaje)
                        {{ $mensaje }}
                    @else

                    @endisset
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
                                <div class="banner-text-m">
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
                                <div class="banner-text-m">
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

<section>
<!-- Modal -->
<div class="modal fade" id="modalInicio" tabindex="-1" role="dialog" aria-labelledby="modalInicioLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div id="modal-inicio-funcionalidad" class="modal-content p-4 text-center">
      <div class="modal-header border-0">
        <h5 class="modal-title header-modal" id="modalInicioLabel">Seleccione una funcionalidad</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <div  class="d-flex justify-content-around flex-wrap div-opciones-modal">

          <!-- Opción 1 -->
          <div class="card opcion-modal card-opcion-pdf" style="width: 200px; height: 200px;" onclick="seleccionarOpcion('1')">
            <img src="./img/pdf.png" class="card-img-top mx-auto mt-3" style="width:120px; height:120px;" alt="PDF Lotes">
            <div class="card-body">
              <h6 class="card-title card-text-pdf">Subir PDF de Lotes</h6>
            </div>
          </div>

          <!-- Opción 2 -->
          <div class="card opcion-modal card-opcion-excel" style="width: 200px; height: 200px;" onclick="seleccionarOpcion('2')">
            <img src="./img/xls.png" class="card-img-top mx-auto mt-3" style="width:120px; height:120px;" alt="Precios Excel">
            <div class="card-body">
              <h6 class="card-title card-text-excel">Visualizar Precios Artículos (Excel)</h6>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>



</section>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="./scripts/login.js"></script>
