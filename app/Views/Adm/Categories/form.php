<!-- Template formulário do cadastro de Category-->
<div class="form-row">
    <div class="form-group col-md-4">
        <label for="name">Nome</label>
        <input type="text" class="form-control" name="name" id="name" value="<?php echo old('name', esc($category->name)); ?>">
    </div>
</div>

<div class="form-row">
    <div class="form-group col-md-2 mb-4 mt-4">
        <div class="form-check form-check-flat form-check-primary">
            <label for="active" class="form-check-label">
                <input type="hidden" name="active" value="0">
                <input type="checkbox" class="form-check-input" id="active" name="active" value="1" <?php if (old('active', $category->active)) : ?> checked="" <?php endif; ?>>
                Ativo
            </label>
        </div>
    </div>
</div>
<button type="submit" class="btn btn-primary mr-2 btn-sm">
    <i class="mdi mdi-checkbox-marked-circle btn-icon-prepend"></i>
    Salvar
</button>