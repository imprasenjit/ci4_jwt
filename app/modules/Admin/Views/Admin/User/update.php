<!-- Include -->
<?= $this->include('Admin\Views\Admin\load\select2') ?>
<?= $this->include('Admin\Views\Admin\load\sweetalert') ?>

<!-- Extend from layout index -->
<?= $this->extend('Admin\Views\layout\index') ?>

<!-- Section content -->
<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-info">
            <div class="card-header">
                <div class="float-left">
                    <div class="btn-group">
                        <a href="<?= site_url('admin/user') ?>" class="btn btn-sm btn-block btn-secondary"><i class="fas fa-arrow-left"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form action="<?= site_url('admin/users/'.$user['id']) ?>/update" method="post" class="form-horizontal">
                    <?= csrf_field() ?>
                    <input type="hidden" name="_method" value="put" />
                    <div class="form-group row">
                        <label for="inputSkills" class="col-sm-2 col-form-label"><?= lang('Users.user.fields.active') ?></label>
                        <div class="col-sm-8">
                            <select class="form-control select" name="active" style="width: 100%;">
                                <option <?= $user['active'] == 1 ? 'selected' : '' ?> value="1"><?= lang('Users.user.fields.active') ?></option>
                                <option <?= $user['active'] == 0 ? 'selected' : '' ?> value="0"><?= lang('Users.user.fields.non_active') ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputName" class="col-sm-2 col-form-label"><?=lang('Auth.email')?></label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                </div>
                                <input type="email" name="email" class="form-control <?= session('error.email') ? 'is-invalid' : '' ?>" value="<?= $user['email'] ?>" placeholder="<?=lang('Auth.email')?>" autocomplete="off">
                                <?php if (session('error.email')) { ?>
                                <div class="invalid-feedback">
                                    <h6><?= session('error.email') ?></h6>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputName" class="col-sm-2 col-form-label"><?=lang('Auth.username')?></label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                </div>
                                <input type="text" name="username" class="form-control <?= session('error.username') ? 'is-invalid' : '' ?>" value="<?= $user['username'] ?>" placeholder="<?=lang('Auth.username')?>" autocomplete="off">
                                <?php if (session('error.username')) { ?>
                                <div class="invalid-feedback">
                                    <h6><?= session('error.username') ?></h6>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputName2" class="col-sm-2 col-form-label"><?=lang('Auth.password')?></label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                </div>
                                <input type="password" name="password" class="form-control <?= session('error.password') ? 'is-invalid' : '' ?>" placeholder="<?=lang('Auth.password')?>" autocomplete="off">
                                <?php if (session('error.password')) { ?>
                                <div class="invalid-feedback">
                                    <h6><?= session('error.password') ?></h6>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputName2" class="col-sm-2 col-form-label"><?=lang('Auth.repeatPassword')?></label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                </div>
                                <input type="password" name="pass_confirm" class="form-control <?= session('error.pass_confirm') ? 'is-invalid' : '' ?>" placeholder="<?=lang('Auth.repeatPassword')?>" autocomplete="off">
                                <?php if (session('error.pass_confirm')) { ?>
                                <div class="invalid-feedback">
                                    <h6><?= session('error.pass_confirm') ?></h6>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputSkills" class="col-sm-2 col-form-label"><?= lang('Users.permission.title') ?></label>
                        <div class="col-sm-8">
                            <select class="form-control select" name="permission[]" multiple="multiple" data-placeholder="<?= lang('boilerplate.permission.fields.plc_name') ?>" style="width: 100%;">
                            </select>
                            <?php if (session('error.permission')) { ?>
                                <h6 class="text-danger"><?= session('error.permission') ?></h6>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputSkills" class="col-sm-2 col-form-label"><?= lang('Users.role.title') ?></label>
                        <div class="col-sm-8">
                            <select class="form-control select" name="role[]" multiple="multiple" data-placeholder="<?= lang('boilerplate.role.fields.plc_name') ?>" style="width: 100%;">
                            </select>
                            <?php if (session('error.role')) { ?>
                                <h6 class="text-danger"><?= session('error.role') ?></h6>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-10">
                            <div class="float-right">
                                <div class="btn-group">
                                    <button type="submit" class="btn btn-sm btn-block btn-primary">
                                        <?= lang('Users.global.save') ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
  <script>
  $('.select').select2();
  </script>
<?= $this->endSection() ?>
