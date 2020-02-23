<!doctype html>
<html lang="en">
  	<head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		
		<title><?php echo $this->siteTitle(); ?></title>
		
		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
		<link rel="stylesheet" href="<?= ROOT; ?>css/bootstrap.min.css">

		<script src="<?= ROOT; ?>js/jquery/jquery-3.4.1.min.js"></script>
		<script src="<?= ROOT; ?>js/bootstrap.bundle.min.js"></script>
		
		<link rel="stylesheet" href="<?= ROOT; ?>css/custom.css">
		
		<?php echo $this->content('head'); ?>
  	</head>
  	<body>
  		<div class="container-fluid" style="min-height:calc(100% - 125px);">
  			<?php echo $this->content('body'); ?>
  		</div>
  		
  	</body>
</html>