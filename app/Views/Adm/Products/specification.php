/**
* Formulário de Edição Especificações
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
<link rel="stylesheet" href="<?php echo site_url('adm/vendors/select2/select2.min.css'); ?>"/>
<style>
   
   .select2-container .select2-selection--single{
     
        display: block;
        width: 100%;
        height: 2.875rem;
        padding: 0.875rem 1.375rem;
        font-size: 0.875rem;
        font-weight: 400;
        line-height: 1;
        color: #495057;
        background-color: #ffffff;
        background-clip: padding-box;
        border: 1px solid #ced4da;
        border-radius: 2px;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
   }

   .select2-container--default .select2-selection--single .select2-selection__rendered {
      line-height: 18px;
   }
   .select2-container--default .select2-selection--single .select2-selection__arrow b {
      top: 80%;
   }
</style>

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
                <?php echo form_open("adm/products/registerspecification/$product->id"); ?>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Escolha a medida  do produto <a href="javascrip:void" class="" data-toggle="popover" title="Medida do Produto" data-content="Exemplo de uso para pizza: <br> Pizza grande, Pizza média, Pizza família">Entenda...</a> </label>
                            <select class="form-control js-basic-single" name="measure_id">

                                <option value="">Escolha</option>
                                <?php foreach ($measurements as $measure) { ?>

                                  <option value="<?php echo $measure->id; ?>"> <?php echo esc($measure->name); ?></option>
                                 
                                <?php } ?>
                            </select>
                        </div>

                        <div class="form-group col-md-2">
                            <label for="price">Preço</label>
                            <input type="text" class="money form-control" name="price" id="price" value="<?php echo old('price'); ?>">
                        </div>

                        <div class="form-group col-md-4">
                            <label>Produto customizável <a href="javascrip:void" class="" data-toggle="popover" title="Produto meio a meio" data-content="Exemplo de uso para pizza: <br> Metade calabreza e metade bacon com batata palha">Entenda...</a> </label>
                            <select class="form-control" name="customizable">

                                <option value="">Escolha</option>
                                <option value="1">Sim</option>
                                <option value="0">Não</option>
                                
                            </select>
                        </div>

                    </div>

                    
                    <div class="form-row">
                        <!-- botão submit (Inserir especificação) -->
                        <button type="submit" class="btn btn-primary mt-4 mr-2 btn-sm">
                            <i class="mdi mdi-checkbox-marked-circle btn-icon-prepend"></i>
                            Inserir especificação
                        </button>
                        
                        <!-- botão voltar -->
                        <a href="<?php echo site_url("adm/products/show/$product->id"); ?>" class="btn btn-light text-dark mt-4 mr-2 btn-sm">
                            <i class="mdi mdi-arrow-left btn-icon-prepend"></i>
                            Voltar
                        </a>
                    </div>
                    
                <?php echo form_close(); ?>
                <hr class="mt-5 mb-3">
                <div class="form-row">
                
                    <div class="col-md-10">

                        <?php if (empty($productSpecifications)) { ?>

                            
                            <div class="alert alert-warning" role="alert">
                                <h4 class="alert-heading">Atenção!</h4>
                                <p>Produto não possui especificações até o momento. Portanto ele <strong>não será exibido</strong> como opção de compra na área pública </p>
                                <hr>
                                <p>Aproveite agora para cadastrar pelo menos uma especificação para produto <strong><?php echo esc($product->name); ?> </strong> </p>
                            </div>
                        <?php } else { ?>

                            <h4 class="card-title">Expecificações do produto</h4>
                            <p class="card-description">
                                <code>Aproveite para gerenciar as especificações do produto</code>
                            </p>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                <thead>
                                    <tr>
                                    <th>Medida</th>
                                    <th>Preço</th>
                                    <th>Customizável</th>
                                    <th class="text-center">Remover</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($productSpecifications as $productSpecification) { ?>
                                      <tr>
                                         <td><?php echo esc($productSpecification->measure); ?></td>
                                         <td><?php echo esc($moeda); ?>&nbsp;<?php echo number_format($productSpecification->price, 2); ?></td>
                                         <td><?php echo $productSpecification->customizable ? '<label class="badge badge-primary">Sim</label>' : '<label class="badge badge-warning">Não</label>'; ?> </td>

                                         <!--- Botão Excluir -->
                                         <td class="text-center"> 
                                            
                                            <a href="<?php echo site_url("adm/products/delete_specification/$productSpecification->id/$productSpecification->product_id"); ?>" class="btn badge badge-danger btn-sm mr-2">
                                                <i class="mdi mdi-trash-can btn-icon-prepend"></i>
                                            </a>
                                         </td>
                                      </tr>
                
                                    <?php } ?>
                                </tbody>
                                </table>
                                <!-- Pagination -->
                                <div class="mt-3">
                                    
                                    <?php echo $pager->links(); ?>

                                </div>
                            </div>
                            


                        <?php } ?>
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
<script src="<?php echo site_url('adm/vendors/select2/select2.min.js'); ?>"> </script>
<script src="<?php echo site_url('adm/vendors/mask/app.js') ;?>"> </script>
<script src="<?php echo site_url('adm/vendors/mask/jquery.mask.min.js') ;?>"> </script>

<script> 
    // In your Javascript (external .js resource or <script> tag)
    $(document).ready(function() {
          
            // Click popover function
            $(function () {
                $('[data-toggle="popover"]').popover({
                    placement: 'top',
                    html: true,
                })
                
            })
            //
            $('.js-basic-single').select2({

                placeholder: "Digite o nome da medida...",
                allowClear: false,
                "language": {
                    "noResults": function() {
                        return "Medida não encontrada&nbsp;&nbsp;<a class='btn btn-primary btn-sm' href='<?php echo site_url('adm/measurements/create'); ?>'>Cadastrar</a>";
                    }
                },
                escapeMarkup: function(markup){
                    return markup;
                },

            });
    });
</script>
<?php echo $this->endSection(); ?>