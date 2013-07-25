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

 <link href="<?php echo $Core->Url("style/font-awesome/css/font-awesome.min.css" , false); ?>" rel="stylesheet">


			<!-- jquery -->
<script type="text/javascript" src="<?php echo $Core->getUrl(); ?>style/jquery-ui/js/jquery-1.7.1.min.js">
</script>
<script type="text/javascript" src="<?php echo $Core->getUrl(); ?>style/jquery-ui/js/jquery-ui-1.8.17.custom.min.js"></script>


<script type="text/javascript" src="<?php echo $Core->getUrl(); ?>style/bootstrap/js/bootstrap-scrollspy.js">
</script>

<script type="text/javascript" src="<?php echo $Core->getUrl(); ?>style/minwt.auto_full_height.mini.js">
</script>

<link href="<?php echo $Core->Url("style/nanoscroller.css" , false); ?>" rel="stylesheet">

<script type="text/javascript" src="<?php echo $Core->getUrl(); ?>style/jquery.nanoscroller.min.js">
</script>


<!-- <script src="<?php echo $Core->getUrl(); ?>style/cake/ie.js" type="text/javascript">
</script><script src="<?php echo $Core->getUrl(); ?>style/cake/core.js" type="text/javascript">
</script> -->

 <link href="<?php echo $Core->Url("style/tasks.css" , false); ?>" rel="stylesheet">

  <style type="text/css">
    body {
      padding-top: 60px;
      padding-bottom: 40px;

    }
    .sidebar-nav {
      padding: 9px 0;
    }
  </style>

<script type="text/javascript">
$(function() {
     $(".nano").nanoScroller();
});   
</script>



</head>
<body>


<!--   <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container-fluid"> -->
  <div class="navbar navbar-fixed-top ">
      <div class="navbar-inner">
        <div class="container" style="margin-left: 10px;">      	
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">

            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="#">TaskBox</a>
          <div class="nav-collapse collapse">
            <!-- <p class="navbar-text pull-right">
              Logged in as <a href="#" class="navbar-link">Username</a>
            </p> -->
            <ul class="nav">

              <li>
                <a href="#Home" class="active">Home</a>
              </li>
              <li>
              	 

              		
                <a href="#User">
                	<i style="margin-right: 7px;margin-top: -4px;" class="icon-user icon-white  pull-left "></i>
                	User</a>
              </li>
              <li>

              	<a href="#set">
              		<i style="margin-right: 7px;margin-top: -4px;" class="icon-cog icon-white  pull-left "></i>
              		Set</a>

              </li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>



    <div class="container-fluid fill" none="true">



      <!-- --------------- row-fluid ------------overflow : auto;------ -->
      <div class="row-fluid " >
        <div class="span2 " id="cx_left " style='z-index: 99;position: relative;' >
          <?php echo $TPL['vLeft'];?>
        </div>


        <div class="span10"  style="margin-left: 0;z-index: 4;position: relative;" >

        <div class='row well_ground_1 ' style='margin-left: -10px;margin-top: 17px;z-index: 3;position: relative;' >
          <div class="span12 nano"  >
            

            <div class="well span12" style="margin-left: 0;z-index: 3;position: relative;">
              <div class='well span8'>
                <h2>title1</h2>
                test
                <p>
                abc
              </div>

              <div class='well span4  ' style="margin-left: -5px;">
               <h2>edite</h2>
               test1<p>abc<p>abc<p>abc<p>abc<p>abc
              </div>

            </div>

            <div class="well span12" style="margin-left: 0;z-index: 3;position: relative;">
              <div class='well span8'>
                <h2>title1</h2>
                test
                <p>
                abc
              </div>

              <div class='well span4  ' style="margin-left: -5px;">
               <h2>edite</h2>
               test1<p>abc<p>abc<p>abc<p>abc<p>abc
              </div>

            </div>
            <div class="well span12" style="margin-left: 0;z-index: 3;position: relative;">
              <div class='well span8'>
                <h2>title1</h2>
                test
                <p>
                abc
              </div>

              <div class='well span4  ' style="margin-left: -5px;">
               <h2>edite</h2>
               test1<p>abc<p>abc<p>abc<p>abc<p>abc
              </div>

            </div>
 

          </div>
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


      </div>
      <!-- --------------- row-fluid ------------------ -->


    </div> 



<!--     <hr>
    <footer>
      <p>System Page</p>
        <p>
          <?php echo $v_footer; ?>
        </p>
    </footer>
    <div id= "debug"><?php echo $_v_debug; ?></div>  -->



</body>

</html>


