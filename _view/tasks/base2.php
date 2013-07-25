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

 <link href="<?php echo $Core->Url("style/bootstrap/css/bootstrap.css" , false); ?>" rel="stylesheet">

 <link href="<?php echo $Core->Url("style/font-awesome/css/font-awesome.min.css" , false); ?>" rel="stylesheet">


			<!-- jquery -->
<script type="text/javascript" src="<?php echo $Core->getUrl(); ?>style/jquery.min.js">
</script>

 <link href="<?php echo $Core->Url("style/tasks.css" , false); ?>" rel="stylesheet">
 <link href="<?php echo $Core->Url("style/custom-scrollbar-plugin/jquery.mCustomScrollbar.css" , false); ?>" rel="stylesheet">
<script type="text/javascript" src="<?php echo $Core->getUrl(); ?>style/custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js">
</script>


<!-- <script src="<?php echo $Core->getUrl(); ?>style/cake/ie.js" type="text/javascript">
</script><script src="<?php echo $Core->getUrl(); ?>style/cake/core.js" type="text/javascript">
</script> -->


  <style type="text/css">

  </style>

<script type="text/javascript">
$(function() {
     //$(".nano").nanoScroller();
      $(".nano_left").mCustomScrollbar();
      // $(".nano_right").mCustomScrollbar();

      $(".nano_right").mCustomScrollbar({
        advanced:{
          updateOnContentResize: true
        },
        mouseWheelPixels:300,
        scrollButtons:{
          enable:false,
          scrollSpeed: 40
        }
      });

      $("#top_page").hide();
      var _topLock = false;
      $("#top_set").click(function(){
        var _height =  $(window).height()
        if(_topLock == false){
          // $('#right_body').animate({"margin-left": "-=990px"}, "slow");
            var _width = $(window).width()/2 - $("#top_page").width()/2
            var _height =  $(window).height()-$("#top_page").height()-$(window).height()/100*6;
             $('#top_page').css('margin-left',_width+"px");
             // $('#top_page').css('margin-left',"-"+$(window).height()+"px");
             $('#top_page').css('margin-top',"-"+$(window).height()+"px");
             $("#top_page").show();
             // $('#top_page').animate({"margin-top": "-"+_height+"px"}, "slow");
             // $("#top_page").show(400);
             $('#top_page').animate({"margin-top": "-5px"}, "slow");
             
             _topLock = true;
        }else{
              _topLock = false;
              $('#top_page').animate({"margin-top": "-"+$(window).height()+"px"}, "hide");
              
              // $("#top_page").hide();
        }

      });

});   



</script>



</head>
<body>
<!-- top menu -->
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

                <a id="top_set" href="#set">
                  <i style="margin-right: 7px;margin-top: -4px;" class="icon-cog icon-white  pull-left "></i>
                  Set</a>

              </li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>


<!-- end top menu -->



  <div class="container-fluid wrapper">  

      <div class="row-fluid columns content " style="margin-top: 40px;"> 
        <div class="span2 article-tree nano_left" style='z-index: 99;' >
          <?php echo $TPL['vLeft'];?>
        </div>

        <div id='right_body' class="span10 content-area " style="margin-left: -10px;margin-top: 15px;z-index: 1;">
          <!-- content column  -->
<div class="span12 nano_right"  style="margin-left: 0;z-index: 4;position: relative;" >

        <div class='row well_ground_1 ' style='margin-left: -10px;margin-top: 9px;z-index: 3;position: relative;' >
          <div class="span12 "  >
            

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
        
</div >

          <!-- content coumn -->
        </div>


<div id="loading_page" class="span8">
  
  <div class="well">
    <center>loading....</center>
  </div>
</div>

<div id='top_page' class='span8 nano_top'>
  
  
    <center> margin-top: -2px; ....</center>
  

</div>

      </div>
       
      <div class="span12 offset2" style='height:10px'>
         
        <center> goldenCake 2013 </center>

      </div>

    </div>



</body>

</html>


