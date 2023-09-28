<!-- formulário -->
<div class="form-row">
    <div class="form-group col-md-5">
        <label for="name">Nome</label>
        <input type="text" class="form-control" name="name" id="name" value="<?php echo old('name', esc($deliverymen->name)); ?>">
    </div>
   
    <div class="form-group col-md-2">
        <label for="cpf">CPF</label>
        <input type="text" class="form-control cpf" name="cpf" id="cpf" value="<?php echo old('cpf', $deliverymen->cpf); ?>">
    </div>
    <div class="form-group col-md-2">
        <label for="cnh">CNH</label>
        <input type="text" class="form-control cnh" name="cnh" id="cnh" value="<?php echo old('cnh', esc($deliverymen->cnh)); ?>">
    </div>
    <div class="form-group col-md-2">
        <label for="phone_celular">Celular</label>
        <input type="text" class="form-control sp_celphones" name="phone_celular" id="phone_celular" value="<?php echo old('phone_celular', $deliverymen->phone_celular); ?>">
    </div>

</div>

<div class="form-row">
    <div class="form-group col-md-5">
        <label for="email">E-mail</label>
        <input type="text" class="form-control" name="email" id="email" value="<?php echo old('email', esc($deliverymen->email)); ?>">
    </div>

    <div class="form-group col-md-5">
        <label for="vehicle">Veiculo</label>
        <input type="text" class="form-control" name="vehicle" id="vehicle" value="<?php echo old('vehicle', esc($deliverymen->vehicle)); ?>">
    </div>

    <div class="form-group col-md-2">
        <label for="vehiche_plate">Placa Veiculo</label>
        <input type="text" class="form-control placa" name="vehicle_plate" id="vehicle_plate" value="<?php echo old('vehicle_plate', esc($deliverymen->vehicle_plate)); ?>">
    </div>
</div>
<div class="form-row">
    <div class="form-group col-md-12">
        <label for="address">Endereço</label>
        <input type="text" class="form-control" name="address" id="address" value="<?php echo old('address', esc($deliverymen->address)); ?>">
    </div>

    
</div>    
<div class="form-row">
    <div class="form-group col-md-2 mt-4">
        <div class="form-check form-check-flat form-check-primary">
            <label for="active" class="form-check-label">
                <input type="hidden" name="active" value="0">
                <input type="checkbox" class="form-check-input" id="active" name="active" value="1" <?php if (old('active', $deliverymen->active)) { ?> checked="" <?php } ?>>
                Ativo
            </label>
        </div>
    </div>
</div>


<button type="submit" class="btn btn-primary mt-3 mr-2 btn-sm">
    <i class="mdi mdi-checkbox-marked-circle btn-icon-prepend"></i>
    Salvar
</button>