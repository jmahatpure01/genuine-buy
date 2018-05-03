</div>
</div>
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title text-danger" id="myModalLabel">Warning!!!</h4>
            </div>
            <div class="modal-body">
                <h5>The Post will be Permanently Deleted from the database.</h5>
                <h6>Do You Wish to Continue?</h6>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                <a id="modalUrl" class="btn btn-danger">Yes</a>
            </div>
        </div>
    </div>
</div>

</body>

<!--   Core JS Files   -->
<script src="<?php echo base_url() ?>assets/js/jquery.3.2.1.min.js" type="text/javascript"></script>
<!-- <script src="<?php echo base_url() ?>assets/js/multislider.min.js" type="text/javascript"></script> -->
<script src="<?php echo base_url() ?>assets/js/moment.js" type="text/javascript"></script>
<script src="<?php echo base_url() ?>assets/js/bootstrap.min.js" type="text/javascript"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.16/r-2.2.1/datatables.min.js"></script>
<script src="<?php echo base_url() ?>assets/js/bootstrap-material-datetimepicker.js" type="text/javascript"></script>
<script>
    $(document).ready(function() {
        $('.table').DataTable();
    } );
</script>
<script>
    $('#date').bootstrapMaterialDatePicker({ format : 'YYYY-MM-DD', minDate : new Date(), time: false });
</script>

<!--  Charts Plugin
<script src="<?php echo base_url() ?>assets/js/chartist.min.js"></script>-->

<!--  Notifications Plugin-->
<script src="<?php echo base_url() ?>assets/js/bootstrap-notify.js"></script>

<script>
    <?php echo $this->session->flashdata('notify') ?>
</script>

<!--  Google Maps Plugin
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script>-->

<!-- Light Bootstrap Table Core javascript and methods for Demo purpose -->
<script src="<?php echo base_url() ?>assets/js/light-bootstrap-dashboard.js?v=1.4.0"></script>

<!-- Light Bootstrap Table DEMO methods, don't include it in your project!
<script src="<?php echo base_url() ?>assets/js/demo.js"></script>-->

</html>
