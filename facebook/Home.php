<?php
if (isset($_POST['submit'])) {
	// Include the database configuration file 
	include_once 'dbConfig.php';

	// File upload configuration 
	$targetDir = "uploads/";
	$allowTypes = array('jpg', 'png', 'jpeg', 'gif');

	$statusMsg = $errorMsg = $insertValuesSQL = $errorUpload = $errorUploadType = '';
	$fileNames = array_filter($_FILES['files']['name']);
	if (!empty($fileNames)) {
		foreach ($_FILES['files']['name'] as $key => $val) {
			// File upload path 
			$fileName = basename($_FILES['files']['name'][$key]);
			$targetFilePath = $targetDir . $fileName;

			// Check whether file type is valid 
			$fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
			if (in_array($fileType, $allowTypes)) {
				// Upload file to server 
				if (move_uploaded_file($_FILES["files"]["tmp_name"][$key], $targetFilePath)) {
					// Image db insert sql 
					$insertValuesSQL .= "('" . $fileName . "', NOW()),";
				} else {
					$errorUpload .= $_FILES['files']['name'][$key] . ' | ';
				}
			} else {
				$errorUploadType .= $_FILES['files']['name'][$key] . ' | ';
			}
		}

		if (!empty($insertValuesSQL)) {
			$insertValuesSQL = trim($insertValuesSQL, ',');
			// Insert image file name into database 
			$insert = $db->query("INSERT INTO images (file_name, uploaded_on) VALUES $insertValuesSQL");
			if ($insert) {
				$errorUpload = !empty($errorUpload) ? 'Upload Error: ' . trim($errorUpload, ' | ') : '';
				$errorUploadType = !empty($errorUploadType) ? 'File Type Error: ' . trim($errorUploadType, ' | ') : '';
				$errorMsg = !empty($errorUpload) ? '<br/>' . $errorUpload . '<br/>' . $errorUploadType : '<br/>' . $errorUploadType;
				$statusMsg = "Files are uploaded successfully." . $errorMsg;
			} else {
				$statusMsg = "Sorry, there was an error uploading your file.";
			}
		}
	} else {
		$statusMsg = 'Please select a file to upload.';
	}

	// Display status message 
	echo $statusMsg;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta charset="utf-8">
	<title>Facebook Theme Demo</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link href="assets/css/bootstrap.css" rel="stylesheet">
	<!--[if lt IE 9]>
          <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
	<link href="assets/css/facebook.css" rel="stylesheet">
</head>

<body>

	<div class="wrapper">
		<div class="box">
			<div class="row row-offcanvas row-offcanvas-left">


				<!-- main right col -->
				<div class="column col-sm-10 col-xs-11" id="main">

					<!-- top nav -->
					<div class="navbar navbar-blue navbar-static-top">
						<div class="navbar-header">
							<button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".navbar-collapse">
								<span class="sr-only">Toggle</span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							</button>
							<a href="http://usebootstrap.com/theme/facebook" class="navbar-brand logo">b</a>
						</div>
						<nav class="collapse navbar-collapse" role="navigation">
							<form class="navbar-form navbar-left">
								<div class="input-group input-group-sm" style="max-width:360px;">
									<input class="form-control" placeholder="Search" name="srch-term" id="srch-term" type="text">
									<div class="input-group-btn">
										<button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
									</div>
								</div>
							</form>
							<ul class="nav navbar-nav">
								<li>
									<a href="./Home.php"><i class="glyphicon glyphicon-home"></i> Home</a>
								</li>
								<li>
									<a href="./Poste.php" role="button" data-toggle="modal"><i class="glyphicon glyphicon-plus"></i> Post</a>
								</li>

							</ul>
							<ul class="nav navbar-nav navbar-right">
								<li class="dropdown">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-user"></i></a>
									<ul class="dropdown-menu">
										<li><a href="">More</a></li>
										<li><a href="">More</a></li>
										<li><a href="">More</a></li>
										<li><a href="">More</a></li>
										<li><a href="">More</a></li>
									</ul>
								</li>
							</ul>
						</nav>
					</div>
					<!-- /top nav -->

					<div class="padding">
						<div class="full col-sm-9">

							<!-- content -->
							<div class="row">

								<!-- main col left -->
								<div class="col-sm-5">

									<div class="panel panel-default">
										<div class="panel-thumbnail"><img src="assets/img/bg_5.jpg" class="img-responsive"></div>
										<div class="panel-body">
											<p class="lead">Urbanization</p>
											<p>45 Followers, 13 Posts</p>

											<p>
												<img src="assets/img/uFp_tsTJboUY7kue5XAsGAs28.png" height="28px" width="28px">
											</p>
										</div>
									</div>
								</div>

								<!-- main col right -->
								<div class="col-sm-7">
							

									<div class="well">
										<b>Welcome to my page</b>

									</div>
									
									<?php
									// Include the database configuration file
									include_once 'dbConfig.php';

									// Get images from the database
									$query = $db->query("SELECT * FROM images ORDER BY id DESC");

									if ($query->num_rows > 0) {
										while ($row = $query->fetch_assoc()) {
											$imageURL = 'uploads/' . $row["file_name"];
									?>
											<img class="img-responsive" src="<?php echo $imageURL; ?>" alt="" />
										<?php }
									} else { ?>
										<p>No image(s) found...</p>
									<?php } ?>
								</div>
								<!--/row-->



								<div class="row" id="footer">
									<div class="col-sm-6">





									</div><!-- /col-9 -->
								</div><!-- /padding -->
							</div>
							<!-- /main -->

						</div>
					</div>
				</div>


				<!--post modal-->
				<div id="postModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">�</button>
								Update Status
							</div>
							<div class="modal-body">
								<form class="form center-block">
									<div class="form-group">
										<textarea class="form-control input-lg" autofocus="" placeholder="What do you want to share?"></textarea>
									</div>
								</form>
							</div>
							<div class="modal-footer">
								<div>
									<button class="btn btn-primary btn-sm" data-dismiss="modal" aria-hidden="true">Post</button>
									<ul class="pull-left list-inline">
										<li><a href=""><i class="glyphicon glyphicon-upload"></i></a></li>
										<li><a href=""><i class="glyphicon glyphicon-camera"></i></a></li>
										<li><a href=""><i class="glyphicon glyphicon-map-marker"></i></a></li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>

				<script type="text/javascript" src="assets/js/jquery.js"></script>
				<script type="text/javascript" src="assets/js/bootstrap.js"></script>
				<script type="text/javascript">
					$(document).ready(function() {
						$('[data-toggle=offcanvas]').click(function() {
							$(this).toggleClass('visible-xs text-center');
							$(this).find('i').toggleClass('glyphicon-chevron-right glyphicon-chevron-left');
							$('.row-offcanvas').toggleClass('active');
							$('#lg-menu').toggleClass('hidden-xs').toggleClass('visible-xs');
							$('#xs-menu').toggleClass('visible-xs').toggleClass('hidden-xs');
							$('#btnShow').toggle();
						});
					});
				</script>
</body>

</html>