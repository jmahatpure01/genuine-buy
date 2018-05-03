<div class="content">
    <div class="container-fluid">
        <div class="col-md-4 col-md-offset-4">
            <div class="card">
                <div class="header">
                    <h4 class="title"><?php echo $title ?></h4>
                </div>
                <div class="content">
                    <div class="alert-success"><?php echo $this->session->flashdata('success'); ?></div>
                    <div class="alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
                    <?php echo form_open('admin/addMedicine') ?>
                        <div class="form-group">
                            <label>Name of Medicine</label>
                            <input type="text" name="name" class="form-control"
                                   value="<?php echo empty($name)?set_value('name'):$name ?>">
                        </div>
                        <div class="form-group">
                            <label>Expiry Date</label>
                            <input type="text" id="date" name="ExpiryDate" class="form-control"
                            value="<?php echo empty($ExpiryDate)?set_value('ExpiryDate'):$ExpiryDate ?>">
                        </div>
                        <div class="form-group">
                            <label>Batch No</label>
                            <input type="text" name="BatchNo" class="form-control"
                            value="<?php echo empty($BatchNo)?set_value('BatchNo'):$BatchNo ?>">
                        </div>
                        <input type="hidden" name="id" value="<?php echo empty($id)?"":$id ?>"/>
                        <div class="alert-info"><?php echo $this->session->flashdata('info') ?></div>
                        <button type="submit" class="btn btn-info btn-fill"><?php echo empty($id)?"Add":"Update" ?></button>
                        <div><?php echo $this->session->flashdata('qrcode') ?></div>
                    <?php echo form_close() ?>
                </div>
            </div>
        </div>
    </div>
</div>
