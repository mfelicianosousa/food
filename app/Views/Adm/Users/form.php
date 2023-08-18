<!-- Template formulário do cadastro de usuário-->
<div class="form-row">
    <div class="form-group col-md-4">
        <label for="name">Nome</label>
        <input type="text" class="form-control" name="name" id="name" value="<?php echo old('name', esc($user->name)); ?>">
    </div>
    <div class="form-group col-md-4">
        <label for="user">Usuário</label>
        <input type="text" class="form-control" name="user" id="user" value="<?php echo old('user', esc($user->user)); ?>">
    </div>
    <div class="form-group col-md-2">
        <label for="cpf">cpf</label>
        <input type="text" class="form-control cpf" name="cpf" id="cpf" value="<?php echo old('cpf', $user->cpf); ?>">
    </div>
    <div class="form-group col-md-2">
        <label for="celular_number">Celular</label>
        <input type="text" class="form-control sp_celphones" name="celular_number" id="celular_number" value="<?php echo old('celular_number', $user->celular_number); ?>">
    </div>

</div>

<div class="form-row">
    <div class="form-group col-md-4">
        <label for="email">E-mail</label>
        <input type="text" class="form-control" name="email" id="email" value="<?php echo old('name', esc($user->email)); ?>">
    </div>

    <div class="form-group col-md-3">
        <label for="password">Senha</label>
        <input type="password" class="form-control" name="password" id="password" value="<?php echo old('password', esc($user->password)); ?>">
    </div>

    <div class="form-group col-md-3">
        <label for="password_confirmation">Confirmação de Senha</label>
        <input type="password" class="form-control" name="password_confirmation" id="password_confirmation">
    </div>
</div>
<div class="form-row">
    <div class="form-group col-md-2 mt-4">
        <div class="form-check form-check-flat form-check-primary">
            <label for="is_admin" class="form-check-label">
                <input type="hidden" name="is_admin" value="0">
                <input type="checkbox" class="form-check-input" id="is_admin" name="is_admin" value="1" <?php if (old('is_admin', $user->is_admin)) : ?> checked="" <?php endif; ?>>
                Administrador
            </label>
        </div>
    </div>
    <div class="form-group col-md-2 mt-4">
        <div class="form-check form-check-flat form-check-primary">
            <label for="active" class="form-check-label">
                <input type="hidden" name="active" value="0">
                <input type="checkbox" class="form-check-input" id="active" name="active" value="1" <?php if (old('active', $user->active)) : ?> checked="" <?php endif; ?>>
                Ativo
            </label>
        </div>

    </div>
</div>
<button type="submit" class="btn btn-primary mr-2 btn-sm">
    <i class="mdi mdi-checkbox-marked-circle btn-icon-prepend"></i>
    Salvar
</button>