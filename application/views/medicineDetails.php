<div class="content">
    <div class="container-fluid">
        <div class="col-md-8 col-md-offset-2">
            <div class="card text-center">
                <div class="header">
                    <h4 class="title"><?php echo $details['name'] ?></h4>
                </div>
                <div class="content">
                    <p>Expiry date: <?php echo $details['ExpiryDate']; ?></p>
                    <p>Batch number: <?php echo  $details['BatchNo'] ?></p>
                    <h4>Transfer History</h4>
                    <div class="content table-responsive text-center">
                        <?php echo $table; ?>
                    </div>
                    <a href="<?php echo base_url('welcome') ?>" class="btn btn-primary btn-fill">Back to HomePage</a>
                </div>
            </div>
        </div>
    </div>
