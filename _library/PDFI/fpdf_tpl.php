<?php
//
//  FPDF_TPL - Version 1.2.2
//
//    Copyright 2004-2013 Setasign - Jan Slabon
//
//  Licensed under the Apache License, Version 2.0 (the "License");
//  you may not use this file except in compliance with the License.
//  You may obtain a copy of the License at
//
//      http://www.apache.org/licenses/LICENSE-2.0
//
//  Unless required by applicable law or agreed to in writing, software
//  distributed under the License is distributed on an "AS IS" BASIS,
//  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
//  See the License for the specific language governing permissions and
//  limitations under the License.
//

class FPDF_TPL extends FPDF {
// class FPDF_TPL extends PDF_ImageAlpha {
    /**
     * Array of Tpl-Data
     * @var array
     */
    var $tpls = array();

    /**
     * Current Template-ID
     * @var int
     */
    var $tpl = 0;
    
    /**
     * "In Template"-Flag
     * @var boolean
     */
    var $_intpl = false;
    
    /**
     * Nameprefix of Templates used in Resources-Dictonary
     * @var string A String defining the Prefix used as Template-Object-Names. Have to beginn with an /
     */
    var $tplprefix = "/TPL";

    /**
     * Resources used By Templates and Pages
     * @var array
     */
    var $_res = array();
    
    /**
     * Last used Template data
     *
     * @var array
     */
    var $lastUsedTemplateData = array();
    
    /**
     * Start a Template
     *
     * This method starts a template. You can give own coordinates to build an own sized
     * Template. Pay attention, that the margins are adapted to the new templatesize.
     * If you want to write outside the template, for example to build a clipped Template,
     * you have to set the Margins and "Cursor"-Position manual after beginTemplate-Call.
     *
     * If no parameter is given, the template uses the current page-size.
     * The Method returns an ID of the current Template. This ID is used later for using this template.
     * Warning: A created Template is used in PDF at all events. Still if you don't use it after creation!
     *
     * @param int $x The x-coordinate given in user-unit
     * @param int $y The y-coordinate given in user-unit
     * @param int $w The width given in user-unit
     * @param int $h The height given in user-unit
     * @return int The ID of new created Template
     */
    function beginTemplate($x = null, $y = null, $w = null, $h = null) {
    	if (is_subclass_of($this, 'TCPDF')) {
    		$this->Error('This method is only usable with FPDF. Use TCPDF methods startTemplate() instead.');
    		return;
    	}
    	
        if ($this->page <= 0)
            $this->error("You have to add a page to fpdf first!");

        if ($x == null)
            $x = 0;
        if ($y == null)
            $y = 0;
        if ($w == null)
            $w = $this->w;
        if ($h == null)
            $h = $this->h;

        // Save settings
        $this->tpl++;
        $tpl =& $this->tpls[$this->tpl];
        $tpl = array(
            'o_x' => $this->x,
            'o_y' => $this->y,
            'o_AutoPageBreak' => $this->AutoPageBreak,
            'o_bMargin' => $this->bMargin,
            'o_tMargin' => $this->tMargin,
            'o_lMargin' => $this->lMargin,
            'o_rMargin' => $this->rMargin,
            'o_h' => $this->h,
            'o_w' => $this->w,
            'o_FontFamily' => $this->FontFamily,
            'o_FontStyle' => $this->FontStyle,
            'o_FontSizePt' => $this->FontSizePt,
            'o_FontSize' => $this->FontSize,
            'buffer' => '',
            'x' => $x,
            'y' => $y,
            'w' => $w,
            'h' => $h
        );

        $this->SetAutoPageBreak(false);
        
        // Define own high and width to calculate possitions correct
        $this->h = $h;
        $this->w = $w;

        $this->_intpl = true;
        $this->SetXY($x + $this->lMargin, $y + $this->tMargin);
        $this->SetRightMargin($this->w - $w + $this->rMargin);

        if ($this->CurrentFont)
        	$this->_out(sprintf('BT /F%d %.2f Tf ET', $this->CurrentFont['i'], $this->FontSizePt));
        
        return $this->tpl;
    }
    
    /**
     * End Template
     *
     * This method ends a template and reset initiated variables on beginTemplate.
     *
     * @return mixed If a template is opened, the ID is returned. If not a false is returned.
     */
    function endTemplate() {
    	if (is_subclass_of($this, 'TCPDF')) {
        	$args = func_get_args();
        	return call_user_func_array(array($this, 'TCPDF::endTemplate'), $args);
        }
        
        if ($this->_intpl) {
            $this->_intpl = false; 
            $tpl =& $this->tpls[$this->tpl];
            $this->SetXY($tpl['o_x'], $tpl['o_y']);
            $this->tMargin = $tpl['o_tMargin'];
            $this->lMargin = $tpl['o_lMargin'];
            $this->rMargin = $tpl['o_rMargin'];
            $this->h = $tpl['o_h'];
            $this->w = $tpl['o_w'];
            $this->SetAutoPageBreak($tpl['o_AutoPageBreak'], $tpl['o_bMargin']);
            
            $this->FontFamily = $tpl['o_FontFamily'];
			$this->FontStyle = $tpl['o_FontStyle'];
			$this->FontSizePt = $tpl['o_FontSizePt'];
			$this->FontSize = $tpl['o_FontSize'];
        	
			$fontkey = $this->FontFamily . $this->FontStyle;
			if ($fontkey)
            	$this->CurrentFont =& $this->fonts[$fontkey];
            
            return $this->tpl;
        } else {
            return false;
        }
    }
    
    /**
     * Use a Template in current Page or other Template
     *
     * You can use a template in a page or in another template.
     * You can give the used template a new size like you use the Image()-method.
     * All parameters are optional. The width or height is calculated automaticaly
     * if one is given. If no parameter is given the origin size as defined in
     * beginTemplate() is used.
     * The calculated or used width and height are returned as an array.
     *
     * @param int $tplidx A valid template-Id
     * @param int $_x The x-position
     * @param int $_y The y-position
     * @param int $_w The new width of the template
     * @param int $_h The new height of the template
     * @retrun array The height and width of the template
     */
    function useTemplate($tplidx, $_x = null, $_y = null, $_w = 0, $_h = 0) {
        if ($this->page <= 0)
        	$this->error('You have to add a page first!');
        
        if (!isset($this->tpls[$tplidx]))
            $this->error('Template does not exist!');
            
        if ($this->_intpl) {
            $this->_res['tpl'][$this->tpl]['tpls'][$tplidx] =& $this->tpls[$tplidx];
        }
        
        $tpl =& $this->tpls[$tplidx];
        $w = $tpl['w'];
        $h = $tpl['h'];
        
        if ($_x == null)
            $_x = 0;
        if ($_y == null)
            $_y = 0;
            
        $_x += $tpl['x'];
        $_y += $tpl['y'];
        
        $wh = $this->getTemplateSize($tplidx, $_w, $_h);
        $_w = $wh['w'];
        $_h = $wh['h'];
    
        $tData = array(
            'x' => $this->x,
            'y' => $this->y,
            'w' => $_w,
            'h' => $_h,
            'scaleX' => ($_w / $w),
            'scaleY' => ($_h / $h),
            'tx' => $_x,
            'ty' =>  ($this->h - $_y - $_h),
            'lty' => ($this->h - $_y - $_h) - ($this->h - $h) * ($_h / $h)
        );
        
        $this->_out(sprintf('q %.4F 0 0 %.4F %.4F %.4F cm', $tData['scaleX'], $tData['scaleY'], $tData['tx'] * $this->k, $tData['ty'] * $this->k)); // Translate 
        $this->_out(sprintf('%s%d Do Q', $this->tplprefix, $tplidx));

        $this->lastUsedTemplateData = $tData;
        
        return array('w' => $_w, 'h' => $_h);
    }
    
    /**
     * Get The calculated Size of a Template
     *
     * If one size is given, this method calculates the other one.
     *
     * @param int $tplidx A valid template-Id
     * @param int $_w The width of the template
     * @param int $_h The height of the template
     * @return array The height and width of the template
     */
    function getTemplateSize($tplidx, $_w = 0, $_h = 0) {
        if (!isset($this->tpls[$tplidx]))
            return false;

        $tpl =& $this->tpls[$tplidx];
        $w = $tpl['w'];
        $h = $tpl['h'];
        
        if ($_w == 0 and $_h == 0) {
            $_w = $w;
            $_h = $h;
        }

    	if($_w == 0)
    		$_w = $_h * $w / $h;
    	if($_h == 0)
    		$_h = $_w * $h / $w;
    		
        return array("w" => $_w, "h" => $_h);
    }
    
    /**
     * See FPDF/TCPDF-Documentation ;-)
     */
    public function SetFont($family, $style = '', $size = 0) {
        if (is_subclass_of($this, 'TCPDF')) {
        	$args = func_get_args();
        	return call_user_func_array(array($this, 'TCPDF::SetFont'), $args);
        }
        
        parent::SetFont($family, $style, $size);
       
        $fontkey = $this->FontFamily . $this->FontStyle;
        
        if ($this->_intpl) {
            $this->_res['tpl'][$this->tpl]['fonts'][$fontkey] =& $this->fonts[$fontkey];
        } else {
            $this->_res['page'][$this->page]['fonts'][$fontkey] =& $this->fonts[$fontkey];
        }
    }
    
    /**
     * See FPDF/TCPDF-Documentation ;-)
     //cx
    function Image(
		$file, $x = '', $y = '', $w = 0, $h = 0, $type = '', $link = '', $align = '', $resize = false,
		$dpi = 300, $palign = '', $ismask = false, $imgmask = false, $border = 0, $fitbox = false,
		$hidden = false, $fitonpage = false, $alt = false, $altimgs = array()
    ) {
        if (is_subclass_of($this, 'TCPDF')) {
        	$args = func_get_args();
			return call_user_func_array(array($this, 'TCPDF::Image'), $args);
        }
        
        $ret = parent::Image($file, $x, $y, $w, $h, $type, $link);
        if ($this->_intpl) {
            $this->_res['tpl'][$this->tpl]['images'][$file] =& $this->images[$file];
        } else {
            $this->_res['page'][$this->page]['images'][$file] =& $this->images[$file];
        }
        
        return $ret;
    }

    */
function Image($file,$x,$y,$w=0,$h=0,$type='',$link='', $isMask=false, $maskImg=0)
{
    //Put an image on the page
    if(!isset($this->images[$file]))
    {
        //First use of image, get info
        if($type=='')
        {
            $pos=strrpos($file,'.');
            if(!$pos)
                $this->Error('Image file has no extension and no type was specified: '.$file);
            $type=substr($file,$pos+1);
        }
        $type=strtolower($type);
        $mqr=get_magic_quotes_runtime();
        set_magic_quotes_runtime(0);
        if($type=='jpg' || $type=='jpeg')
            $info=$this->_parsejpg($file);
        elseif($type=='png'){
            $info=$this->_parsepng($file);
            if ($info=='alpha') return $this->ImagePngWithAlpha($file,$x,$y,$w,$h,$link);
        }
        else
        {
            //Allow for additional formats
            $mtd='_parse'.$type;
            if(!method_exists($this,$mtd))
                $this->Error('Unsupported image type: '.$type);
            $info=$this->$mtd($file);
        }
        set_magic_quotes_runtime($mqr);
        
        if ($isMask){
      $info['cs']="DeviceGray"; // try to force grayscale (instead of indexed)
    }
        $info['i']=count($this->images)+1;
        if ($maskImg>0) $info['masked'] = $maskImg;###
        $this->images[$file]=$info;
    }
    else
        $info=$this->images[$file];
    //Automatic width and height calculation if needed
    if($w==0 && $h==0)
    {
        //Put image at 72 dpi
        $w=$info['w']/$this->k;
        $h=$info['h']/$this->k;
    }
    if($w==0)
        $w=$h*$info['w']/$info['h'];
    if($h==0)
        $h=$w*$info['h']/$info['w'];
    
    // embed hidden, ouside the canvas
    if ((float)FPDF_VERSION>=1.7){
        if ($isMask) $x = ($this->CurOrientation=='P'?$this->CurPageSize[0]:$this->CurPageSize[1]) + 10;
    }else{
        if ($isMask) $x = ($this->CurOrientation=='P'?$this->CurPageFormat[0]:$this->CurPageFormat[1]) + 10;
    }
        
    $this->_out(sprintf('q %.2f 0 0 %.2f %.2f %.2f cm /I%d Do Q',$w*$this->k,$h*$this->k,$x*$this->k,($this->h-($y+$h))*$this->k,$info['i']));
    if($link)
        $this->Link($x,$y,$w,$h,$link);
        
    return $info['i'];
}

// needs GD 2.x extension
// pixel-wise operation, not very fast
function ImagePngWithAlpha($file,$x,$y,$w=0,$h=0,$link='')
{
    $tmp_alpha = tempnam('.', 'mska');
    $this->tmpFiles[] = $tmp_alpha;
    $tmp_plain = tempnam('.', 'mskp');
    $this->tmpFiles[] = $tmp_plain;
    
    list($wpx, $hpx) = getimagesize($file);
    $img = imagecreatefrompng($file);
    $alpha_img = imagecreate( $wpx, $hpx );
    
    // generate gray scale pallete
    for($c=0;$c<256;$c++) ImageColorAllocate($alpha_img, $c, $c, $c);
    
    // extract alpha channel
    $xpx=0;
    while ($xpx<$wpx){
        $ypx = 0;
        while ($ypx<$hpx){
            $color_index = imagecolorat($img, $xpx, $ypx);
            $alpha = 255-($color_index>>24)*255/127; // GD alpha component: 7 bit only, 0..127!
            imagesetpixel($alpha_img, $xpx, $ypx, $alpha);
        ++$ypx;
        }
        ++$xpx;
    }

    imagepng($alpha_img, $tmp_alpha);
    imagedestroy($alpha_img);
    
    // extract image without alpha channel
    $plain_img = imagecreatetruecolor ( $wpx, $hpx );
    imagecopy ($plain_img, $img, 0, 0, 0, 0, $wpx, $hpx );
    imagepng($plain_img, $tmp_plain);
    imagedestroy($plain_img);
    
    //first embed mask image (w, h, x, will be ignored)
    $maskImg = $this->Image($tmp_alpha, 0,0,0,0, 'PNG', '', true); 
    
    //embed image, masked with previously embedded mask
    $this->Image($tmp_plain,$x,$y,$w,$h,'PNG',$link, false, $maskImg);
}
function Close()
{
    parent::Close();
    // clean up tmp files
    foreach((array)$this->tmpFiles as $tmp) @unlink($tmp);

}

function _parsepng($file)
{
    //Extract info from a PNG file
    $f=fopen($file,'rb');
    if(!$f)
        $this->Error('Can\'t open image file: '.$file);
    //Check signature
    if(fread($f,8)!=chr(137).'PNG'.chr(13).chr(10).chr(26).chr(10))
        $this->Error('Not a PNG file: '.$file);
    //Read header chunk
    fread($f,4);
    if(fread($f,4)!='IHDR')
        $this->Error('Incorrect PNG file: '.$file);
    $w=$this->_readint($f);
    $h=$this->_readint($f);
    $bpc=ord(fread($f,1));
    if($bpc>8)
        $this->Error('16-bit depth not supported: '.$file);
    $ct=ord(fread($f,1));
    if($ct==0)
        $colspace='DeviceGray';
    elseif($ct==2)
        $colspace='DeviceRGB';
    elseif($ct==3)
        $colspace='Indexed';
    else {
        fclose($f);      // the only changes are 
        return 'alpha';  // made in those 2 lines
    }
    if(ord(fread($f,1))!=0)
        $this->Error('Unknown compression method: '.$file);
    if(ord(fread($f,1))!=0)
        $this->Error('Unknown filter method: '.$file);
    if(ord(fread($f,1))!=0)
        $this->Error('Interlacing not supported: '.$file);
    fread($f,4);
    $parms='/DecodeParms <</Predictor 15 /Colors '.($ct==2 ? 3 : 1).' /BitsPerComponent '.$bpc.' /Columns '.$w.'>>';
    //Scan chunks looking for palette, transparency and image data
    $pal='';
    $trns='';
    $data='';
    do
    {
        $n=$this->_readint($f);
        $type=fread($f,4);
        if($type=='PLTE')
        {
            //Read palette
            $pal=fread($f,$n);
            fread($f,4);
        }
        elseif($type=='tRNS')
        {
            //Read transparency info
            $t=fread($f,$n);
            if($ct==0)
                $trns=array(ord(substr($t,1,1)));
            elseif($ct==2)
                $trns=array(ord(substr($t,1,1)),ord(substr($t,3,1)),ord(substr($t,5,1)));
            else
            {
                $pos=strpos($t,chr(0));
                if($pos!==false)
                    $trns=array($pos);
            }
            fread($f,4);
        }
        elseif($type=='IDAT')
        {
            //Read image data block
            $data.=fread($f,$n);
            fread($f,4);
        }
        elseif($type=='IEND')
            break;
        else
            fread($f,$n+4);
    }
    while($n);
    if($colspace=='Indexed' && empty($pal))
        $this->Error('Missing palette in '.$file);
    fclose($f);
    return array('w'=>$w,'h'=>$h,'cs'=>$colspace,'bpc'=>$bpc,'f'=>'FlateDecode','parms'=>$parms,'pal'=>$pal,'trns'=>$trns,'data'=>$data);
}

//-----------

    
    /**
     * See FPDF-Documentation ;-)
     *
     * AddPage is not available when you're "in" a template.
     */
    function AddPage($orientation = '', $format = '', $keepmargins = false, $tocpage = false) {
    	if (is_subclass_of($this, 'TCPDF')) {
        	$args = func_get_args();
        	return call_user_func_array(array($this, 'TCPDF::AddPage'), $args);
        }
        
        if ($this->_intpl)
            $this->Error('Adding pages in templates isn\'t possible!');
            
        parent::AddPage($orientation, $format);
    }

    /**
     * Preserve adding Links in Templates ...won't work
     */
    function Link($x, $y, $w, $h, $link, $spaces = 0) {
        if (is_subclass_of($this, 'TCPDF')) {
        	$args = func_get_args();
			return call_user_func_array(array($this, 'TCPDF::Link'), $args);
        }
        
        if ($this->_intpl)
            $this->Error('Using links in templates aren\'t possible!');
            
        parent::Link($x, $y, $w, $h, $link);
    }
    
    function AddLink() {
    	if (is_subclass_of($this, 'TCPDF')) {
        	$args = func_get_args();
			return call_user_func_array(array($this, 'TCPDF::AddLink'), $args);
        }
        
        if ($this->_intpl)
            $this->Error('Adding links in templates aren\'t possible!');
        return parent::AddLink();
    }
    
    function SetLink($link, $y = 0, $page = -1) {
    	if (is_subclass_of($this, 'TCPDF')) {
        	$args = func_get_args();
			return call_user_func_array(array($this, 'TCPDF::SetLink'), $args);
        }
        
        if ($this->_intpl)
            $this->Error('Setting links in templates aren\'t possible!');
        parent::SetLink($link, $y, $page);
    }
    
    /**
     * Private Method that writes the form xobjects
     */
    function _putformxobjects() {
        $filter=($this->compress) ? '/Filter /FlateDecode ' : '';
	    reset($this->tpls);
        foreach($this->tpls AS $tplidx => $tpl) {

            $p=($this->compress) ? gzcompress($tpl['buffer']) : $tpl['buffer'];
    		$this->_newobj();
    		$this->tpls[$tplidx]['n'] = $this->n;
    		$this->_out('<<'.$filter.'/Type /XObject');
            $this->_out('/Subtype /Form');
            $this->_out('/FormType 1');
            $this->_out(sprintf('/BBox [%.2F %.2F %.2F %.2F]',
                // llx
                $tpl['x'] * $this->k,
                // lly
                -$tpl['y'] * $this->k,
                // urx
                ($tpl['w'] + $tpl['x']) * $this->k,
                // ury
                ($tpl['h'] - $tpl['y']) * $this->k
            ));
            
            if ($tpl['x'] != 0 || $tpl['y'] != 0) {
                $this->_out(sprintf('/Matrix [1 0 0 1 %.5F %.5F]',
                     -$tpl['x'] * $this->k * 2, $tpl['y'] * $this->k * 2
                ));
            }
            
            $this->_out('/Resources ');

            $this->_out('<</ProcSet [/PDF /Text /ImageB /ImageC /ImageI]');
        	if (isset($this->_res['tpl'][$tplidx]['fonts']) && count($this->_res['tpl'][$tplidx]['fonts'])) {
            	$this->_out('/Font <<');
                foreach($this->_res['tpl'][$tplidx]['fonts'] as $font)
            		$this->_out('/F' . $font['i'] . ' ' . $font['n'] . ' 0 R');
            	$this->_out('>>');
            }
        	if(isset($this->_res['tpl'][$tplidx]['images']) && count($this->_res['tpl'][$tplidx]['images']) || 
        	   isset($this->_res['tpl'][$tplidx]['tpls']) && count($this->_res['tpl'][$tplidx]['tpls']))
        	{
                $this->_out('/XObject <<');
                if (isset($this->_res['tpl'][$tplidx]['images']) && count($this->_res['tpl'][$tplidx]['images'])) {
                    foreach($this->_res['tpl'][$tplidx]['images'] as $image)
              			$this->_out('/I' . $image['i'] . ' ' . $image['n'] . ' 0 R');
                }
                if (isset($this->_res['tpl'][$tplidx]['tpls']) && count($this->_res['tpl'][$tplidx]['tpls'])) {
                    foreach($this->_res['tpl'][$tplidx]['tpls'] as $i => $tpl)
                        $this->_out($this->tplprefix . $i . ' ' . $tpl['n'] . ' 0 R');
                }
                $this->_out('>>');
        	}
        	$this->_out('>>');
        	
        	$this->_out('/Length ' . strlen($p) . ' >>');
    		$this->_putstream($p);
    		$this->_out('endobj');
        }
    }
    
    /**
     * Overwritten to add _putformxobjects() after _putimages()
     * cx
     */
    // function _putimages() {
    //     parent::_putimages();
    //     $this->_putformxobjects();
    // }

function _putimages()
{
    $filter=($this->compress) ? '/Filter /FlateDecode ' : '';
    reset($this->images);
    while(list($file,$info)=each($this->images))
    {
        $this->_newobj();
        $this->images[$file]['n']=$this->n;
        $this->_out('<</Type /XObject');
        $this->_out('/Subtype /Image');
        $this->_out('/Width '.$info['w']);
        $this->_out('/Height '.$info['h']);
        
        if (isset($info["masked"])) $this->_out('/SMask '.($this->n-1).' 0 R'); ###
        
        if($info['cs']=='Indexed')
            $this->_out('/ColorSpace [/Indexed /DeviceRGB '.(strlen($info['pal'])/3-1).' '.($this->n+1).' 0 R]');
        else
        {
            $this->_out('/ColorSpace /'.$info['cs']);
            if($info['cs']=='DeviceCMYK')
                $this->_out('/Decode [1 0 1 0 1 0 1 0]');
        }
        $this->_out('/BitsPerComponent '.$info['bpc']);
        if(isset($info['f']))
            $this->_out('/Filter /'.$info['f']);
        if(isset($info['parms']))
            $this->_out($info['parms']);
        if(isset($info['trns']) && is_array($info['trns']))
        {
            $trns='';
            for($i=0;$i<count($info['trns']);$i++)
                $trns.=$info['trns'][$i].' '.$info['trns'][$i].' ';
            $this->_out('/Mask ['.$trns.']');
        }
        $this->_out('/Length '.strlen($info['data']).'>>');
        $this->_putstream($info['data']);
        unset($this->images[$file]['data']);
        $this->_out('endobj');
        //Palette
        if($info['cs']=='Indexed')
        {
            $this->_newobj();
            $pal=($this->compress) ? gzcompress($info['pal']) : $info['pal'];
            $this->_out('<<'.$filter.'/Length '.strlen($pal).'>>');
            $this->_putstream($pal);
            $this->_out('endobj');
        }
    }
    $this->_putformxobjects();
}



    
    function _putxobjectdict() {
        parent::_putxobjectdict();
        
        if (count($this->tpls)) {
            foreach($this->tpls as $tplidx => $tpl) {
                $this->_out(sprintf('%s%d %d 0 R', $this->tplprefix, $tplidx, $tpl['n']));
            }
        }
    }

    /**
     * Private Method
     */
    function _out($s) {
        if ($this->state == 2 && $this->_intpl) {
            $this->tpls[$this->tpl]['buffer'] .= $s . "\n";
        } else {
            parent::_out($s);
        }
    }
}
