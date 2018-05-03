<div class="content">
    <div class="container-fluid">
        <div class="col-md-4 col-md-offset-4">
            <div class="card">
                <div class="header">
                    <h4 class="title">Sign Up as a Manufacturer</h4>
                </div>
                <div class="content">
                    <?php echo form_open('admin/signup') ?>
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="user_name" class="form-control"
                                   value="<?php echo empty($user_name)?set_value('user_name'):$user_name ?>">
                        </div>
                        <div class="form-group">
                            <label>Email Address</label>
                            <input type="email" name="user_email" class="form-control"
                            value="<?php echo empty($user_email)?set_value('user_email'):$user_email ?>">
                        </div>
                        <div class="form-group">
                            <label>Phone</label>
                            <input type="number" name="user_phone" class="form-control"
                            value="<?php echo empty($user_phone)?set_value('user_phone'):$user_phone ?>">
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <textarea name="user_address" class="form-control"><?php echo empty($user_address)?set_value('user_address'):$user_address ?></textarea>
                        </div>
                        <div class="form-group">
                            <label>GSTIN</label>
                            <input type="number" name="user_gstin" class="form-control"
                            value="<?php echo empty($user_gstin)?set_value('user_gstin'):$user_gstin ?>">
                        </div>
                        <div class="form-group">
                            <label>City</label>
                            <input type="text" name="user_city" class="form-control"
                            value="<?php echo empty($user_city)?set_value('user_city'):$user_city ?>">
                        </div>
                        <div class="form-group">
                            <label>State</label>
                            <input type="text" name="user_state" class="form-control"
                            value="<?php echo empty($user_state)?set_value('user_state'):$user_state ?>">
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input class="form-control" type="password" name="user_password" required>
                        </div>
                        <label>Other Details</label>
                        <div class="form-group">
                            <label>Registered Office Address</label>
                            <textarea name="manufacturer_registered_office_address" class="form-control"><?php echo empty($manufacturer_registered_office_address)?set_value('manufacturer_registered_office_address'):$manufacturer_registered_office_address ?></textarea>
                        </div>
                        <div class="form-group">
                            <label>Manufacture Plant Address(if Multiple specify all seperated by a new line</label>
                            <textarea name="manufacturer_plant_addresses" class="form-control"><?php echo empty($manufacturer_plant_addresses)?set_value('manufacturer_plant_addresses'):$manufacturer_plant_addresses ?></textarea>
                        </div>
                        <button type="submit" class="btn btn-default btn-fill">SignUp</button>
                        <a href="<?php echo base_url('admin/login') ?>" class="btn btn-primary btn-fill">Login</a>
                    <?php echo form_close() ?>
                </div>
            </div>
        </div>
    </div>

