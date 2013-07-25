<script type="text/javascript">
$(document).ready(function(){
  var bLock = false;
  $("#loading_page").hide();
  $("#click_btn").click(function(){
      // $('#right_body').stop(true, false).animate({
      //           'left': '0px'
      //       }, 900);
  // $('#right_body').animate({left: '-201px'}, 800);
      if(bLock == false){
          // $('#right_body').animate({"margin-left": "-=990px"}, "slow");
          var _width =  $(window).width()-$(".nano_left").width()-$(window).width()/100*6;
          $('#right_body').animate({"margin-left": "-"+_width+"px"}, "slow");
          $("#loading_page").show(400);
          bLock = true;
      }else{
          $('#right_body').animate({"margin-left": "-10px"}, "slow");
          bLock = false;
          $("#loading_page").hide(100);
      }
      
      // $("#right_body").hide("slide", { direction: "left" }, 1000);
  });
  // $('#scroll-pane').scrollspy({target: '#navbar'});
  // var h=$(window).height();
  // $('#scrollable').height(h+'px');
  // style='height:250px;overflow : auto;'
});
</script>

          <div id="scroll-pane" class="well sidebar-nav " >
            <ul class="nav nav-list">
              <li class="active">
                <a href="#">Home</a>
              </li>
              <li class="nav-header">Config Value</li>
              <li >
                <a id = 'click_btn' href="#">System Config click_btn</a>
              </li>
              <li><a href="#">Project Config</a></li>

              <li class="nav-header">Controllers</li>
              <li><a href="#">Link</a></li>
              <li><a href="#">Link</a></li>

              <li class="nav-header">User</li>
              <li><a href="#">Link</a></li>
              <li><a href="#">Link</a></li>
              <li><a href="#">Link</a></li>
              <li><a href="#">Link</a></li>
              <li><a href="#">Link</a></li>
              <li><a href="#">Link</a></li>
              <li><a href="#">Link</a></li>
              <li><a href="#">Link</a></li>
              <li><a href="#">Link</a></li>
              <li><a href="#">Link</a></li>
              <li><a href="#">Link</a></li>
              <li><a href="#">Link</a></li>
              <li><a href="#">Link</a></li>
              <li><a href="#">Link</a></li>
              <li><a href="#">Link</a></li>
              <li><a href="#">Link</a></li>
              <li><a href="#">Link</a></li>
              <li><a href="#">Link</a></li>
              <li><a href="#">Link</a></li>
              <li><a href="#">Link</a></li>
              <li><a href="#">Link</a></li>
              <li><a href="#">Link</a></li>
              <li><a href="#">Link</a></li>
              <li><a href="#">Link</a></li>
   
            </ul>
          </div> 