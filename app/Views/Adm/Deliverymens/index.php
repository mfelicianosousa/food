<?php echo $this->extend('Adm/Layout/main'); ?>
<!-- ************************************************************** -->

<!-- Aqui enviamos para os template main (principal) os estilos -->
<?php echo $this->section('titulo'); ?>
<?php echo $title; ?>
<?php echo $this->endSection(); ?>
<!-- ************************************************************** -->

<?php echo $this->section('style'); ?>
<!-- Aqui enviamos para os template main (principal) os estilos -->

<link rel="stylesheet" href="<?php echo site_url('adm/vendors/auto-complete/jquery-ui.css'); ?>" />

<?php echo $this->endSection(); ?>
<!-- ************************************************************** -->


<?php echo $this->section('content'); ?>
<!-- Aqui enviamos para os template principal (main) o content (conteúdos) -->
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header bg-primary pb-0 pt-4">
                <h4 class="card-title text-white"><?php echo esc($title); ?></h4>
            </div>
            <div class="card-body">

                <div class="ui-widget">
                    <!--label for="query">Pesquisar:</label> -->
                    <input id="query" name="query" placeholder="Pesquise por um entregador" class="form-control bg-light mb-5">
                </div>
                <a href="<?php echo site_url('adm/deliverymens/create'); ?>" class="btn btn-success float-right mt-1 mb-5">
                    <i class="mdi mdi-plus btn-icon-prepend"></i>
                    Cadastrar
                </a>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Nome</th>
                                <th>Telefone</th>
                                <th>Placa</th>
                                <th>Ativo</th>
                                <th>Situação</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($deliverymens as $deliverymen) { ?>

                                <tr>
                                    <td class="py-1">
                                        <?php if ($deliverymen->image) { ?>

                                            <img src="<?php echo site_url("adm/deliverymens/image/$deliverymen->image"); ?>" alt="<?php echo esc($deliverymen->name); ?>"/>

                                        <?php } else { ?>
                                            <img src="<?php echo site_url('adm/'); ?>images/deliverymen-no-image.png" alt="Sem imagem"/>
                                            
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <a href="<?php echo site_url("adm/deliverymens/show/$deliverymen->id"); ?>"> <?php echo $deliverymen->name; ?></a>
                                    </td>
                                    
                                    <td> <?php echo $deliverymen->phone_celular; ?></td>
                                    <td> <?php echo $deliverymen->vehicle_plate; ?></td>
                                    <td> <?php echo $deliverymen->active && $deliverymen->deleted_at == null ? '<label class="badge badge-primary">Sim</label>' : '<label class="badge badge-danger">Não</label>'; ?></td>
                                    <td> 
                                        <?php echo $deliverymen->deleted_at == null ? '<label class="badge badge-primary">Disponível</label>' : '<label class="badge badge-danger"> Excluído </label>'; ?>
                                        <?php if ($deliverymen->deleted_at != null) { ?>
                                            <a href="<?php echo site_url("adm/deliverymens/undodelete/$deliverymen->id"); ?>" class="badge badge-dark ml-2"> 
                                                <i class="mdi mdi-undo btn-icon-prepend"></i>
                                                Desfazer
                                            </a>


                                        <?php } ?>
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
            </div>
        </div>
    </div>
</div>
<?php echo $this->endSection(); ?>
<!-- ************************************************************** -->


<?php echo $this->section('scriptJs'); ?>
<!-- Aqui enviamos para o template principal (main) os scripts ) -->
<script src="<?php echo site_url('adm/vendors/auto-complete/jquery-ui.js'); ?>"></script>
<script>
    $(function() {

        $("#query").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "<?php echo site_url('adm/deliverymens/search'); ?>",
                    dataType: "json",
                    data: {
                        term: request.term
                    },
                    success: function(data) {
                        if (data.length < 1) {
                            var data = [{
                                label: 'Entregador não encontrado!',
                                value: -1
                            }];
                        }
                        response(data); // aqui temos o valor no data
                    },
                }); // fim do ajax
            },
            minLenght: 1,
            select: function(event, ui) {
                if (ui.item.values == -1) {
                    $(this).val("");
                    return false;
                } else {
                    window.location.href = '<?php echo site_url('adm/deliverymens/show/'); ?>' + ui.item.id;
                }
            }

        }); // fim autocomplete

    });
</script>
<?php echo $this->endSection(); ?>