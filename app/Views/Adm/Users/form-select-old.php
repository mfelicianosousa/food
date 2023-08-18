     <div class="form-group col-md-3">
        <label for="is_admin">Perfil de Acesso</label>
        <select class="form-control" name="is_admin">
            <?php if ($user->id) : ?>
                <option value="1" <?php echo ($user->is_admin ? 'selected' : ''); ?> <?php echo set_select('is_admin','1'); ?> > Administrador</option>
                <option value="0" <?php echo (!$user->is_admin ? 'selected' : ''); ?> <?php echo set_select('is_admin','0'); ?> > Cliente</option>

            <?php else : ?>
                <option value="1">Administrador</option>
                <option value="0" selected="">cliente</option>
            <?php endif; ?>
        */
        </select>
    </div>
    <div class="form-group col-md-1">
        <label for="active">Ativo</label>
        <select class="form-control" name="active">
            <?php if ($user->id) : ?>
                <option value="1" <?php echo ($user->active ? 'selected' : ''); ?> <?php echo set_select('active','1'); ?> > Sim</option>
                <option value="0" <?php echo (!$user->active ? 'selected' : ''); ?> <?php echo set_select('active','0'); ?> > Não</option>

            <?php else : ?>
                <option value="1">Sim</option>
                <option value="0" selected="">Não</option>
            <?php endif; ?>
        </select>
    </div>
    