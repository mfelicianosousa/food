/**
* Formulário de Edição
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

                <!-- Envia os dados para envio de imagem-->
                <?php echo form_open_multipart("adm/products/upload/$product->id"); ?>
                    
                    <div class="form-group mb-5">
                      <label>Upload de imagem</label>
                      <input type="file" name="image_product" class="file-upload-default">
                      <div class="input-group col-xs-12">
                        <input type="text" class="form-control file-upload-info" disabled placeholder="Escolha uma imagem">
                        <span class="input-group-append">
                          <button class="file-upload-browse btn btn-danger" type="button">Escolher</button>
                        </span>
                      </div>
                    </div>
                    <!-- botão Save -->
                    <button type="submit" class="btn btn-primary mr-2 btn-sm">
                        <i class="mdi mdi-checkbox-marked-circle btn-icon-prepend"></i>
                        Salvar
                    </button>
                    <!-- botão voltar -->
                    <a href="<?php echo site_url("adm/products/show/$product->id"); ?>" class="btn btn-light text-dark btn-sm">
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
<script src="<?php echo site_url('adm/js/file-upload.js'); ?>"></script>

<?php echo $this->endSection(); ?>