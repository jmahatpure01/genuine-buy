<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Medic-chain</title>

	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="<?php echo base_url()  ?>assets/css/main.css" />
		<!--[if lte IE 9]><link rel="stylesheet" href="<?php echo base_url()  ?>assets/css/ie9.css" /><![endif]-->
		<noscript><link rel="stylesheet" href="<?php echo base_url()  ?>assets/css/noscript.css" /></noscript>
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.10/css/all.css" integrity="sha384-+d0P83n9kaQMCwj8F4RJB66tzIwOKmrdb46+porD/OvrJ+37WqIM7UoBtwHO6Nlg" crossorigin="anonymous">
	</head>
	<body>

		<!-- Wrapper -->
			<div id="wrapper">
				<div class="search">
					<a href="<?php echo base_url('welcome/searchMedicine') ?>" >SEARCH MEDICINE</a>
				</div>
				<!-- Header -->
					<header id="header">
						<div class="logo"> 
							<i class="fas fa-capsules fa-2x"></i>
						</div>
						<div class="content">
							<div class="inner">
								<h1>MEDIC-CHAIN</h1>
								<p>Secured Anti-Counterfeit System Based On Block-Chain Technology</p>
							</div>
						</div>
						<div class="logo"> 
							<a style="font-weight: bold" href="<?php echo base_url('admin') ?>">Get Started</a>
						</div>
					</header>

				<!-- Footer -->
					<footer id="footer">
						<p class="copyright">&copy; Dual Degree PG Programme 2014 Batch</p>
					</footer>

			</div>

		<!-- BG -->
			<div id="bg"></div>

		<!-- Scripts -->
			<script src="<?php echo base_url()  ?>assets/js/jquery.min.js"></script>
			<script src="<?php echo base_url()  ?>assets/js/skel.min.js"></script>
			<script src="<?php echo base_url()  ?>assets/js/util.js"></script>
			<script src="<?php echo base_url()  ?>assets/js/main.js"></script>

	</body>
</html>