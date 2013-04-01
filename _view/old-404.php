<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>
			404
		</title>
		<!-- <link rel="shortcut icon" href="../style/image/favicon.ico"> -->
		<link rel="icon" type="image/gif" href="../style/image/favicon.gif">
		<link href="../style/css/stylesheets/screen.css" rel="stylesheet" type="text/css">
		<style type="text/css">
		</style>
		<script type="text/javascript">
$(function() {
          

		});     
		</script>
	</head>
	<body>
		<div class="container mContainer">
			<div class="header span-24">
				<h1 style="overflow-x:hidden; overflow-y:hidden;">
					<?php echo $VIEW['top']; ?>
					404 找不到此頁
				</h1>
			</div>
			<?php echo $VIEW['menu']; ?>
			<hr>
			<?php echo ($VIEW['msg'] != '' ) ? "訊息" . $VIEW['msg']:''; ?>
			<br>
			你可以嘗試回上一頁
			<?php echo $VIEW['container']; ?>

			<div class="footer span-24">
				<hr>
				<p class="font">
				</p>
			</div>
		</div><!-- end container -->
	</body>
</html>