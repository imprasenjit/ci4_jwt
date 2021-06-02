<!-- Push section css -->
<?= $this->section('css') ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@9.7.2/dist/sweetalert2.min.css">
<?= $this->endSection() ?>

<!-- Push section js -->
<?= $this->section('js') ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9.7.2/dist/sweetalert2.all.min.js"></script>

<script>
    const Toast = Swal.mixin({
        showConfirmButton: true,
        timer: 4000,
        timerProgressBar: true,
        onOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    })
    <?php if(isset($sweet_success) && $sweet_success!=""):?>
        Toast.fire({
        icon: 'success',
        title: 'Success',
        text: '<?=$sweet_success?>',        
    })
    <?php elseif(isset($sweet_error) && $sweet_error!="") :?>
        Toast.fire({
        icon: 'error',
        title: 'Error',
        text: '<?=$sweet_error?>',        
    })
    <?php endif ?>
    
</script>
<?= $this->endSection() ?>