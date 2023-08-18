<!-- Template formulário do cadastro de Extra-->
<div class="form-row">
    <div class="form-group col-md-12">
        <label for="name">Nome</label>
        <input type="text" class="form-control" name="name" id="name" value="<?php echo old('name', esc($measure->name)); ?>">
    </div>
</div>

<div class="form-row">
    <div class="form-group col-md-12">
        <label for="description">Descrição</label>
        <textarea class="form-control" name="description" id="description" rows="3"><?php echo old('description', esc($measure->description)); ?></textarea>
    </div>
</div>

<div class="form-row">
    <div class="form-group col-md-2 mb-4 mt-4">
        <div class="form-check form-check-flat form-check-primary">
            <label for="active" class="form-check-label">
                <input type="hidden" name="active" value="0">
                <input type="checkbox" class="form-check-input" id="active" name="active" value="1" <?php if (old('active', $measure->active)) { ?> checked="" <?php } ?>>
                Ativo
            </label>
        </div>
    </div>
</div>
<button type="submit" class="btn btn-primary mr-2 btn-sm">
    <i class="mdi mdi-checkbox-marked-circle btn-icon-prepend"></i>
    Salvar
</button>