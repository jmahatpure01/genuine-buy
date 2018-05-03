<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="card">
                    <a href="#addUser" class="btn btn-info btn-fill pull-right addBtn">Add User</a>
                    <div class="content table-responsive text-center">
                        <?php echo $table; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-md-offset-4" id="addUser">
            <div class="card">
                <div class="header">
                    <h4 class="title">Add User</h4>
                </div>
                <div class="content">
                    <div class="alert-success"><?php echo $this->session->flashdata('success'); ?></div>
                    <div class="alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
                    <?php echo form_open('admin/addUser') ?>
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
                        <input type="hidden" name="user_public_id"
                               value="<?php echo empty($user_public_id)?set_value('user_public_id'):$user_public_id ?>">
                        <div class="form-group">
                            <label>User Type</label>
                            <select class="form-control" name="user_type" id="userType">
                                <?php echo $user_types ?>
                            </select>
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
                        <div class="alert-info"><?php echo $this->session->flashdata('info') ?></div>
                        <button type="submit" class="btn btn-info btn-fill">Add</button>
                    <?php echo form_close() ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function setUrl(publicId) {
        document.getElementById('modalUrl').href = "<?php echo base_url('admin/deleteUser/') ?>"+publicId;
        $('#myModal').modal('show');
    }
</script>
