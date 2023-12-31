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
                <div class="text-center">
                    <?php if ($product->image && $product->deleted_at == null) { ?>
                            <img class="card-img-top w-75" src="<?php echo site_url("adm/products/image/$product->image"); ?>" alt="<?php echo esc($product->name); ?>">
                    <?php } else { ?>
                        <img class="card-img-top w-75" src="<?php echo site_url('adm/images/product-without-image.jpg'); ?>" alt="Produto sem imagem">
                    <?php } ?>
                </div>
                
                <?php if ($product->deleted_at == null): ?>
                    <hr>
                        <!--- Botão Editar imagem -->
                        <a href="<?php echo site_url("adm/products/editimage/$product->id"); ?>" class="btn btn-outline-primary mt-2 mb-2 btn-sm">
                        <i class="mdi mdi-image btn-icon-prepend"></i>
                            Editar imagem
                        </a>
                    <hr>

                <?php endif; ?>
           
                <p class="card-text">
                    <span class="font-weight-bold">Nome:</span>
                    <?php echo esc($product->name); ?>
                </p>

                <p class="card-text">
                    <span class="font-weight-bold">Categoria :</span>
                    <?php echo $product->category; ?>
                </p>
                                   
                <p class="card-text">
                    <span class="font-weight-bold">Slug:</span>
                    <?php echo esc($product->slug); ?>
                </p>
                <p class="card-text">
                    <span class="font-weight-bold">Ativo:</span>
                    <?php echo $product->active ? 'Sim' : 'Não'; ?>
                </p>
               
                <p class="card-text">
                    <span class="font-weight-bold">Criado:</span>
                    <?php echo $product->created_at->humanize(); ?>
                </p>

                <?php if ($product->deleted_at == null) { ?>
                    <p class="card-text">
                        <span class="font-weight-bold">Atualizado:</span>
                        <?php echo $product->modified_at->humanize(); ?>
                    </p>

                <?php } else { ?>
                    <p class="card-text">
                        <span class="font-weight-bold text-danger">Excluído:</span>
                        <?php echo $product->deleted_at->humanize(); ?>
                    </p>


                <?php } ?>

                <div class="mt-4">

                    <?php if ($product->deleted_at == null) { ?>
                        <!--- Botão Editar -->
                        <a href="<?php echo site_url("adm/products/edit/$product->id"); ?>" class="btn btn-dark btn-sm mr-2">
                            <i class="mdi mdi-pencil btn-icon-prepend"></i>
                            Editar
                        </a>
                        <!--- Botão Extras -->
                        <a href="<?php echo site_url("adm/products/extras/$product->id"); ?>" class="btn btn-outline-success btn-sm mr-2">
                            <i class="mdi mdi-pencial btn-icon-prepend"></i>
                            Extras
                        </a>
                        <!--- Botão Extras -->
                        <a href="<?php echo site_url("adm/products/specification/$product->id"); ?>" class="btn btn-outline-warning btn-sm mr-2">
                            <i class="mdi mdi-pencial btn-icon-prepend"></i>
                            Especificação
                        </a>


                          <!--- Botão Excluir -->
                        <a href="<?php echo site_url("adm/products/delete_product/$product->id"); ?>" class="btn btn-danger btn-sm mr-2">
                            <i class="mdi mdi-trash-can btn-icon-prepend"></i>
                            Excluir
                        </a>


                        <!--- Botão Voltar-->
                        <a href="<?php echo site_url('adm/products'); ?>" class="btn btn-light text-dark btn-sm">
                            <i class="mdi mdi-arrow-left btn-icon-prepend"></i>
                            Voltar
                        </a>

                    <?php } else { ?>
                        <!--- Botão Desfazer Exclusão -->
                        <a href="<?php echo site_url("adm/products/undodelete/$product->id"); ?>" class="btn btn-dark btn-sm" data-toggle="tooltip" data-placement="top" title="Desfazer  a exclusão">
                            <i class="mdi mdi-undo btn-icon-prepend"></i>
                            Desfazer
                        </a>
                        <!--- Botão Voltar-->
                        <a href="<?php echo site_url('adm/products'); ?>" class="btn btn-light text-dark btn-sm" data-toggle="tooltip" data-placement="top" title="Voltar para a view">
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