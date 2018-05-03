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
                    <?php echo form_open('admin/transfer') ?>
                        <div class="form-group">
                            <label>Medicine Id</label>
                            <input type="text" name="medicineId" class="form-control"
                                   value="<?php echo empty($medicineId)?set_value('medicineId'):$medicineId ?>">
                        </div>
                        <div class="form-group">
                            <label>New Owner</label>
                            <input type="text" id="newOwner" onkeypress="getUser()" name="newOwner" class="form-control"
                            value="<?php echo empty($newOwner)?set_value('newOwner'):$newOwner ?>">
                            <div id="ownerDiv"></div>
                        </div>
                        <div class="alert-info"><?php echo $this->session->flashdata('info') ?></div>
                        <button type="submit" class="btn btn-info btn-fill">Transfer</button>
                        <div><?php echo $this->session->flashdata('qrcode') ?></div>
                    <?php echo form_close() ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function getUser() {
        var owner = document.getElementById('newOwner').value;
        
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var list = this.responseText;
                if(list) {
                    document.getElementById("ownerDiv").style = "z-index: 1;position: absolute;width: 80%";
                    document.getElementById("ownerDiv").innerHTML = list;
                } else {
                    document.getElementById("ownerDiv").innerHTML = null;
                }
            }
        };
        xhttp.open("GET", "<?php echo base_url('admin/getUsers/') ?>"+owner, true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send();
    }
    function setOwner(owner) {
        document.getElementById("ownerDiv").innerHTML = null;
    
        document.getElementById('newOwner').value = owner;
    }
</script>