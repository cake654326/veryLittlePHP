<?php


/**
 * Debugging tool which displays information about any PHP variable, class or exception.
 * Inspired in Krumo by mrasnika (http://krumo.sourceforge.net/)
 * @author Javier Marín (https://github.com/javiermarinros)
 */
define( 'CXDUMP', true );

abstract class Dump {

    private static $_static_url = '/dump-static';
    private static $_special_paths = array();
    private static $_nesting_level = 5;
    private static $_recursion_objects;
    

    public static function config(
        $static_url = '/dump-static'
        , $special_paths = array()
        , $nesting_level = 5) {
        self::$_static_url = $static_url;
        self::$_special_paths = $special_paths;
        self::$_nesting_level = $nesting_level;
    }

    /**
     * Display information about one or more PHP variables
     * @param mixed $var
     */
    public static function show() {
        $data = func_get_args();
        echo self::render_data($data, NULL, TRUE);
    }

    /**
     * Gets information about one or more PHP variables and return it in HTML code
     * @param mixed $var
     * @return string
     */
    public static function render() {
        $data = func_get_args();

        return self::render_data_js($data, NULL, TRUE);
    }


    /**
     * Gets information about one or more PHP variables and return it in HTML code.
     * @param mixed $name Name of the analyzed var, or dictionary with several vars and names
     * @param mixed $value
     * @param bool $show_caller
     * @return string
     */
    public static function render_data_js($name, $value = NULL, $show_caller = TRUE) {
        static $_index_key = 0;
        $_index_key++;
        // echo $_index_key;

        //Prepare data
        if (is_array($name)) {
            $data = $name;
        } else {
            $data = array($name => $value);
        }



        //Render data
        if (count($data) == 1 && ($e = reset($data)) instanceof Exception) {
            self::$_recursion_objects = array();
            $inner = array(self::_render_exception($e, FALSE));

            //Caller info
            $show_caller = TRUE;
            $action = 'Thrown';
            $step['file'] = self::clean_path($e->getFile());
            $step['line'] = $e->getLine();
        } else {
            $inner = array();

            //判別資料串 cx
            foreach ($data as $name => $value) {
                self::$_recursion_objects = array();
                // echo "<br>cx_tag:" . $name . "<br>";
                $inner[] = self::_render(empty($name) || is_numeric($name) ? '...' : $name, $value);

                self::$_recursion_objects = NULL;
            }

            //Caller info
            if ($show_caller) {

                $action = 'Called';
                $trace = debug_backtrace();
                while ($step = array_pop($trace)) {
                    if ((strToLower($step['function']) == 'dump' || strToLower($step['function']) == 'dumpdie') || (isset($step['class']) && strToLower($step['class']) == 'dump')) {
                        break;
                    }
                }
                $step['file'] = self::clean_path($step['file']);
            }
        }


        //Generate HTML
        $html = array('<div class="dump" style="visibility:hidden" >');

        //Content init

        $_content = array();
        $_content[] = '<ul class="dump-node dump-firstnode"><li>';
        foreach ($inner as $item)
            $_content[] = $item;
        $_content[] = '</li></ul>';
        //echo "<pre>";print_r($_content);echo "</pre>";

        $_content_val = implode('', $_content);
        //Loader init
        // $html[] = self::_assets_loader('init_dump($(".dump"),{static_url:"' . self::$_static_url . '"})');
        $_loader = self::_assets_loader('init_dump($(".dump"),{static_url:"' . self::$_static_url . '"})', $_content_val , $_index_key);
        

        $html[] = $_loader;
        $html[] = $_content_val ;

        //Footer
        if (isset($step) && $show_caller)
            $html[] = self::_html_element('div', array('class' => 'dump-footer'), "$action from {$step['file']}, line {$step['line']}");

        $html[] = '</div>';
        return implode('', $html);
    }


    /**
     * Gets information about one or more PHP variables and return it in HTML code.
     * @param mixed $name Name of the analyzed var, or dictionary with several vars and names
     * @param mixed $value
     * @param bool $show_caller
     * @return string
     */
    public static function render_data($name, $value = NULL, $show_caller = TRUE) {
        static $_index_key = 0;
        $_index_key++;
        // echo $_index_key;

        //Prepare data
        if (is_array($name)) {
            $data = $name;
        } else {
            $data = array($name => $value);
        }

        //Render data
        if (count($data) == 1 && ($e = reset($data)) instanceof Exception) {
            self::$_recursion_objects = array();
            $inner = array(self::_render_exception($e, FALSE));

            //Caller info
            $show_caller = TRUE;
            $action = 'Thrown';
            $step['file'] = self::clean_path($e->getFile());
            $step['line'] = $e->getLine();
        } else {
            $inner = array();
            foreach ($data as $name => $value) {
                self::$_recursion_objects = array();

                $inner[] = self::_render(empty($name) || is_numeric($name) ? '...' : $name, $value);

                self::$_recursion_objects = NULL;
            }

            //Caller info
            if ($show_caller) {

                $action = 'Called';
                $trace = debug_backtrace();
                while ($step = array_pop($trace)) {
                    if ((strToLower($step['function']) == 'dump' || strToLower($step['function']) == 'dumpdie') || (isset($step['class']) && strToLower($step['class']) == 'dump')) {
                        break;
                    }
                }
                $step['file'] = self::clean_path($step['file']);
            }
        }


        //Generate HTML
        $html = array('<div class="dump">');

        


/* content old code
        $html[] = '<ul class="dump-node dump-firstnode"><li>';
        foreach ($inner as $item)
            $html[] = $item;
        $html[] = '</li></ul>';
*/
        //Content init

        $_content = array();
        $_content[] = '<ul class="dump-node dump-firstnode"><li>';
        foreach ($inner as $item)
            $_content[] = $item;
        $_content[] = '</li></ul>';
        //echo "<pre>";print_r($_content);echo "</pre>";

        $_content_val = implode('', $_content);
        //Loader init
        // $html[] = self::_assets_loader('init_dump($(".dump"),{static_url:"' . self::$_static_url . '"})');
        $_loader = self::_assets_loader('init_dump($(".dump"),{static_url:"' . self::$_static_url . '"})', $_content_val , $_index_key);
        

        $html[] = $_loader;
        $html[] = $_content_val ;

        //Footer
        if (isset($step) && $show_caller)
            $html[] = self::_html_element('div', array('class' => 'dump-footer'), "$action from {$step['file']}, line {$step['line']}");

        $html[] = '</div>';
        return implode('', $html);
    }

    private static function _assets_loader($on_load = '' ,$_content = '' ,$_index_key=0) {
        ob_start();
        ?>
        <div class="_cx_debug_msg_<?php echo $_index_key?>"  style="visibility:hidden" >
            <div class="dump" >
            <?php echo $_content; ?>
            </div>
        </div>
        <script type="text/javascript">
            //cx
            //window.jQuery || alert("no jqui");
            // window.jQuery || document.write('<script src="<?php echo self::$_static_url ?>/jquery.js"><\/script>');

            // if (typeof jQuery === 'undefined') {
            if( !( typeof(window.jQuery) !== "undefined" ) ){
                window._cx = true;
                document.write('<script src="<?php echo self::$_static_url ?>/jquery.js"><\/script>');
            }

            // if (typeof(window.jQuery.ui) !== "undefined") {
            // if ( !( typeof window.jQuery.ui !== 'undefined') ){

            // alert(window.jQuery.ui);
            


        </script>

        <script type="text/javascript">
        if( !( typeof window.jQuery.ui !== 'undefined')  ){
                // alert("test");
                document.write('<link rel="stylesheet" href="<?php echo self::$_static_url ?>/jquery-ui/themes/base/jquery.ui.all.css" \/>');
                document.write('<script src="<?php echo self::$_static_url ?>/jquery-ui/ui/jquery.ui.core.js"><\/script>');
                document.write('<script src="<?php echo self::$_static_url ?>/jquery-ui/ui/jquery.ui.widget.js"><\/script>');
                document.write('<script src="<?php echo self::$_static_url ?>/jquery-ui/ui/jquery.ui.mouse.js"><\/script>');
                document.write('<script src="<?php echo self::$_static_url ?>/jquery-ui/ui/jquery.ui.draggable.js"><\/script>');
                document.write('<script src="<?php echo self::$_static_url ?>/jquery-ui/ui/jquery.ui.position.js"><\/script>');
                document.write('<script src="<?php echo self::$_static_url ?>/jquery-ui/ui/jquery.ui.resizable.js"><\/script>');
                document.write('<script src="<?php echo self::$_static_url ?>/jquery-ui/ui/jquery.ui.button.js"><\/script>');
                document.write('<script src="<?php echo self::$_static_url ?>/jquery-ui/ui/jquery.ui.dialog.js"><\/script>');
                document.write('<script src="<?php echo self::$_static_url ?>/jquery.scrollfollow.js"><\/script>');
        } 
        </script>


        <script type="text/javascript">
 $(function () {

    var _cx_val = $("#cx_dump_key").attr("act");
    //alert(_cx_val);
    if (_cx_val == '' || _cx_val == undefined) {} else {
        return;
    }


    var seat = 'down'; //down or up
    var div_id = "cx_dump_view";
    // var w = $(window);
    var $win = $(window);
    var _nowHight = parseInt(document.body.scrollTop, 10) || parseInt(document.documentElement.scrollTop, 10);

    $("body").append("<div id='cx_dump_key' act='1' >1</div>");
    // $("body").append("<div id='cx_dump_view' width='100%' style='position: absolute;z-index:99;'><button id='cx_debug_button'>Show</button></div><div id=cx_dialog style='overflow-x:hidden;overflow-y:auto;'></div>");
    $("body").append("\
    <div id='cx_dump_view' width='100%' style='position: absolute;z-index:99;'>\
        <button id='cx_debug_button'>Show</button>\
    </div>\
    <div id=cx_dialog style='overflow-x:hidden;overflow-y:auto;'>\
    </div>\
    ");
    $("#cx_dump_key").hide();
    //$("#dialog-report-problem-form").dialog({autoOpen: false});
    // dialog.offset({ top: offset.top - 5, left: offset.left + 25 });
    $("#cx_dialog").dialog({
        draggable: false,
        resizable: true,
        autoOpen: false,
        height: '101px',
        minHeight: '100px',
        width: '95%',
        title: 'VLP Debug',
        create: function (event, ui) {
            // $(".ui-dialog-titlebar").append();
            // var _btn_close = $("<span class='ui-icon-arrowthick-1-s ui-icon' style='display:inline-block' ></span>").button();
            // var _btn_close = $("<div></div>").button( { icons: {primary:'ui-icon-arrowthick-1-s',secondary:'ui-icon-arrowthick-1-s'} } );


            var button_up = $("<button class=button_up></button>")
                .button({
                    icons: {
                        primary: 'ui-icon-triangle-1-n ui-button-icon-only ',
                        secondary: null
                    }
                });
            $(this).parent().find(".ui-dialog-titlebar").append(button_up);

            var button_down = $("<button class=button_down></button>")
                .button({
                    icons: {
                        primary: 'ui-icon-arrowthick-1-s ui-button-icon-only',
                        secondary: null
                    }
                });
            $(this).parent().find(".ui-dialog-titlebar").append(button_down);
            // 
            // $("#cx_dialog").find(".ui-dialog-titlebar").append(_btn_close);

            // $(this).parent().children().append(_btn_close); 
            // alert("c");
        },
        open: function (event, ui) {
            // $( "#cx_dialog" ).dialog('option', 'position', [0,($win.height() - $("#"+div_id).height()) ] );
            // Hide close button
            // var me = $(this);
            // $(this).closest("[role=dialog]").find(".dialog-form-close").bind('click',function(){ 
            //     me.dialog('close')
            // });
            // $(this).parent().children().children(".ui-dialog-titlebar-close").hide();

            // $("#cx_dialog").closest(".ui-dialog-titlebar").addClass("dialog-tag-title-none");



        },
        // position: [0,($win.height() - $("#"+div_id).height()) ] ,
        //window.innerHeight/2
        // position: [0,(window.innerHeight/2) ] ,
        // position: [5,50 ] ,
        close: function (event, ui) {
            $("#cx_debug_button").stop().animate({
                "left": "50px"
            }, "slow");
            // $("#cx_dialog").hide();
            //$(this).dialog("close");
            //$(this).dialog("option","height","50%");
        }

    });

    var $dlg = $("#cx_dialog").parent();
    var _isUp = false;
    $(".button_down").click(function () {
        if ($dlg.height() > window.innerHeight) {
            $dlg.height(window.innerHeight - 100);
            $("#cx_dialog").height(window.innerHeight - 100);
        }
        var _min_height = 30;
        _isUp = false;
        $(".button_up").show();
        $(".button_down").hide();
        $dlg.animate({
            // "top": ($win.scrollTop() + window.innerHeight - _min_height - 10) + "px"
            "top": (window.innerHeight-_min_height) + "px"
        }, {
            duration: 100,
            easing: 'swing'
        });

        $("#cx_dialog").animate({
            "height": _min_height + "px"
        }, {
            duration: 100,
            easing: 'swing'
        });


        // $dlg.animate({
        //     "height": _min_height + "px"
        // }, {
        //     duration: 100,
        //     easing: 'swing'
        // });

    });

    $(".button_up").click(function () {
        if ($dlg.height() > window.innerHeight) {
            $dlg.height(window.innerHeight - 100);
            $("#cx_dialog").height(window.innerHeight - 100);
        }
        var _min_height = window.innerHeight * 0.6;
        _isUp = true;
        $(".button_down").show();
        $(".button_up").hide();
        $dlg.animate({
            // "top": ($win.scrollTop() + window.innerHeight - _min_height - 10) + "px",
             "top": (window.innerHeight - window.innerHeight*0.6) + "px",
             "left":( window.innerHeight * 0.1 ) +'px'
        }, {
            duration: 100,
            easing: 'swing'
        });

        $("#cx_dialog").animate({
            "height": _min_height + "px"
        }, {
            duration: 100,
            easing: 'swing'
        });


        // $dlg.animate({
        //     "height": _min_height + "px"
        // }, {
        //     duration: 100,
        //     easing: 'swing'
        // });

    });


    // var initialTop = $dlg.offset().top - $win.scrollTop() ;
    $("#cx_debug_button").button().click(function (event) {

        // event.preventDefault();
        if ($dlg.height() > window.innerHeight) {
            $dlg.height(window.innerHeight - 100);
            $("#cx_dialog").height(window.innerHeight - 100);
        }
        $dlg.show();
        $dlg.css({
            "top": "100px",
            'position': 'fixed',
            'width': innerWidth *0.9+"px"
        }, {
            duration: 100,
            easing: 'swing'
        });


        // _isUp = false;
        // $(".button_up").show();
        // $(".button_down").hide();

        // var _min_height = 20;
        // $("#cx_dialog").dialog("open");
        // $dlg.stop().animate({
        //     "top": ($win.scrollTop() + $win.height() - _min_height - 10) + "px"
        // }, {
        //     duration: 100,
        //     easing: 'swing'
        // });

        // $("#cx_debug_button").stop()
        //     .animate({
        //         "left": "-100px"
        //     }, {
        //         duration: 100,
        //         easing: 'swing'
        //     });

        $(".button_up").click();

    });

    // console.log($("#cx_dialog"));
    if (seat == 'down') {

        $("#" + div_id).css('top', $win.height() - $("#" + div_id).height());
    } else {

        $("#" + div_id).css('top', 0);
    }

    $win.keydown(function (event) {

        if (event.which == 27) { //ESC
            $('#cx_debug_button').click();
        }


    });


    $win.scroll(function () {
        // $(this).scrollTop()
        // nowHight =parseInt(document.body.scrollTop, 10) ||parseInt(document.documentElement.scrollTop, 10);
        // nowTop =parseInt(document.body.scrollTop, 10) ||parseInt(document.documentElement.scrollTop, 10);
        nowTop = $(this).scrollTop();
        if (seat == 'down') {
            // CX BUG 暫時不用
            // $("#"+div_id).css('top', nowTop+($win.height() - $("#"+div_id).height())  );
        } else {
            // CX BUG 暫時不用
            //$("#"+div_id).css('top', nowTop);
        }
        //$( "#dialog" ).dialog({ position: [ 0,nowTop+(w.height() - $("#"+div_id).height()) ] }); 
        //$( "#cx_dialog" ).dialog('option', 'position', [0,($win.height() - $("#"+div_id).height()) ] );
        // $("#d").dialog("option", { position: [e.pageX, e.pageY] });
        nowTop2 = nowTop;
    });



    //$win.scrollTop()+($win.height() - $("#"+div_id).height())  
    /*
 "top": ($win.scrollTop() + initialTop) + "px"
*/
    $win.scroll(function () {
        // "top": ($win.scrollTop() + $win.height() -  $dlg.height()-10 ) + "px"
        //"top":($win.scrollTop() +  window.innerHeight/2  ) + "px"
        if ($dlg.height() > window.innerHeight) {
            $dlg.height(window.innerHeight - 100);
            $("#cx_dialog").height(window.innerHeight - 100);
        }
        // console.log('scrollTop:' + $win.scrollTop());
        // console.log('innerHeight:' + window.innerHeight);
        // console.log('win:' + $win.height());
        // console.log('dlg:' + $dlg.height());
        // console.log(($win.scrollTop() + $win.height() -  $dlg.height()-10 ) );

        // $dlg.stop().animate({
        //     "top": ($win.scrollTop() + window.innerHeight - $dlg.height() - 10) + "px"
        // }, {
        //     duration: 100,
        //     easing: 'swing'
        // });
    }).resize(function () {
    });

    $dlg.bind('dialogcreate dialogopen', function (e) {
        initialTop = $dlg.offset().top - $win.scrollTop();
    });




    //$(".ui-dialog-titlebar-close", ui).hide();   

});

$(function () {
    $("#cx_dialog").append($("._cx_debug_msg_<?php echo $_index_key?>").html());
});
        </script>
        <script>
            window.init_dump ? window.jQuery(function() {
        <?php echo $on_load ?>;
            }) : (window.jQuery.ajax({dataType: "script",
                cache: true,
                url: "<?php echo self::$_static_url ?>/dump.js",
                success: function() {
                    window.jQuery(function() {
        <?php echo $on_load ?>;
                    });
                }
            }), $("head").append($("<link rel='stylesheet' type='text/css' href='<?php echo self::$_static_url ?>/dump.css' />")), window.init_dump = 'loading');
        </script>
        <noscript><style>@import url("<?php echo self::$_static_url ?>/dump.css");.dump-firstnode>li>.dump-content{display:block;}</style></noscript>
        
        

        <?php
        return ob_get_clean();
    }

    private static function _render($name, &$data, $level = 0, $metadata = NULL,$_cxtype = '') {
        // echo "cake:" . $name;exit();
        // switch($_cxtype){
        //     case "cx":
        //     exit();
        //         $render = self::_render_item($name, $data, "cake", $metadata,'',"<div>cake2</div>");
        //         return $render;
        //     break;
        // }

        if ($data instanceof Exception) {
            $render = self::_render_exception($data, TRUE, $level);
        } elseif (is_object($data)) {

            $render = self::_render_vars(TRUE, $name, $data, $level, $metadata);

        } elseif (is_array($data)) {

            $render = self::_render_vars(FALSE, $name, $data, $level, $metadata);

        } elseif (is_resource($data)) {
            $render = self::_render_item($name, 'Resource', get_resource_type($data), $metadata);
        } elseif (is_string($data)) {
            if (preg_match('#^(\w+):\/\/([\w@][\w.:@]+)\/?[\w\.?=%&=\-@/$,]*$#', $data))//URL
                $html = self::_html_element('a', array('href' => $data, 'target' => '_blank'), htmlspecialchars($data));
            else if (preg_match('#^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})$#', $data))//Email
                $html = self::_html_element('a', array('href' => "mailto:$data", 'target' => '_blank'), htmlspecialchars($data));
            else if (strpos($data, '<') !== FALSE || strlen($data) > 15) //Only expand if is a long text or HTML code
                $html = self::_html_element('div', array('class' => 'dump-string'), htmlspecialchars($data));
            $render = self::_render_item($name, 'String', $data, $metadata, strlen($data) . ' characters', isset($html) ? $html : '');
        } elseif (is_float($data)) {
            $render = self::_render_item($name, 'Float', $data, $metadata);
        } elseif (is_integer($data)) {
            $render = self::_render_item($name, 'Integer', $data, $metadata);
        } elseif (is_bool($data)) {
            $render = self::_render_item($name, 'Boolean', $data ? 'TRUE' : 'FALSE', $metadata);
        } elseif (is_null($data)) {
            $render = self::_render_item($name, 'NULL', NULL, $metadata);
        } else {
            $render = self::_render_item($name, '?', '<pre>' . print_r($data, TRUE) . '</pre>', $metadata);
        }

        return $render;
    }

    private static function _render_item($name, $type = '', $value = '', $metadata = '', $extra_info = '', $inner_html = NULL, $class = NULL) {
        
        //自定格式
        $_len = mb_strlen($name);
        if($_len > 2){
            $_type = 'none';
            if(mb_substr($name,0,2) == 'cx'){
                $_aName = explode(";", $name);
                $_type =$_aName[0];
            }

            switch($_type){
                case "cxHtml":
                    $name = $_aName[1];
                break;
                case "cxSql":
                        $type = "";
                        $name = $_aName[1];
                        $inner_html = self::_html_element(
                                        'div', array(
                                            'class' => 'dump-code',//dump-code dump-source
                                            'data-language'=>'SQL',
                                            'data-theme'=>"graynight"
                                                ), 
                                        ''.htmlspecialchars($value) );
                        $value = '';
                        $metadata = '';
                        $extra_info = '';
                break;
                case "cxFileCode":
                    //cxFileCode;name;PHP;line;C:/abc...php
                    //0       1    2    3    4
                // print_cx($_aName);
                    $type = "";
                        $name = $_aName[1];
                        $language = $_aName[2];//don't work
                        $file = $_aName[4];
                        $line = $_aName[3];
                        $inner_html = self::_get_source($file, $line);
                        $value = '';
                        $metadata = '';
                        $extra_info = '';
                break;
                case "cxCode":
                    //cxCode;name;PHP;line
                    $type = "";
                    $name = $_aName[1];
                    $line = $_aName[3];//don't work
                    $inner_html = self::_html_element(
                                        'div', array(
                                            'class' => 'dump-code',//dump-code dump-source
                                            'data-language'=>$_aName[2],
                                            'data-theme'=>"graynight"
                                                ), 
                                        ''.htmlspecialchars($value) );
                    $value = '';
                    $metadata = '';
                    $extra_info = '';
                    
                break;

                default:
                    $name= htmlspecialchars($name) ;
                break;
            }
            
        }else{
            $name= htmlspecialchars($name) ;
        }

        if (!isset($class))
            $class = strtolower($type);

        $info = '';
        if (!empty($type)) {
            $info .= self::_html_element('span', array('class' => 'dump-type'), !empty($metadata) ? "$metadata, $type" : $type);
        }
        if (!empty($extra_info)) {
            if (!empty($info))
                $info .= ', ';
            $info .= self::_html_element('span', array('class' => 'dump-info'), $extra_info);
        }

        $inner_html = empty($inner_html) ? '' : self::_html_element('div', array('class' => "dump-content $class"), '<ul class="dump-node"><li>' . implode('</li><li>', (is_array($inner_html) ? $inner_html : array($inner_html))) . '</li></ul>');

        return self::_html_element('div', array('class' => array('dump-header', $class, empty($inner_html) ? '' : ' dump-collapsed')), array(
                    array('span', array('class' => 'dump-name'), $name ),
                    empty($info) ? '' : "($info)",
                    // !empty($value) ? array('span', array('class' => 'dump-value'), htmlspecialchars($value)) : '',
                    empty($value) ? '' : array('span', array('class' => 'dump-value'), htmlspecialchars($value)),
                )) . $inner_html;
    }

    private static function _render_exception(Exception &$e, $show_location = TRUE, $level = 0) {
        $inner = array();
        $analized_trace = self::backtrace($e->getTrace());
        $path = self::clean_path($e->getFile());

        //Exception name
        $name = get_class($e);

        //Basic info about the exception
        $message = $e->getMessage();
        $inner[] = self::_html_element('div', array('class' => 'dump-exception'), $e->getMessage());

        //Source code
        foreach ($analized_trace as $step) {
            if ($step['file'] == $path && $step['line'] == $e->getLine()) {
                $source = $step['source'];
                break;
            }
        }
        if (!isset($source)) {
            $source = self::_get_source($e->getFile(), $e->getLine());
        }
        if (!empty($source)) {
            $inner[] = self::_render_source_code('Source', $source, $path, $e->getLine());
        }

        //Context and data
        if (method_exists($e, 'getContext')) {
            $context = $e->getContext();
            $inner[] = self::_render('Context', $context, $level + 1);
        }
        if (method_exists($e, 'getData')) {
            $data = $e->getData();
            $inner[] = self::_render('Data', $data, $level + 1);
        }

        //Fields
        $inner[] = self::_render_vars(TRUE, 'Fields', $e, $level);

        //Backtrace
        $inner[] = self::_render_vars(FALSE, 'Backtrace', $analized_trace, $level);

        return self::_render_item($name, $show_location ? ($path . ':' . $e->getLine()) : '', strip_tags($message), '', '', $inner, 'exception');
    }

    private static function _render_vars($is_object, $name, &$data, $level = 0, $metadata = '') {
        //"Patch" to detect if the current array is a backtrace
        $is_backtrace = !$is_object && isset($data['function']) && is_string($data['function']) &&
                isset($data['file']) && is_string($data['file']);

        $recursive = $level > 4 && $is_object && in_array($data, self::$_recursion_objects, TRUE);
        if ($level < self::$_nesting_level && !$recursive) {
            //Render subitems
            $inner_html = array();
            if ($is_object) {
                $properties_count = 0;
                if (!($data instanceof stdClass) && class_exists('ReflectionClass', FALSE)) {
                    $current = new ReflectionClass($data);
                    $properties = array();
                    $private_data = NULL;
                    while ($current !== FALSE) {
                        foreach ($current->getProperties() as $property) {
                            /* @var $property ReflectionProperty */
                            if (in_array($property->name, $properties))
                                continue;

                            //Get metadata
                            $meta = array();
                            if ($property->isStatic())
                                $meta[] = 'Static';
                            if ($property->isPrivate())
                                $meta[] = 'Private';
                            if ($property->isProtected())
                                $meta[] = 'Protected';
                            if ($property->isPublic())
                                $meta[] = 'Public';

                            //Build field
                            if ($property->isPublic()) {
                                $value = $property->getValue($data);
                            } else if (method_exists($property, 'setAccessible')) {
                                $property->setAccessible(TRUE);
                                $value = $property->getValue($data);
                            } else {
                                if (!isset($private_data))//Initialize object private data
                                    $private_data = self::_get_private_data($data, array());

                                if (array_key_exists($property->name, $private_data)) {
                                    $value = $private_data[$property->name];
                                } else {
                                    $value = '?';
                                }
                            }
                            $inner_html[] = self::_render($property->name, $value, $level + 1, implode(', ', $meta));
                            $properties[] = $property->name;
                        }
                        $current = $current->getParentClass();
                        $properties_count = count($properties);
                    }
                } else {
                    $properties = get_object_vars($data);
                    foreach ($properties as $key => &$value) {
                        $inner_html[] = self::_render($key, $value, $level + 1);
                        $properties_count++;
                    }
                }
                self::$_recursion_objects[] = $data;
            } else { //Array

                foreach ($data as $key => &$value) {


                    if ( ( $is_backtrace && $key == 'source' && is_string($value) && !empty($value) )  ) {
                        $inner_html[] = self::_render_source_code($key, $value, $data['file'], $data['line']);
                    } else {
                        $inner_html[] = self::_render($key, $value, $level + 1);
                    }

                }
            }

        } else {
            $inner_html = '&infin;';
        }

        //Render item
        if ($is_object) {
            return self::_render_item($name, 'Object', get_class($data), $metadata, isset($properties_count) ? "$properties_count fields" : '', $inner_html);
        } else {
            if ($is_backtrace) {
                $type = $data['function'];
                $info = (isset($data['args']) ? count($data['args']) : 0) . ' parameters';
            } else {
                $type = 'Array';
                $info = count($data) . ' elements';
            }

            return self::_render_item($name, $type, '', $metadata, $info, $inner_html);
        }
    }

    private static function _get_private_data($object, $default = FALSE) {
        for ($method = 0; $method < 2; $method++) {
            try {
                $raw_data = FALSE;
                if ($method == 0) {
                    //Based on a hack to access private properties: http://derickrethans.nl/private-properties-exposed.html
                    $raw_data = (array) $object;
                } else if ($method == 1) {
                    //Try to get it using serialize()
                    $class_name = get_class($object);
                    $serialized = serialize($object);

                    if (preg_match('/' . preg_quote($class_name) . '.\:(\d+)/', $serialized, $match)) {
                        $prop_count = $match[1];
                        $class_name_len = strlen($class_name);

                        $serialized_array = str_replace("O:$class_name_len:\"$class_name\":$prop_count:", "a:$prop_count:", $serialized);

                        if ($serialized != $serialized_array) {
                            $raw_data = unserialize($serialized_array);
                        }
                    }
                }

                if ($raw_data !== FALSE) {
                    $data = array();
                    foreach ($raw_data as $key => $value) {
                        $pos = strrpos($key, "\0");

                        if ($pos !== FALSE)//Remove special names given by php ( "\0*\0" for protected fields, "\0$class_name\0" for private)
                            $key = substr($key, $pos + 1);

                        $data[$key] = $value;
                    }

                    if (!empty($data))
                        return $data;
                }
            } catch (Exception $err) {
                
            }
        }

        return $default;
    }

    private static function _render_source_code($name, $value, $file = NULL, $line = NULL) {
        $edit_link = '';
        return self::_render_item($name, '', '', '', '', self::_html_element('div', array('class' => 'dump-source'), $edit_link . $value));
    }

    /**
     * Analyzes the backtrace generated by debug_backtrace function(),
     * adding contextual information.
     * The result is returned in an array with the keys:
     * 'function': function name
     * 'args': arguments name and value
     * 'file': file where the call occurs
     * 'line': line of the file where the call occurs
     * 'source': source code where the call comes (in HTML format)
     * @param array $ call stack trace to be analyzed, if not use this parameter indicates the call stack before the function
     * @return array
     */
    public static function backtrace(array $trace = NULL) {
        if ($trace === NULL) {
            $trace = debug_backtrace();
        }

        //"Special" functions
        $special_functions = array('include', 'include_once', 'require', 'require_once');

        $output = array();
        foreach ($trace as $i => $step) {
            //Get data from the current step
            foreach (array('class', 'type', 'function', 'file', 'line', 'args') as $param) {
                $$param = isset($step[$param]) ? $step[$param] : NULL;
            }

            //Source code of the call to this step
            if (!empty($file) && !empty($line)) {
                $source = self::_get_source($step['file'], $step['line']);
            } else {
                $source = '';
            }

            //Arguments
            $function_call = $class . $type . $function;
            $function_args = array();
            if (isset($args)) {
                if (in_array($function, $special_functions)) {
                    $function_args = array(self::clean_path($args[0]));
                } else {
                    if (!function_exists($function) || strpos($function, '{closure}') !== FALSE) {
                        $params = NULL;
                    } else if (class_exists('ReflectionMethod', FALSE)) {
                        if (isset($class)) {
                            $reflection = new ReflectionMethod($class, method_exists($class, $function) ? $function : '__call');
                        } else {
                            $reflection = new ReflectionFunction($function);
                        }
                        $params = $reflection->getParameters();
                    }

                    foreach ($args as $i => $arg) {
                        if (isset($params[$i])) {
                            // Assign the argument by the parameter name
                            $function_args[$params[$i]->name] = $arg;
                        } else {
                            // Assign the argument by number
                            $function_args[$i] = $arg;
                        }
                    }
                }
            }

            $output[] = array(
                'function' => $function_call,
                'args' => $function_args,
                'file' => self::clean_path($file),
                'line' => $line,
                'source' => $source,
            );
        }
        return $output;
    }

    /**
     * Renders an abbreviated version of the backtrace
     * @param array $ call stack trace to be analyzed, if not use this parameter indicates the call stack before the function
     * @return string
     */
    public static function backtrace_small(array $trace = NULL) {
        if ($trace === NULL) {
            $trace = debug_backtrace();
        }

        $output = array();
        foreach ($trace as $i => $step) {

            //Get data from the current step
            foreach (array('class', 'type', 'function', 'file', 'line', 'args') as $param) {
                $$param = isset($step[$param]) ? $step[$param] : '';
            }

            //Generate HTML
            $output[] = self::_html_element('abbr', array('title' => "$file:$line"), $class . $type . $function);
        }

        return implode(' &rarr; ', array_reverse($output));
    }

    /**
     * Renders source code of an specified programming language
     * @param string $code
     * @param string $language
     * @return string
     */
    public static function source($code, $language = 'php', $theme = 'default') {
        $code = htmlspecialchars($code, ENT_NOQUOTES);
        return self::_assets_loader('init_dump($(".dump-code"),{static_url:"' . self::$_static_url . '"})') . '<pre class="dump-code" data-language="' . $language . '" data-theme="' . $theme . '">' . $code . '</pre>';
    }

    /**
     * Clean a path, replacing the special folders defined in the config. E.g.:
     *         /home/project/www/index.php -> APP_PATH/index.php
     * @param string $path
     * @param bool $restore True for restore a cleared path to its original state
     * @return string
     */
    public static function clean_path($path, $restore = FALSE) {
        foreach (self::$_special_paths as $clean_path => $source_path) {
            if ($restore) {
                if (strpos($path, $clean_path) === 0) {
                    $path = $source_path . substr($path, strlen($clean_path));
                    break;
                }
            } else {
                if (strpos($path, $source_path) === 0) {
                    $path = $clean_path . substr($path, strlen($source_path));
                    break;
                }
            }
        }

        return str_replace(array('\\', '/'), DIRECTORY_SEPARATOR, $path);
    }

    /**
     * Read the source code from a file, centered in a line number, with a specific padding and applying a highlight
     * @return string
     */
    private static function _get_source($file, $line_number, $padding = 10) {
        if (!$file || !is_readable($file)) { //Error de lectura
            return FALSE;
        }

        // Open file
        $file = fopen($file, 'r');

        // Set padding
        $start = max(1, $line_number - $padding);
        $end = $line_number + $padding;

        $source = array();
        for ($line = 1; ($row = fgets($file)) !== FALSE && $line < $end; $line++) {
            if ($line >= $start) {
                $source[] = trim($row) == '' ? "&nbsp;\n" : htmlspecialchars($row, ENT_NOQUOTES);
            }
        }

        // Close file
        fclose($file);

        return '<pre class="dump-code" data-language="php" data-from="' . $start . '" data-highlight="' . $line_number . '" data-theme="graynight">' . implode('', $source) . '</pre>';
    }

    private static function _html_element($tag_name, $attributes, $content = NULL) {
        //Check input data
        if (!isset($content)) {
            if (is_array($attributes)) {
                return '<' . $tag_name . self::_html_attributes($attributes) . ' />';
            } else {
                $content = $attributes;
                $attributes = NULL;
            }
        }

        //Prepare content
        if (is_array($content)) {
            $content_html = array();
            foreach ($content as $child_element) {
                if (is_array($child_element)) {
                    $content_html[] = self::_html_element($child_element[0], $child_element[1], count($child_element) > 2 ? $child_element[2] : NULL);
                } else if (!empty($child_element)) {
                    $content_html[] = $child_element;
                }
            }
            $content = implode('', $content_html);
        }

        //Build element
        if (empty($attributes)) {
            return "<$tag_name>$content</$tag_name>";
        } else {
            return '<' . $tag_name . self::_html_attributes($attributes) . ">$content</$tag_name>";
        }
    }

    private static function _html_attributes($attributes = '') {
        if (is_array($attributes)) {
            $atts = '';
            foreach ($attributes as $key => $val) {
                if ($key == 'class' && is_array($val)) {
                    $val = implode(' ', array_filter($val));
                } elseif ($key == 'style' && is_array($val)) {
                    $val = implode(';', array_filter($val));
                } elseif (is_bool($val)) {
                    // XHTML compatibility
                    if ($val) {
                        $val = $key;
                    } else {
                        continue;
                    }
                }

                $atts .= " $key=\"$val\"";
            }
            return $atts;
        }
        return $attributes;
    }

}





//Define shortcuts
if (!function_exists('dump')) {

    /**
     * Echo information about the selected variable.
     * This function can be overwrited for autoload the DUMP class, e.g.:
     * @code
     * function dump() {
     *      if (!class_exists('Dump', FALSE)) {
     *          require SYS_PATH . '/vendor/Dump.php';
     *          Dump::config(...);
     *      }
     *      call_user_func_array(array('Dump', 'show'), func_get_args());
     * }
     * @endcode
     */
    function dump() {
        $_func_get_args = func_get_args();
        call_user_func_array(array('Dump', 'show'), $_func_get_args );
    }

}

//預設 輸出字串
if (!function_exists('dump_render')) {

    function dump_render() {
        $_func_get_args = func_get_args();
        $_html = call_user_func_array(array('Dump', 'render'), $_func_get_args );
        return $_html;
    }
}
//預設 輸出字串
if (!function_exists('dump_render_array')) {

    function dump_render_array() {
        $_func_get_args = func_get_args();

        $_html = Dump::render_data_js($_func_get_args[0], $_func_get_args[1], TRUE);
        //$_html = call_user_func_array(array('Dump', 'render'), $_func_get_args );
        return $_html;
    }
}




//預設 輸出字串
if (!function_exists('dump_box')) {

    function dump_box() {
        $_func_get_args = func_get_args();
        $_html = call_user_func_array(array('Dump', 'render'), $_func_get_args );
        return $_html;
    }

}

if (!function_exists('dumpdie')) {

    function dumpdie() {
        //Clean all output buffers
        while (ob_get_clean()) {
            ;
        }

        //Dump info
        call_user_func_array('dump', func_get_args());

        //Exit
        die(1);
    }

}


