<?php

abstract class iDump {
    public static function dump_render() {
        if ( defined('CXDUMP') ) return ;
        $_func_get_args = func_get_args();
        $_html = call_user_func_array(array('Dump', 'render'), $_func_get_args );
        return $_html;
    }

    public static function dump_render_array() {
        if ( defined('CXDUMP') ) return ;
        $_func_get_args = func_get_args();
        $_html = Dump::render_data_js($_func_get_args[0], $_func_get_args[1], TRUE);
        //$_html = call_user_func_array(array('Dump', 'render'), $_func_get_args );
        return $_html;
    }

}

