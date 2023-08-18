<?php echo $this->extend('Adm/Layout/main_authentication'); ?>
<!-- ************************************************************** -->

<!-- Aqui enviamos para os template main (principal) os estilos -->
<?php echo $this->section('title'); ?>
<?php echo $title; ?>
<?php echo $this->endSection() ?>
<!-- ************************************************************** -->

<?php echo $this->section('style') ?>
<!-- Aqui enviamos para os template main (principal) os estilos -->

<?php echo $this->endSection() ?>
<!-- ************************************************************** -->


<?php echo $this->section('content') ?>
<!-- Aqui enviamos para os template principal (main) o content (conteúdos) -->
<div class="container-fluid page-body-wrapper full-page-wrapper">
  <div class="content-wrapper d-flex align-items-center auth px-0">
    <div class="row w-100 mx-0">
      <div class="col-lg-4 mx-auto">
        <div class="auth-form-light text-left py-5 px-4 px-sm-5">



          <!-- Mensagem de validação de sessão (flash data) -->
          <!-- Alert success-->
          <?php if (session()->has('success')) : ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              <strong>Sucesso!</strong> <?php echo session('success'); ?>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          <?php endif; ?>
          <!-- Alert Info -->
          <?php if (session()->has('info')) : ?>
            <div class="alert alert-info alert-dismissible fade show" role="alert">
              <strong>Informação!</strong> <?php echo session('info'); ?>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          <?php endif; ?>

          <!-- Alert Atençao -->

          <?php if (session()->has('attention')) : ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <strong>Atenção!</strong> <?php echo session('attention'); ?>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          <?php endif; ?>

          <!-- Alert Error -->
          <!-- Captura os erros de CSRF - Ação não permitida -->
          <?php if (session()->has('error')) : ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <strong>Erro!</strong> <?php echo session('error'); ?>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          <?php endif; ?>

          <!-- Fim das Mensagem de validação de sessão (flash data) -->

          <div class="brand-logo">
            <img src="<?php echo site_url('adm/') ?>images/logo.svg" alt="logo">
          </div>
          <h4>Recuperando a senha </h4>
          <h6 class="font-weight-light mb-3"><?php echo $title; ?></h6>

          <!-- Init errors formulário -->
          <?php if (session()->has('errors_model')) : ?>

            <ul>
              <?php foreach (session('errors_model') as $error) : ?>

                <li class="text-danger"><?php echo $error; ?></li>

              <?php endforeach; ?>

            </ul>

          <?php endif ?>
          <!-- End errors formulário -->






          <?php echo form_open("password/process_reset/$token") ?>


          <div class="form-group">
            <label for="password">Nova Senha</label>
            <input type="password" class="form-control" name="password" id="password" value="">
          </div>


          <div class="form-group">
            <label for="password_confirmation">Confirmação da nova Senha</label>
            <input type="password" class="form-control" name="password_confirmation" id="password_confirmation">
          </div>


          <div class="mt-3">
            <input type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" value="Redefir senha">

          </div>
          <?php echo form_close(); ?>
        </div>
      </div>
    </div>
  </div>
  <!-- content-wrapper ends -->
</div>
<!-- page-body-wrapper ends -->
<?php echo $this->endSection() ?>
<!-- ************************************************************** -->

<!-- Aqui enviamos para o template principal (main) os scripts ) -->
<?php echo $this->section('scriptJs') ?>

<?php echo $this->endSection() ?>