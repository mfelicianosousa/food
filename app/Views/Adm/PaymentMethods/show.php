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


    <div class="col-lg-6 grid-margin stretch-card">
        <div class="card">

            <div class="card-header bg-primary pb-0 pt-4">

                <h4 class="card-title text-white"><?php echo esc($title); ?></h4>

            </div>

            <div class="card-body">
                <?php if ($paymentMethod->id == 1) { ?>

                    <div class="alert alert-primary" role="alert">
                        A forma de pagamento <strong><?php echo esc($paymentMethod->name); ?></strong>
                        não pode ser <span class="text-danger">editada ou excluída</span>, pois está opção sera vinculada ou não o envio de troco para o cliete
                        quando o mesmo estiver no <strong>Checkout</strong>

                    </div>

                <?php } ?>

                <p class="card-text">
                    <span class="font-weight-bold">Nome:</span>
                    <?php echo esc($paymentMethod->name); ?>
                </p>
                                   
               
                <p class="card-text">
                    <span class="font-weight-bold">Ativo:</span>
                    <?php echo $paymentMethod->active ? 'Sim' : 'Não'; ?>
                </p>
               
                <p class="card-text">
                    <span class="font-weight-bold">Criado:</span>
                    <?php echo $paymentMethod->created_at->humanize(); ?>
                </p>

                <?php if ($paymentMethod->deleted_at == null) { ?>
                    <p class="card-text">
                        <span class="font-weight-bold">Atualizado:</span>
                        <?php echo $paymentMethod->modified_at->humanize(); ?>
                    </p>

                <?php } else { ?>
                    <p class="card-text">
                        <span class="font-weight-bold text-danger">Excluído:</span>
                        <?php echo $paymentMethod->deleted_at->humanize(); ?>
                    </p>


                <?php } ?>

                <div class="mt-4">

                    <?php if ($paymentMethod->deleted_at == null) { ?>
                       
                        <?php if ($paymentMethod->id != 1) { ?>
                            <!--- Botão Editar -->
                                                    
                            <a href="<?php echo site_url("adm/payment/edit/$paymentMethod->id"); ?>" class="btn btn-dark btn-sm mr-2">
                                <i class="mdi mdi-pencil btn-icon-prepend"></i>
                                Editar
                            </a>
                       
                            <!--- Botão Excluir -->
                            <a href="<?php echo site_url("adm/payment/delete/$paymentMethod->id"); ?>" class="btn btn-danger btn-sm mr-2">
                                <i class="mdi mdi-trash-can btn-icon-prepend"></i>
                                Excluir
                            </a>

                        <?php } ?>


                        <!--- Botão Voltar-->
                        <a href="<?php echo site_url('adm/payment'); ?>" class="btn btn-light text-dark btn-sm">
                            <i class="mdi mdi-arrow-left btn-icon-prepend"></i>
                            Voltar
                        </a>

                    <?php } else { ?>
                        <!--- Botão Desfazer Exclusão -->
                        <a href="<?php echo site_url("adm/payment/undodelete/$paymentMethod->id"); ?>" class="btn btn-dark btn-sm" data-toggle="tooltip" data-placement="top" title="Desfazer  a exclusão">
                            <i class="mdi mdi-undo btn-icon-prepend"></i>
                            Desfazer
                        </a>
                        <!--- Botão Voltar-->
                        <a href="<?php echo site_url('adm/payment'); ?>" class="btn btn-light text-dark btn-sm" data-toggle="tooltip" data-placement="top" title="Voltar para a view">
                            <i class="mdi mdi-arrow-left btn-icon-prepend"></i>
                            Voltar
                        </a>

                    <?php } ?>

                </div>

            </div>

        </div>
    </div>
</div>
<?php echo $this->endSection(); ?>
<!-- ************************************************************** -->


<?php echo $this->section('scriptJs'); ?>
<!-- Aqui enviamos para o template principal (main) os scripts ) -->

<?php echo $this->endSection(); ?>