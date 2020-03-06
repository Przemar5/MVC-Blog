<!doctype html>
<html lang="en">
  	<head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		
		<title><?= $this->siteTitle(); ?></title>
		
		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
		<link rel="stylesheet" href="<?= URL; ?>css/bootstrap.min.css">

		<script src="<?= URL; ?>js/jquery/jquery-3.4.1.min.js"></script>
		<script src="<?= URL; ?>js/bootstrap.bundle.min.js"></script>
		
		<link rel="stylesheet" href="<?= URL; ?>css/custom.css">

        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">
		
		<?= $this->content('head'); ?>
  	</head>
  	<body>
        <?= $this->include('navbar'); ?>

  		<div class="container-fluid">
            <?= (Session::exists('last_action')) ? Session::pop('last_action') : ''; ?>

  			<?= $this->content('body'); ?>
  		</div>

  		<?= $this->scripts(); ?>

  	</body>
</html>