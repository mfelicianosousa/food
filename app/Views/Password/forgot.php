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
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
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
          <?php echo form_open('password/processforgot') ?>

          <div class="form-group">
            <input type="email" id="email" name="email" value="<?php echo old('email') ?>" class="form-control form-control-lg" placeholder="Digite o seu email">
          </div>
          
          <div class="mt-3">
            <input type="submit" id="btn-reset-password" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" value="Recuperar senha">
          
          </div>

          <div class="mt-3 d-flex justify-content-between align-items-center font-weight-light">
             <a href="<?php echo site_url('login');?>" class="auth-link text-black">Lembrei a minha senha</a>
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

   <script>

      $("form").submit(function(){
        
        $(this).find(":submit").attr('disabled','disabled');
        $("#btn-reset-password").val("Enviando e-mail de recuperação...");
      });
  
  </script>


<?php echo $this->endSection() ?>