<div class="content vertical-center">
    <div class="container-fluid">
        <div class="col-md-4 col-md-offset-4">
            <div class="card">
                <div class="header">
                    <h4 class="title">Forgot Password</h4>
                </div>
                <div class="content">
                    <?php echo form_open('admin/forgotPassword') ?>
                    <div class="form-group">
                        <label>Email Address</label>
                        <input class="form-control" type="email" name="email" required
                               value="<?php echo empty($email)?set_value('email'):$email ?>">
                    </div>
                    <button type="submit" class="btn btn-default btn-fill">Send Mail</button>
                    <?php echo form_close() ?>
                </div>
            </div>
        </div>
    </div>

