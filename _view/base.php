<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>
			大學甄選落點分析
		</title>
		<link rel="shortcut icon" href="../style/image/favicon.ico">
		<link rel="icon" type="image/gif" href="../style/image/favicon.gif">

  <link href="../style/css/stylesheets/screen.css"  rel="stylesheet" type="text/css" />
  <link href="../style/css/stylesheets/print.css" rel="stylesheet" type="text/css" />
  <link href="../style/css/stylesheets/style.css" rel="stylesheet" type="text/css" /> 
  <link href="../style/css/stylesheets/cake.css" rel="stylesheet" type="text/css" />
  <!--[if IE]>
      <link href="../style/css/stylesheets/ie.css" media="screen, projection" rel="stylesheet" type="text/css" />
  <![endif]-->
  <!--[if lt IE 7]>
      <link href="../style/css/stylesheets/ie6.css" media="screen, projection" rel="stylesheet" type="text/css" />
  <![endif]-->
		<!-- Start menu  -->
		<link rel="stylesheet" href="../style/css3menu1/style.css" type="text/css">
<!-- End  HEAD  -->


		<!-- jquery -->
		<script type="text/javascript" src="../style/jquery-ui/js/jquery-1.7.1.min.js">
</script>
<script type="text/javascript" src="../style/jquery-ui/js/jquery-ui-1.8.17.custom.min.js"></script>

		<link rel="stylesheet" href="../style/jquery-ui/themes/base/jquery.ui.all.css" type="text/css">
		<script src="../style/jquery-ui/ui/jquery.ui.core.js" type="text/javascript">
</script>
		<script src="../style/jquery-ui/ui/jquery.ui.widget.js" type="text/javascript">
</script>
		<script src="../style/jquery-ui/ui/jquery.ui.button.js" type="text/javascript">
</script>
		<script src="../style/cake/ie.js" type="text/javascript">
</script><script src="../style/cake/core.js" type="text/javascript">
</script>

<!-- bar -->
<!-- <link type="text/css" href="../style/bar/style/demo.css" rel="stylesheet" media="all" /> -->
<link type="text/css" href="../style/bar/style/jquery.jscrollpane.css" rel="stylesheet" media="all" />
<script type="text/javascript" src="../style/bar/script/jquery.mousewheel.js"></script>
<script type="text/javascript" src="../style/bar/script/jquery.jscrollpane.min.js"></script>
<!-- bar end -->
		<style type="text/css">
		.horizontal-only
		{
			height: auto;
			max-height: 200px;
		}
		</style>
<script type="text/javascript">
$(function() {

//  bar
    var bars = '.jspHorizontalBar, .jspVerticalBar';
 
    $('.scroll-pane').bind('jsp-initialised', function (event, isScrollable) {
         
        //hide the scroll bar on first load
        $(this).find(bars).hide();
     
    }).jScrollPane().hover(
        //hide show scrollbar
        function () {
            $(this).find(bars).stop().fadeTo('fast', 1);
        },
        function () {
            $(this).find(bars).stop().fadeTo('fast', 0);
        }
 
    );              
 

});		
</script>

	</head>
	<body>
		
		<div class="container mContainer ">
			<div class="header span-24 " >
				<img width='940' src="../style/images/demo_logo.jpg" alt="">
				<h1 style="overflow-x:hidden; overflow-y:hidden;">
					<?php echo $VIEW['top']; ?>
				</h1>
			
			</div>

<?php echo $VIEW['menu']; ?>


			


<?php echo $VIEW['container']; ?>




			<div class="footer span-24">
			<hr>
				<p class="font">
					地址：404 台中市北區大雅路337號7樓之2　TEL : (04)2298-5966 　FAX: (04)2298-5977 　<br>
					版權所有 © 2012 　松盟科技股份有限公司‧ All Rights Reserved.<br>
					建議使用 
					<!-- <a href="http://www.google.com/chrome/">Google瀏覽器（Chrome）</a>、
					<a href="http://moztw.org/firefox/">Firefox瀏覽器（Firefox）</a>、
					<a href="http://www.opera.com/products/">Opera 瀏覽器（Opera）</a>、 -->
					Google瀏覽器（Chrome）、
					Firefox瀏覽器（Firefox）、
					Opera 瀏覽器（Opera）、
					IE8瀏覽器（Internet Explorer 8），
					以達到最佳的顯示效果
				</p>
			</div>

		</div><!-- end container -->
	</body>
</html>