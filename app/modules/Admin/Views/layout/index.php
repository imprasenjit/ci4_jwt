<?= $this->include('Admin\Views\layout\header'); ?>
<div class="wrapper">
  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <div class="spinner-border text-info" role="status">
      <span class="sr-only">Loading...</span>
    </div>
  </div>
  <?= $this->include('Admin\Views\layout\navbar'); ?>
  <?= $this->include('Admin\Views\layout\sidebar'); ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <?= $this->include('Admin\Views\layout\breadcrumb'); ?>
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <?= $this->renderSection('content') ?>
      </div><!-- /.container-fluid -->
    </section>
  </div>
  <!-- /.content-wrapper -->

</div>
<?= $this->include('Admin\Views\layout\footer'); ?>