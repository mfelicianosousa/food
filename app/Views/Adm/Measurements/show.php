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
                <p class="card-text">
                    <span class="font-weight-bold">Nome:</span>
                    <?php echo esc($measure->name); ?>
                </p>

                <p class="card-text">
                    <span class="font-weight-bold">Ativo:</span>
                    <?php echo $measure->active ? 'Sim' : 'Não'; ?>
                </p>
               
                <p class="card-text">
                    <span class="font-weight-bold">Criado:</span>
                    <?php echo $measure->created_at->humanize(); ?>
                </p>

                <?php if ($measure->deleted_at == null) { ?>
                    <p class="card-text">
                        <span class="font-weight-bold">Atualizado:</span>
                        <?php echo $measure->modified_at->humanize(); ?>
                    </p>

                <?php } else { ?>
                    <p class="card-text">
                        <span class="font-weight-bold text-danger">Excluído:</span>
                        <?php echo $measure->deleted_at->humanize(); ?>
                    </p>


                <?php } ?>

                <div class="mt-4">

                    <?php if ($measure->deleted_at == null) { ?>
                        <!--- Botão Editar -->
                        <a href="<?php echo site_url("adm/measurements/edit/$measure->id"); ?>" class="btn btn-dark btn-sm mr-2">
                            <i class="mdi mdi-pencil btn-icon-prepend"></i>
                            Editar
                        </a>
                          <!--- Botão Excluir -->
                        <a href="<?php echo site_url("adm/measurements/excluir/$measure->id"); ?>" class="btn btn-danger btn-sm mr-2">
                            <i class="mdi mdi-trash-can btn-icon-prepend"></i>
                            Excluir
                        </a>
                        <!--- Botão Voltar-->
                        <a href="<?php echo site_url('adm/measurements'); ?>" class="btn btn-light text-dark btn-sm">
                            <i class="mdi mdi-arrow-left btn-icon-prepend"></i>
                            Voltar
                        </a>

                    <?php } else { ?>
                        <!--- Botão Desfazer Exclusão -->
                        <a href="<?php echo site_url("adm/measurements/undodelete/$measure->id"); ?>" class="btn btn-dark btn-sm" data-toggle="tooltip" data-placement="top" title="Desfazer  a exclusão">
                            <i class="mdi mdi-undo btn-icon-prepend"></i>
                            Desfazer
                        </a>
                        <!--- Botão Voltar-->
                        <a href="<?php echo site_url('adm/measurements'); ?>" class="btn btn-light text-dark btn-sm" data-toggle="tooltip" data-placement="top" title="Voltar para a view">
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