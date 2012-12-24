<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">

<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    
<!--   <link href="../style/css/stylesheets/screen.css"  rel="stylesheet" type="text/css" />
  <link href="../style/css/stylesheets/print.css" rel="stylesheet" type="text/css" />
  <link href="../style/css/stylesheets/style.css" rel="stylesheet" type="text/css" /> 
  <link href="../style/css/stylesheets/cake.css" rel="stylesheet" type="text/css" /> -->

	<!-- bootstrap framework -->
	<link href="<?php echo $Core->getUrl(); ?>/bootstrap/css/bootstrap.min.css" rel="stylesheet">



			<!-- jquery -->
		<script type="text/javascript" src="../style/jquery-ui/js/jquery-1.7.1.min.js">
</script>
<script type="text/javascript" src="../style/jquery-ui/js/jquery-ui-1.8.17.custom.min.js"></script>


		<script src="../style/cake/ie.js" type="text/javascript">
</script><script src="../style/cake/core.js" type="text/javascript">
</script>


	<style type="text/css">
		body {
			padding-top: 60px;
			padding-bottom: 40px;
		}
		.sidebar-nav {
			padding: 9px 0;
		}
	</style>
		
		
		
	<title><?php echo $VIEW['title']; ?></title>
	<script type="text/javascript">
	$(document).ready(function(){
		var _head_click = 0;
		$("#cx_head").click(function(){
			if(_head_click == 0){
				
				$("#cx_left").hide(200,function(){
					//$("#cx_body").hide();
					$("#cx_body").toggleClass('span10 span11');
					//$("#cx_body").show(100);
					});
				//
				
				//$("#cx_body").show(100);
				//$("#cx_left").toggleClass('span2 span0');
			}
			if(_head_click == 1){
				
				 $("#cx_left").show(200);
				 $("#cx_body").toggleClass('span11 span10');
				 //$("#cx_left").toggleClass('span0 span2');
				 

			 }
			_head_click = (_head_click == 0)?1:0;
		});
		/****/
		<?php echo $VIEW['jquery']; ?>
		/****/
	});
	</script>
</head>
<body>
	<?php echo $VIEW['header']; ?>

		<div class="container-fluid">
		<div class="row-fluid">
		
        <div class="span2" id="cx_left">
          <div class="well sidebar-nav">
            <ul class="nav nav-list">
					<?php echo $VIEW['left']; ?>
            </ul>
          </div><!--/.well -->
        </div><!--/span-->
		
			<div class="span10" id="cx_body">
			<!--Body content-->
			
			

		<?php echo $VIEW['body']; ?>
			</div>
		</div>
	</div>

  
  	<hr>
	<footer>
        <p><?php echo $v_footer; ?></p>
    </footer>
    <div id= "debug"></div>	
</body>
</html>
