/**
*
* Formulário de Edição
*
*/

<?php echo $this->extend('Adm/Layout/main'); ?>
<!-- ************************************************************** -->

<!-- Aqui enviamos para os template main (principal) os estilos -->
<?php echo $this->section('titulo'); ?>
<?php echo $title; ?>
<?php echo $this->endSection() ?>
<!-- ************************************************************** -->

<?php echo $this->section('style') ?>
<!-- Aqui enviamos para os template main (principal) os estilos -->

<?php echo $this->endSection() ?>
<!-- ************************************************************** -->


<?php echo $this->section('content') ?>
<!-- Aqui enviamos para os template principal (main) o content (conteúdos) -->
<div class="row">


    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header bg-primary pb-0 pt-4">
                <h4 class="card-title text-white"> <?php echo esc($title); ?></h4>
            </div>


            <div class="card-body">
                <!-- Erros de formulário -->
                <?php if (session()->has('errors_model')): ?>

                    <ul>
                        <?php foreach (session('errors_model') as $error): ?>

                            <li class="text-danger"><?php echo $error; ?></li>

                        <?php endforeach; ?>

                    </ul>

                <?php endif ?>

                <!-- Envia os dados para update (atualizar) -->
                <?php echo form_open("adm/users/update/$user->id");?>
                    
                    <?php echo $this->include('Adm/Users/form'); ?>
                    
                    <!-- botão voltar -->
                    <a href="<?php echo site_url("adm/users/show/$user->id"); ?>" class="btn btn-light text-dark btn-sm">
                        <i class="mdi mdi-arrow-left btn-icon-prepend"></i>
                        Voltar
                    </a>
                    
                <?php echo form_close();?>

            </div>
        </div>
    </div>
</div>
<?php echo $this->endSection() ?>
<!-- ************************************************************** -->


<?php echo $this->section('scriptJs') ?>
<!-- Aqui enviamos para o template principal (main) os scripts ) -->
<script src="<?php echo site_url('adm/vendors/mask/app.js') ;?>"> </script>
<script src="<?php echo site_url('adm/vendors/mask/jquery.mask.min.js') ;?>"> </script>


<?php echo $this->endSection() ?>