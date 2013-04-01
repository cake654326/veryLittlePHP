<?php
// 	

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>404 - 找不到網頁</title>
	


		<style type="text/css">@import url("<?php echo $Core->Url("style/404/css/stylesheet.css" , false); ?>	");</style>

		<link href="<?php echo $Core->Url("style/404/css/blue.css" , false); ?>" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="<?php echo $Core->Url("style/jquery-ui/js/jquery-1.7.1.min.js" , false); ?>"></script>
		<!-- Import google jquery -->
		<script type="text/javascript" src="http://www.google.com/jsapi"></script>
		
		<script type="text/javascript">

		</script> 
		
		<!-- PNGFix for IE6 -->
		<script type="text/javascript" src="<?php echo $Core->Url("style/404/js/jquery.pngFix.js" , false); ?>	"></script> 
		
		<!-- Active pngfix -->
		<script type="text/javascript"> 
    		$(document).ready(function(){ 
      		$(document).pngFix(); 
    	}); 
		</script> 
		
	</head>
<body>


	<!-- Warp around everything -->
	<div id="warp">
	
		
		<!-- Header top -->
		<div id="header_top"></div>
		<!-- End header top -->
		
		
		<!-- Header -->
		<div id="header">
			<h2>404 - 找不到此網頁</h2>
			<h5>你有可能輸入錯誤的網址。</h5>
		</div>
		<!-- End Header -->
		
		
		<!-- The content div -->
		<div id="content">
		
			<!-- text -->
			<div id="text">
				<!-- The info text -->
				<p>
					很抱歉，<br/>
					您要前往的頁面已經不存在了。<br/>
					請使用下面的連結或者回到上一頁。
				</p>
                <br />
				<h3></h3>
				<!-- End info text -->
				
				<!-- Page links -->
				<ul>
					<li><a href="<?php echo $Core->Url("index.php" , false); ?>">&raquo; 首頁</a></li>
					
				</ul>
				<!-- End page links -->	
			</div>
			<!-- End info text -->
		
			
			<!-- Book icon -->
			<img id="book" src="<?php echo $Core->Url("style/404/images/img-01.png" , false); ?>" alt="Book iCon" />
			<!-- End Book icon -->
			
			<div style="clear:both;"></div>
		</div>
		<!-- End Content -->
		
		
		<!-- Footer -->
		<div id="footer">

		</div>
		<!-- End Footer -->
		
		
		<!-- Footer bottom -->
		<div id="footer_bottom"></div>
		<!-- End Footer bottom -->
	
	
	
		
		
		<div style="clear:both;"></div>


	</div>
	<!-- End Warp around everything -->
	
</body>
</html>