<?php
require(dirname(__FILE__) . '/models/employee.php');
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="icon" type="image/vnd.microsoft.icon" href="./assets/img/favicon.ico" />
    <link rel="shortcut icon" type="image/x-icon" href="./assets/img/favicon.ico" />

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/bootstrap-datepicker.standalone.min.css">
    <link rel="stylesheet" href="assets/css/bootstrapValidator.min.css">
    <link rel="stylesheet" href="assets/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">

    <title>GESTION POINTAGE KIBO | SANIFER | TALYS</title>
  </head>
  <body>
    <nav class="navbar navbar-expand-lg py-3 navbar-dark bg-white shadow-sm">
      <div class="container">
        <a href="#" onclick="window.location.href=window.location.pathname" class="navbar-brand">
          <img src="assets/img/logo_talys.png" alt="TALYS" class="d-inline-block align-middle mr-2" width="200">
          <span class="text-uppercase font-weight-bold">SANIFER</span>
        </a>

        <button type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler"><span class="navbar-toggler-icon"></span></button>

        <div class="gg">
          <h2>
            GESTION POINTAGE <br> KIBO | SANIFER | TALYS
          </h2>
        </div>
      </div>
    </nav>
  <form class="form_" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <section class="py-5">
      <div class="container">

        <div class="row">


          <div class="col-sm">
            <div class="form-group">
              <label for="site">Site</label>
              <select class="form-control form-control-xs selectpicker selectpicker__" data-size="7" data-live-search="true" data-title="SANIFER 1" data-width="100%" id="site" name="site">
                <?php //echo $option_site;?>
                <option value="1">KIBO</option>
                <option value="2">SANIFER 1</option>
                <option value="3">SANIFER 2</option>
                <option value="4">SANIFER 3</option>
                <option value="5">SANIFER 4</option>
                <option value="6">TALYS</option>
              </select>
              <input id="site_" type="hidden" value="<?php echo $site_;?>" />
            </div>
          </div>


          <div class="col-sm">
            <div class="form-group">
              <label for="employées">Employées</label>
              <select class="form-control form-control-xs selectpicker selectpicker__" data-size="7" data-live-search="true" data-title="Tous les employées" data-width="100%" id="badge" name="badge">
                <option value="">Tous les employées</option>
                <?php echo $option_employee;?>
              </select>
              <input id="num" type="hidden" value="<?php echo $num;?>" />
              <input id="e" name="e" type="hidden" value="<?php echo $e;?>" />
            </div>
          </div>

          <div class="col-sm">
            <div class="form-group">
              <label for="start">Date de Début</label>
              <input id="start" name="start" type="text" class="form-control" value="<?php echo date('d/m/Y',strtotime($date_debut));?>" />
            </div>
          </div>

          <div class="col-sm">
            <div class="form-group">
              <label for="end">Date de Fin</label>
              <input id="end" name="end" type="text" class="form-control" value="<?php echo date('d/m/Y',strtotime($date_fin));?>" />
            </div>
          </div>

          <div class="col-sm">
            <div class="form-group">
              <label for="type">Type</label>
              <select class="form-control form-control-xs selectpicker selectpicker_" data-size="7" data-live-search="true" data-title="Type" data-width="100%" id="type" name="type">
                <option value="">Type</option>
                <option value="a">Absence</option>
                <option value="r">Rétard</option>
              </select>
              <input id="type_" type="hidden" value="<?php echo $type;?>" />
            </div>
          </div>         

        </div>

         <div class="row float-left">
            <div class="col-md-12 float-left">
              <button type="button" type="submit" class="btn btn-primary valider">Valider</button>
              <button type="button" class="btn btn-success excel_">Export en Excel</button>
              <div class="chargement">
                <button class="btn btn-primary" type="button" disabled>
                  <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                  Chargement...
                </button>
              </div>
            </div>
        </div>

      </div>
    </section>

  <div class="container">
    <div class="table-responsive">

      <table id="dtBasicExample" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">

        <thead>
          <tr>
            <th>Date</th>
            <th>N°</th>
            <th>Nom et Prénom</th>
            <th>Département</th>
            <th>Société</th>
            <th>Qualification</th>
            <th>Entree</th>
            <th>Sortie</th>
            <th>Entree</th>
            <th>Sortie</th>
            <th>Total horaire</th>
          </tr>
        </thead>

        <tbody>
          <?php echo $table;?>
        </tbody>

      </table>

    </div>
  </div>
</form>

    <script src="assets/js/jquery-3.5.1.min.js" ></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js" ></script>
    <script src="assets/js/bootstrapValidator.min.js"></script>
    <script src="assets/js/moment.min.js"></script>
    <script src="assets/js/bootstrap-datepicker.min.js"></script>
    <script src="assets/js/jquery.dataTables.min.js"></script>
    <script src="assets/js/js.js" ></script>

  </body>
</html>