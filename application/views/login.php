<div class="content vertical-center">
    <div class="container-fluid">
        <div class="col-md-4 col-md-offset-4">
            <div class="card">
                <div class="header">
                    <h4 class="title">Login</h4>
                </div>
                <div class="content">
                    <?php echo form_open('admin/login') ?>
                        <div class="form-group">
                            <label>Email Address</label>
                            <input class="form-control" type="email" name="email" required
                                   value="<?php echo empty($email)?set_value('email'):$email ?>">
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input class="form-control" type="password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-default btn-fill">Login</button>
                        <a href="<?php echo base_url('admin/forgotPassword')?>">Forgot Password?</a>
                        <a href="<?php echo base_url('admin/signup') ?>" class="btn btn-primary btn-fill">Signup as a Manufacturer</a>
                    <?php echo form_close() ?>
                </div>
            </div>
        </div>
    </div>

