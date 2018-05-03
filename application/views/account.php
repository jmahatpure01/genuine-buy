<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="card card-user">
                    <div class="image">
                        <img src="https://ununsplash.imgix.net/photo-1431578500526-4d9613015464?fit=crop&fm=jpg&h=300&q=75&w=400" alt="..."/>
                    </div>
                    <div class="content">
                        <div class="author">
                            <a href="#">
                                <img class="avatar border-gray" src="<?php echo base_url()?>assets/img/default-avatar.png" alt="..."/>

                                <h4 class="title"><?php echo $user_name ?></h4>
                            </a>
                        </div>
                        <p class="description text-center"><?php echo $user_email ?></p>
                        <hr>
                        <div class="content">
                            <h4 class="title">Change Password</h4>
                            <?php echo form_open('admin/changePassword')?>
                                <div class="form-group">
                                    <label>New Password</label>
                                    <input type="password" class="form-control" name="password1" required>
                                </div>
                                <div class="form-group">
                                    <label>New Password Again</label>
                                    <input type="password" class="form-control" name="password2" required>
                                </div>
                                <button type="submit" class="btn btn-primary btn-fill">Change</button>
                            <?php echo form_close()?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>