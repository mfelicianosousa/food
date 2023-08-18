<?php echo $this->extend('Adm/Layout/main'); ?>
<!-- ************************************************************** -->

<!-- Aqui enviamos para os template main (principal) os estilos -->
<?php echo $this->section('title'); ?>
<?php echo $title; ?>
<?php echo $this->endSection() ?>
<!-- ************************************************************** -->

<?php echo $this->section('style') ?>
<!-- Aqui enviamos para os template main (principal) os estilos -->

<?php echo $this->endSection() ?>
<!-- ************************************************************** -->

<!-- Aqui enviamos para os template principal (main) o content (conteúdos) -->
<?php echo $this->section('content') ?>
<?php echo $title; ?>

<?php echo $this->endSection() ?>
<!-- ************************************************************** -->

<!-- Aqui enviamos para o template principal (main) os scripts ) -->
<?php echo $this->section('scriptJs') ?>

<?php echo $this->endSection() ?>