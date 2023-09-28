/**
* Formulário de Exclusão
*/

<?php echo $this->extend('Adm/Layout/main'); ?>
<!-- ************************************************************** -->

<!-- Aqui enviamos para os template main (principal) os estilos -->
<?php echo $this->section('titulo'); ?>
<?php echo $title; ?>
<?php echo $this->endSection(); ?>
<!-- ************************************************************** -->

<?php echo $this->section('style'); ?>
<!-- Aqui enviamos para os template main (principal) os estilos -->

<?php echo $this->endSection(); ?>
<!-- ************************************************************** -->


<?php echo $this->section('content'); ?>
<!-- Aqui enviamos para os template principal (main) o content (conteúdos) -->
<div class="row">


    <div class="col-lg-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-header bg-primary pb-0 pt-4">
                <h4 class="card-title text-white"> <?php echo esc($title); ?></h4>
            </div>


            <div class="card-body">
                <!-- Erros de formulário -->
                <?php if (session()->has('errors_model')) { ?>

                    <ul>
                        <?php foreach (session('errors_model') as $error) { ?>

                            <li class="text-danger"><?php echo $error; ?></li>

                        <?php } ?>

                    </ul>

                <?php } ?>

                <!-- Envia o registro para exclusão -->
                <?php echo form_open("adm/deliverymens/delete/$deliverymen->id"); ?>
                    
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>Atenção! </strong>Tem certeza da exclusão do entregador, <strong> <?php echo esc($deliverymen->name); ?>?</strong>
                        
                    </div>
                    <button type="submit" class="btn btn-danger mr-2 btn-sm">
                        <i class="mdi mdi-trash-can btn-icon-prepend"></i>
                        Excluir
                    </button>
                    
                    <!-- botão voltar -->
                    <a href="<?php echo site_url("adm/deliverymens/show/$deliverymen->id"); ?>" class="btn btn-light text-dark btn-sm">
                        <i class="mdi mdi-arrow-left btn-icon-prepend"></i>
                        Voltar
                    </a>
                    
                <?php echo form_close(); ?>

            </div>
        </div>
    </div>
</div>
<?php echo $this->endSection(); ?>
<!-- ************************************************************** -->


<?php echo $this->section('scriptJs'); ?>
<!-- Aqui enviamos para o template principal (main) os scripts ) -->
<?php echo $this->endSection(); ?>