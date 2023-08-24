<!-- Template formulário do cadastro de Produto-->
<div class="form-row">

    <div class="form-group col-md-8">
        <label for="name">Nome</label>
        <input type="text" class="form-control" name="name" id="name" value="<?php echo old('name', esc($product->name)); ?>">
    </div>

    <div class="form-group col-md-4">
        <label for="category_id">Categoria</label>
        <select class="form-control" name="category_id">
            <option value="">Escolha a categoria ...</option>
            <?php foreach ($categories as $category): ?>

                <?php if($product->id): ?>
                    <!-- Edit produto -->
                    <option value="<?php echo $category->id; ?>" <?php echo ($category->id == $product->category_id ? 'selected': ""); ?> > <?php echo $category->name ?> </option>

                <?php else: ?>
                    <!-- novo produto -->
                    <option value="<?php echo $category->id; ?>"> <?php echo $category->name ?> </option>

                <?php endif; ?>
                
                
            <?php endforeach; ?>
            
    
        </select>

    </div>
</div>

<div class="form-row">
    <div class="form-group col-md-12">
        <label for="ingredients">Ingredientes</label>
        <textarea class="form-control" name="ingredients" id="ingredients" rows="3"><?php echo old('ingredients', esc($product->ingredients)); ?></textarea>
    </div>
</div>

<div class="form-row">
    <div class="form-group col-md-2 mb-4 mt-4">
        <div class="form-check form-check-flat form-check-primary">
            <label for="active" class="form-check-label">
                <input type="hidden" name="active" value="0">
                <input type="checkbox" class="form-check-input" id="active" name="active" value="1" <?php if (old('active', $product->active)) { ?> checked="" <?php } ?>>
                Ativo
            </label>
        </div>
    </div>
</div>
<button type="submit" class="btn btn-primary mr-2 btn-sm">
    <i class="mdi mdi-checkbox-marked-circle btn-icon-prepend"></i>
    Salvar
</button>