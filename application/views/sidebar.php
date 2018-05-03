<div class="sidebar" data-color="red" data-image="<?php echo base_url() ?>assets/img/sidebar-5.jpg">

    <!--

        Tip 1: you can change the color of the sidebar using: data-color="blue | azure | green | orange | red | purple"
        Tip 2: you can also add an image using data-image tag

    -->

    <div class="sidebar-wrapper">
        <div class="logo">
            <a href="#" class="simple-text">
                Genuine Buy
            </a>
        </div>

        <ul class="nav">
            <?php if ($this->session->userdata('userType') != "retailer") { ?>
            <li id="Users">
                <a href="<?php echo base_url('admin/users')?>">
                    <i class="pe-7s-users"></i>
                    <p>Users</p>
                </a>
            </li>
            <?php } ?>
            <li id="Medicines">
                <a href="<?php echo base_url('admin/medicines')?>">
                    <i class="pe-7s-pin"></i>
                    <p>Medicines</p>
                </a>
            </li>
            <li id="Add New Medicine">
                <a href="<?php echo base_url('admin/addMedicine')?>">
                    <i class="pe-7s-note"></i>
                    <p>Add New Medicine</p>
                </a>
            </li>
            <li id="Account">
                <a href="<?php echo base_url('admin/account')?>">
                    <i class="pe-7s-user"></i>
                    <p>User Profile</p>
                </a>
            </li>
        </ul>
    </div>
</div>
<script>
    var page = "<?php echo $title ?>";
    document.getElementById(page).className = "active";
</script>
<div class="main-panel">
    <nav class="navbar navbar-default navbar-fixed">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation-example-2">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#"><?php echo $title ?></a>
            </div>
            <div class="collapse navbar-collapse">

                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <a href="<?php echo base_url('admin/account')?>">
                            <p>Account</p>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo base_url('admin/logout')?>">
                            <p>Log out</p>
                        </a>
                    </li>
                    <li class="separator hidden-lg"></li>
                </ul>
            </div>
        </div>
    </nav>