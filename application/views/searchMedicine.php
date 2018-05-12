<div class="content vertical-center">
    <div class="container-fluid">
        <div class="col-md-4 col-md-offset-4">
            <div class="card">
                <div class="header">
                    <h4 class="title">Search Medicine</h4>
                </div>
                <div class="content">
                    <?php echo form_open('welcome/displayResult') ?>
                        <div class="form-group">
                            <label>Medicine Id</label>
                            <input class="form-control" type="text" name="medicineId" required
                                   value="<?php echo empty($medicineId)?set_value('medicineId'):$medicineId ?>">
                        </div>
                        
                        <button type="submit" class="btn btn-default btn-fill">Search</button>
                        <a href="<?php echo base_url('welcome') ?>" class="btn btn-primary btn-fill">Back to HomePage</a>
                    <?php echo form_close() ?>
                </div>
            </div>
        </div>
    </div>

