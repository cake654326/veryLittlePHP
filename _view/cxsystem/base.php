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
	<!-- <link href="<?php echo $Core->getUrl(); ?>style/bootstrap/css/bootstrap.css" rel="stylesheet">
 -->
 <link href="<?php echo $Core->Url("style/bootstrap/css/bootstrap.css" , false); ?>" rel="stylesheet">



			<!-- jquery 
		<script type="text/javascript" src="<?php echo $Core->getUrl(); ?>style/jquery-ui/js/jquery-1.7.1.min.js">
</script>
<script type="text/javascript" src="<?php echo $Core->getUrl(); ?>style/jquery-ui/js/jquery-ui-1.8.17.custom.min.js"></script>
-->

<!-- <script src="<?php echo $Core->getUrl(); ?>style/cake/ie.js" type="text/javascript">
</script><script src="<?php echo $Core->getUrl(); ?>style/cake/core.js" type="text/javascript">
</script> -->

  <style type="text/css">
    body {
      padding-top: 60px;
      padding-bottom: 40px;
    }
    .sidebar-nav {
      padding: 9px 0;
    }
  </style>

</head>
<body>


  <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container-fluid">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="#">System</a>
          <div class="nav-collapse collapse">
            <!-- <p class="navbar-text pull-right">
              Logged in as <a href="#" class="navbar-link">Username</a>
            </p> -->
            <ul class="nav">

              <li>
                <a href="#backEnd" class="active">backEnd</a>
              </li>
              <li>
                <a href="#WEB">WEB index</a>
              </li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>



    <div class="container-fluid">
      <div class="row-fluid">
<!-- --------------- row-fluid ------------------ -->

        <div class="span2" id="cx_left">
          <?php echo $TPL['vLeft'];?>
        </div>


        <div class="span10">
          <div class="hero-unit">
            <h1>System </h1>
            <hr>
            <p>
              set Config 
            </p>
              <a class="btn btn-primary btn-large">Config Value&raquo;</a>

            
          </div>


  <!--         
          <div class="row-fluid">
            <div class="span4">
              <h2>Heading</h2>
              <p>

              </p>
              <p><a class="btn" href="#">View details &raquo;</a></p>
            </div>
          </div> -->


        </div>

<!-- --------------- row-fluid ------------------ -->
      </div>
    </div>   
    <hr>
    <footer>
      <p>System Page</p>
        <p>
          <?php echo $v_footer; ?>
        </p>
    </footer>
    <div id= "debug"><?php echo $_v_debug; ?></div> 



</body>

</html>


