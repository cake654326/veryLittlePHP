/**
 * very Little PHP Framework
 *
 * @package     veryLittlePHP
 * @author      Cake X
 * @link        https://github.com/cake654326/veryLittlePHP
 * @mail        c782172004@gmail.com
 * @version     1.3
 *
 * ------------------------------------------------------------------------
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * ------------------------------------------------------------------------
 * Summary:
 * This is a small framework to support the "ADODB",
 * The ADODB tools provide simplified, and a simple template,
 * As well as to solve the traditional wording contains MVC,
 * You can use a simple MVC design pattern,
 * 
 * ------------------------------------------------------------------------
 * 2012 10	
 *			+ tpl and adodb object
 *
 * 2012 11     - v1.1
 *			+ DB object Update 
 *			+ 相容 傳統寫法專案(v1.1)，不相容 舊版 v1.0框架專案
 *  
 * 2012 12 07  - v1.2
 *			+ 傳統 MVC 入口點 index.php 
 *			+ 增加 router 網址解析 
 *			+ 增加 _controllers 放置區
 *			+ 相容之前非使用 入口點 專案(v1.1) 以及 傳統寫法專案(v1.1)
                ，不相容 舊版 v1.0框架專案
 *			+ _base/mvc_init.php 為 index.php 初始加載
 *
 * 2013 08 05  - v1.3
 *          + 初步相容 PHP 5.4
 *          + 增加 debug view 功能
 *          + 整合 PDF 庫
 *          + ADODB 版本升級
 *          + chromePhp 版本升級
 ***/
