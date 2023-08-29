/**
* Formulário de Edição Extra
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


    <div class="col-lg-12 grid-margin stretch-card">
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

                <!-- Envia os dados para update (atualizar) -->
                <?php echo form_open("adm/products/registerextras/$product->id"); ?>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label> Escolha o extra do produto (opcional) </label>
                            <select class="form-control" name="extra_id">

                                <option>Ecolha</option>
                                <?php foreach ($extras as $extra):?>

                                  <option value="<?php echo $extra->id; ?>"> <?php echo esc($extra->name);?></option>
                                 
                                <?php endforeach; ?>
                            </select>
                        </div>

                    </div>

                    
                    <div class="form-row">
                        <!-- botão submit (Salvar) -->
                        <button type="submit" class="btn btn-primary mr-2 btn-sm">
                            <i class="mdi mdi-checkbox-marked-circle btn-icon-prepend"></i>
                            Inserir extra
                        </button>
                        
                        <!-- botão voltar -->
                        <a href="<?php echo site_url("adm/products/show/$product->id"); ?>" class="btn btn-light text-dark btn-sm">
                            <i class="mdi mdi-arrow-left btn-icon-prepend"></i>
                            Voltar
                        </a>
                    </div>
                    
                <?php echo form_close(); ?>
                <hr>
                <div class="form-row mt-4">
                    
                    <div class="col-md-12">

                        <?php if(empty($productsExtras)): ?>

                            <p>Produto não possui extras </p>

                        <?php else: ?>


                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<?php echo $this->endSection(); ?>
<!-- ************************************************************** -->


<?php echo $this->section('scriptJs'); ?>
<!-- Aqui enviamos para o template principal (main) os scripts ) -->
<script src="<?php echo site_url('adm/vendors/mask/app.js'); ?>"> </script>
<script src="<?php echo site_url('adm/vendors/mask/jquery.mask.min.js'); ?>"> </script>


<?php echo $this->endSection(); ?>