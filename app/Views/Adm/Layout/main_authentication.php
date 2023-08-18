<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Food Delivery | <?php echo $this->renderSection('title') ?></title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="<?php echo site_url('adm/'); ?>vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="<?php echo site_url('adm/'); ?>vendors/base/vendor.bundle.base.css">
  <!-- endinject -->

  <!-- inject:css -->
  <link rel="stylesheet" href="<?php echo site_url('adm/'); ?>css/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="<?php echo site_url('adm/'); ?>images/favicon.png" />

  <!-- Essa section renderizará os estilos especificos da view que estender esse layout -->
  <?php echo $this->renderSection('style') ?>

</head>

<body>
  <div class="container-scroller">

  

  <!-- Essa section renderizará os conteúdos especificos da view que estender esse layout -->
  <?php echo $this->renderSection('content') ?>

    
  </div>
  <!-- container-scroller -->
  <!-- plugins:js -->
  <script src="<?php echo site_url('adm/'); ?>vendors/base/vendor.bundle.base.js"></script>
  <!-- endinject -->
  <!-- inject:js -->
  <script src="<?php echo site_url('adm/'); ?>js/off-canvas.js"></script>
  <script src="<?php echo site_url('adm/'); ?>js/hoverable-collapse.js"></script>
  <script src="<?php echo site_url('adm/'); ?>js/template.js"></script>
  <!-- endinject -->

  <!-- Essa section renderizará os scriptsJs especificos da view que estender esse layout ->
  <?php echo $this->renderSection('scriptJs') ?>
  
</body>

</html>
