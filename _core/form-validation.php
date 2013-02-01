<?php

/**
 * Form Validation
 *
 * @package     Cake-Form-Validation
 * @author      Cake X
 * @link        https://github.com/cake654326/veryLittlePHP.git
 * @version     0.5
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
 * @history
 * 2012-10/23:
 *          cake Validation
 *          add digit function:  判斷全數字
 *          add alpha function:  判斷全英文
 *          add alnum function:  判斷全英文數字
 *
 * 2013-01/xx:
 *          add alnum function:  判斷SQL 危險字元
 *
 * 2013-01/31:
 *          改為自定 POST AND GET TYPE
 *
 * */

class validateForm {
    private $mServer = array();//post or get
    protected $_formSuccess  = false;
    protected $_errorPhrases = array(
        'required'     => ' %1 為必填', //不可為空
        'min_length'   => ' %1 字數不可小於 %2 ',
        'max_length'   => ' %1 字數大於 %2 ',
        'exact_length' => ' %1 長度只可為 %2 ',
        'matches'      => 'The %1 field must match the %2.',
        'valid_email'  => 'The %1 field must be a valid E-Mail Address.',
        'not_equal'    => array(
            'post:key'     => 'The %1 field must not be the same as the %2 field.',
            'string'       => 'The %1 field must not be %2.' ),
        'depends'      => 'The %1 field depends on the %2 field being valid.',
        'digit'        => ' %1 只能為數字',
        'alpha'        => ' %1 只能為英文字母',
        'alnum'        => ' %1 只能為英數混合',
        'sql'          => ' %1 含有非法字元'
    );
    // Array of rule sets, fieldName => PIPE seperated ruleString
    protected $_ruleSets             = array();
    // Array of errors, niceName => Error Message
    protected $_errorSet             = array();
    // Array of post Key => Nice name labels
    protected $_inputLabels          = array();
    protected $_allErrorsDelimiter   = array( '<div class="errors">', '</div>' );
    protected $_eachErrorDelimiter   = array( '<p class="error">', '</p>' );
    protected $_forceFail            = false;
    protected $_errorPhraseOverrides = array();

    /**
     * Sets all errors and rule sets empty, and sets success to false.
     *
     * @return void
     */
    public function __construct() {
        $this->_resetValidation();
        return;
    }

    protected function _resetValidation() {
        $this->_ruleSets             = array();
        $this->_inputLabels          = array();
        $this->_errorPhraseOverrides = array();
        $this->_errorSet             = array();

        $this->_formSuccess = false;
        $this->_forceFail   = false;
        return;
    }

    protected function _toCallCase( $funcName, $prefix='_validate' ) {
        $funcName = strtolower( $funcName );

        $finalFuncName = $prefix;

        foreach ( explode( '_', $funcName ) as $funcNamePart ) {
            $finalFuncName .= strtoupper( $funcNamePart[0] ) . substr( $funcNamePart, 1 );
        }
        //echo "<p>FF: $finalFuncName</P>";
        return $finalFuncName;
    }

    /**
     * Returns the boolean of the forms success. It goes by the simple
     * "form has failed until proven otherwise".
     *
     * @return boolean Whether or not form has succeeded
     */
    public function formSuccess() {
        return $this->_formSuccess;
    }

    /**
     * Checks if the request method is POST
     *
     * @return boolean Whether or not the form has been submitted.
     */
    public function formSubmitted() {
        return $_SERVER["REQUEST_METHOD"] == 'POST';
    }

    /**
     * Runs _runValidation once POST data has been submitted.
     *
     * @return void
     */
    public function runValidation( $_server_tag = 'POST' ) {
        switch ( $_server_tag ) {
        case "POST":
            $this->mServer = $_POST;
            break;
        case "GET":
            $this->mServer = $_GET;
            // print_cx($_GET);
            break;
        default:
            $this->_formSuccess = true;
            return;
            break;

        }
        $this->_runValidation();

        return $this->_formSuccess;
    }

    /**
     * Takes and trims each $this->mServer field, if it has any rules, we parse the rule string and run
     * each rule against the $this->mServer value. Sets formSuccess to true if there are no errors
     * afterwards.
     */
    protected function _runValidation() {

        $this->_forceFail = false;
        // print_cx($this->mServer);
        foreach ( $this->mServer as $inputName => $inputVal ) {
            $this->mServer[$inputName] = trim( $this->mServer[$inputName] );

            if ( array_key_exists( $inputName, $this->_ruleSets ) ) {

                foreach ( $this->_parseRuleString( $this->_ruleSets[$inputName] ) as $eachRule ) {
                    //echo ".2-" . $eachRule;
                    $this->_validateRule( $inputName, $this->mServer[$inputName], $eachRule );
                }
            }
        }

        if ( empty( $this->_errorSet ) && $this->_forceFail === false ) {
            $this->_formSuccess = true;
        }

        return;
    }

    /**
     * Adds a rule to a $this->mServer field.
     *
     * @param string  $inputField Name of the field to add a rule to
     * @param string  $ruleSets   PIPE seperated string of rules
     * @return formValidation Current instance of object.
     */
    public function setRule( $inputField, $inputLabel, $ruleSets ) {
        $this->_ruleSets[$inputField] = $ruleSets;

        $this->_inputLabels[$inputField] = $inputLabel;

        return $this;
    }

    /**
     * Takes an array of rules and uses setRule() to set them, accepts an array
     * of rule names rather than a pipe-delimited string as well.
     *
     * @param array   $ruleSets
     */
    public function setRules( array $ruleSets ) {
        foreach ( $ruleSets as $ruleSet ) {
            $pipeDelimitedRules = null;

            if ( is_array( $ruleSet['rules'] ) ) {
                $pipeDelimitedRules = implode( '|', $ruleSet['rules'] );
            } else {
                $pipeDelimitedRules = $ruleSet['rules'];
            }

            $this->setRule( $ruleSet['name'], $ruleSet['label'], $pipeDelimitedRules );
        }

        return $this;
    }

    /**
     * This method creates the global errors delimiter, each argument occurs once, at the beginning, and
     * end of the errors block respectively.
     *
     * @param string  $start Before block of errors gets displayed, HTML allowed.
     * @param string  $end   After the block of errors gets displayed, HTML allowed.
     * @return void
     */
    public function setErrorsDelimiter( $start, $end ) {
        $this->_allErrorsDelimiter[0] = $start;
        $this->_allErrorsDelimiter[1] = $end;
        return;
    }

    /**
     * This is the individual error delimiter, each argument occurs once before and after
     * each individual error listed.
     *
     * @param string  $start Displayed before each error.
     * @param string  $end   Displayed after each error.
     * @return void
     */
    public function setErrorDelimiter( $start, $end ) {
        $this->_eachErrorDelimiter[0] = $start;
        $this->_eachErrorDelimiter[1] = $end;
        return;
    }

    /**
     * This sets a custom error message that can override the default error phrase provided
     * by Form-Validation, it can be used in the format of setMessage('rule', 'error phrase')
     * which will globally change the error phrase of that rule, or in the format of:
     * setMessage('rule', 'fieldname', 'error phrase') - which will only change the error phrase for
     * that rule, applied on that field.
     *
     * @return boolean True on success, false on failure.
     */
    public function setMessage() {
        $numArgs = func_num_args();

        switch ( $numArgs ) {
        default:
            return false;
            break;

            // A global rule error message
        case 2:
            foreach ( $this->post( null ) as $key => $val ) {
                $this->_errorPhraseOverrides[$key][func_get_arg( 0 )] = func_get_arg( 1 );
            }
            break;

            // Field specific rule error message
        case 3:
            $this->_errorPhraseOverrides[func_get_arg( 1 )][func_get_arg( 0 )] = func_get_arg( 2 );
            break;
        }

        return true;
    }

    /**
     * Adds a custom error message in the errorSet array, that will
     * forcibly display it.
     *
     * @param string  $errorMessage Error to display
     * @return formValidation Current instance of the object
     */
    public function setCustomError( $inputName, $errorMessage ) {
        $errorMessage = str_replace( '%1', $this->_inputLabels[$inputName], $errorMessage );
        $this->_errorSet[] = $errorMessage;
        return $this;
    }

    /**
     * Allows for an accesor to any/all post values, if a value of null is passed as the key, it
     * will recursively find all keys/values of the $this->mServer array. It also automatically trims
     * all values.
     *
     * @param string  $key  Key of $this->mServer to be found, pass null for all Key => Val pairs.
     * @param boolean $trim Defaults to true, trims all $this->mServer values.
     * @return string/array Array of post values if null is passed as key, string if only one key is desired.
     */
    public function post( $key=null, $trim=true ) {

        $returnValue = null;
        // $returnValue = '';

        if ( is_null( $key ) ) {

            $returnValue = array();

            foreach ( $this->mServer as $key => $val ) {
                $returnValue[$key] = $this->post( $key, $trim );
            }
        } else {
            $returnValue = ( array_key_exists( $key, $this->mServer ) ) ? ( ( $trim ) ? trim( $this->mServer[$key] ) : $this->mServer[$key] ) : false;
        }

        return $returnValue;
    }

    /**
     * Gets all errors from errorSet and displays them, can be echoed out from the
     * function or just returned.
     *
     * @param boolean $echo Whether or not the values are to be returned or echoed
     * @return string Errors formatted for output
     */
    public function displayErrors( $limit=null, $echo=true ) {
        list( $errorsStart, $errorsEnd ) = $this->_allErrorsDelimiter;
        list( $errorStart, $errorEnd ) = $this->_eachErrorDelimiter;
        $mError = '';
        $errorOutput = $errorsStart;

        $i = 0;

        if ( !empty( $this->_errorSet ) ) {
            foreach ( $this->_errorSet as $fieldName => $error ) {
                if ( $i === $limit ) { break; }

                $errorOutput .= $errorStart;
                $errorOutput .= $error;
                $mError  = $error;
                $errorOutput .= $errorEnd;

                $i++;
            }
        }

        $errorOutput .= $errorsEnd;
        //echo $mError;
        echo ( $echo ) ? $errorOutput : '';
        return ( !$echo ) ? $errorOutput : null;
    }
    public function displayTextError() {
        $mError = '';
        $errorOutput = '';
        $i = 0;
        if ( !empty( $this->_errorSet ) ) {
            foreach ( $this->_errorSet as $fieldName => $error ) {
                if ( $i === 1 ) { break; }
                $mError  = $error;
                $i++;
            }
        }
        return $mError;
    }

    /**
     * Returns raw array of errors in no format instead of displaying them
     * formatted.
     *
     * @return array
     */
    public function returnErrors() {
        return $this->_errorSet;
    }

    /**
     * Breaks up a PIPE seperated string of rules, and puts them into an array.
     *
     * @param string  $ruleString String to be parsed.
     * @return array Array of each value in original string.
     */
    protected function _parseRuleString( $ruleString ) {
        $ruleSets = array();

        //echo "<p>ru: " . $ruleString . "</P>";

        if ( strpos( $ruleString, "|" ) !== FALSE ) {
            $ruleSets = explode( "|", $ruleString );
        } else {
            $ruleSets[] = $ruleString;
        }

        //cake
        //print_r($ruleSets);

        return $ruleSets;
    }

    /**
     * Returns whether or not a field obtains the rule "required".
     *
     * @param string  $fieldName Field to check if required.
     * @return boolean Whether or not the field is required.
     */
    protected function _fieldIsRequired( $fieldName ) {
        $rules = $this->_parseRuleString( $this->_ruleSets[$fieldName] );

        if ( in_array( 'required', $rules ) ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Takes a $this->mServer input name, it's value, and the rule it's being validated against (ex: max_length[16])
     * and adds an error to the errorSet if it fails validation of the rule.
     *
     * @param string  $inputName Name of $this->mServer field
     * @param string  $inputVal  Value of the $this->mServer field
     * @param string  $ruleName  Rule to be validated against, including args (exact_length[5])
     * @return void
     */
    protected function _validateRule( $inputName, $inputVal, $ruleName ) {
        // Array to store [] args
        $ruleArgs = array();

        // Get the rule arguments, realRule is just the base rule name
        // Like min_length instead of min_length[3]
        $realRule = preg_match( '/\[(.*?)\]/', $ruleName, $ruleArgs );

        $ruleName = preg_replace( '/\[(.*?)\]/', '', $ruleName );

        //echo "<p>".$ruleName."</P>";

        if ( method_exists( $this, $this->_toCallCase( $ruleName ) ) ) {
            $methodToCall = $this->_toCallCase( $ruleName );
            @call_user_func( array( $this, $methodToCall ), $inputName, $ruleName, $ruleArgs );
        }

        return;
    }

    protected function _setError( $inputName, $ruleName, $replacements=array() ) {
        $rulePhraseKeyParts = explode( ',', $ruleName );

        foreach ( $rulePhraseKeyParts as $rulePhraseKeyPart ) {
            if ( array_key_exists( $rulePhraseKeyPart, $this->_errorPhrases ) ) {
                $rulePhrase = $this->_errorPhrases[$rulePhraseKeyPart];
            } else {
                $rulePhrase = $rulePhrase[$rulePhraseKeyPart];
            }
        }

        // Any overrides?
        if ( array_key_exists( $inputName, $this->_errorPhraseOverrides ) && array_key_exists( $ruleName, $this->_errorPhraseOverrides[$inputName] ) ) {
            $rulePhrase = $this->_errorPhraseOverrides[$inputName][$ruleName];
        }

        // Typecast to array in case it's a string
        $replacements = (array) $replacements;

        for ( $i = 1, $replacementCount = count( $replacements ); $i <= $replacementCount; $i++ ) {
            $key = $i - 1;
            $rulePhrase = str_replace( '%' . $i, $replacements[$key], $rulePhrase );
        }

        if ( !array_key_exists( $inputName, $this->_errorSet ) ) {
            $this->_errorSet[$inputName] = $rulePhrase;
        }
    }

    /**
     * Used to run a callback for the callback rule, as well as pass in a default
     * argument of the post value. For example the username field having a rule:
     * callback[userExists] will eval userExists($this->mServer[username]) - Note the use
     * of eval over call_user_func is in case the function is not user defined.
     *
     * @param type    $inputArg
     * @param string  $callbackFunc
     * @return anything
     */
    protected function _runCallback( $inputArg, $callbackFunc ) {

        return eval( $callbackFunc . '("' . $inputArg . '");' );
    }

    /**
     * Used for applying a rule only if the empty callback evaluates to true,
     * for example required[funcName] - This runs funcName without passing any
     * arguments.
     *
     * @param string  $callbackFunc
     * @return anything
     */
    protected function _runEmptyCallback( $callbackFunc ) {
        return eval( 'return ' . $callbackFunc . '();' );
    }

    /**
     * Gets a specific label of a specific field input name.
     *
     * @param string  $inputName
     * @return string
     */
    protected function _getLabel( $inputName ) {
        return ( array_key_exists( $inputName, $this->_inputLabels ) ) ? $this->_inputLabels[$inputName] : $inputName;
    }

    protected function _validateHoneypot( $inputName, $ruleName, array $ruleArgs ) {
        if ( $this->mServer[$inputName] != '' ) {
            $this->_forceFail = true;
        }
    }

    protected function _validateCallback( $inputName, $ruleName, array $ruleArgs ) {
        if ( function_exists( $ruleArgs[1] ) && !empty( $this->mServer[$inputName] ) ) {
            $this->_runCallback( $this->mServer[$inputName], $ruleArgs[1] );
        }
    }

    protected function _validateDepends( $inputName, $ruleName, array $ruleArgs ) {
        if ( array_key_exists( $ruleArgs[1], $this->_errorSet ) ) {
            $this->_setError( $inputName, $ruleName, array( $this->_getLabel( $inputName ), $this->_getLabel( $ruleArgs[1] ) ) );
        }
    }

    protected function _validateNotEqual( $inputName, $ruleName, array $ruleArgs ) {
        $canNotEqual = explode( ',', $ruleArgs[1] );

        foreach ( $canNotEqual as $doNotEqual ) {
            $inputVal = $this->post( $inputName );

            if ( preg_match( '/post:(.*)/', $doNotEqual ) ) {
                if ( $inputVal == $this->mServer[str_replace( 'post:', '', $doNotEqual )] ) {
                    $this->_setError( $inputName, $ruleName . ',post:key', array( $this->_getLabel( $inputName ), $this->_getLabel( str_replace( 'post:', '', $doNotEqual ) ) ) );
                    continue;
                }
            } else {
                if ( $inputVal == $doNotEqual ) {
                    $this->_setError( $inputName, $ruleName . ',string', array( $this->_getLabel( $inputName ), $doNotEqual ) );
                    continue;
                }
            }
        }
    }

    protected function _validateMatches( $inputName, $ruleName, array $ruleArgs ) {
        $inputVal = $this->post( $inputName );

        if ( $inputVal != $this->mServer[$ruleArgs[1]] ) {
            $this->_setError( $inputName, $ruleName, array( $this->_getLabel( $inputName ), $this->_getLabel( $ruleArgs[1] ) ) );
        }
    }

    protected function _validateValidEmail( $inputName, $ruleName, array $ruleArgs ) {
        $inputVal = $this->post( $inputName );

        if ( !preg_match( "/^([\w\!\#$\%\&\'\*\+\-\/\=\?\^\`{\|\}\~]+\.)*[\w\!\#$\%\&\'\*\+\-\/\=\?\^\`{\|\}\~]+@((((([a-z0-9]{1}[a-z0-9\-]{0,62}[a-z0-9]{1})|[a-z])\.)+[a-z]{2,6})|(\d{1,3}\.){3}\d{1,3}(\:\d{1,5})?)$/i", $inputVal ) ) {
            if ( !$this->_fieldIsRequired( $inputName ) && empty( $this->mServer[$inputName] ) ) {
                break;
            }

            $this->_setError( $inputName, $ruleName, $this->_getLabel( $inputName ) );
        }
    }

    protected function _validateExactLength( $inputName, $ruleName, array $ruleArgs ) {

        $inputVal = $this->post( $inputName );
        if ( strlen( $inputVal ) != $ruleArgs[1] ) { // $ruleArgs[0] is [length] $rulesArgs[1] is just length
            if ( !$this->_fieldIsRequired( $inputName ) && empty( $this->mServer[$inputName] ) ) {
                break;
            }

            $this->_setError( $inputName, $ruleName, array( $this->_getLabel( $inputName ), $this->_getLabel( $ruleArgs[1] ) ) );
        }
    }

    protected function _validateMaxLength( $inputName, $ruleName, array $ruleArgs ) {
        $inputVal = $this->post( $inputName );
        if ( strlen( $inputVal ) > $ruleArgs[1] ) { // $ruleArgs[0] is [length] $rulesArgs[1] is just length
            if ( !$this->_fieldIsRequired( $inputName ) && empty( $this->mServer[$inputName] ) ) {
                break;
            }

            $this->_setError( $inputName, $ruleName, array( $this->_getLabel( $inputName ), $this->_getLabel( $ruleArgs[1] ) ) );
        }
    }



    protected function _validateMinLength( $inputName, $ruleName, array $ruleArgs ) {
        $inputVal = $this->post( $inputName );

        if ( strlen( $inputVal ) < $ruleArgs[1] ) { // $ruleArgs[0] is [length] $rulesArgs[1] is just length
            if ( !$this->_fieldIsRequired( $inputName ) && empty( $this->mServer[$inputName] ) ) {
                break;
            }

            $this->_setError( $inputName, $ruleName, array( $this->_getLabel( $inputName ), $this->_getLabel( $ruleArgs[1] ) ) );
        }
    }

    protected function _validateRequired( $inputName, $ruleName, array $ruleArgs ) {
        $inputVal = $this->post( $inputName );

        if ( array_key_exists( 1, $ruleArgs ) && function_exists( $ruleArgs[1] ) ) {
            $callbackReturn = $this->_runEmptyCallback( $ruleArgs[1] );

            if ( $inputVal == '' && $callbackReturn == true ) {
                $this->_setError( $inputName, $ruleName, $this->_getLabel( $inputName ) );
            }
        } elseif ( $inputVal == '' ) {
            $this->_setError( $inputName, $ruleName, $this->_getLabel( $inputName ) );
        }
    }

    //cake model
    //判斷數字
    protected function _validateDigit( $inputName , $ruleName, array $ruleargs ) {
        $inputVal = $this->post( $inputName );
        if ( !$this->_cx_checkValue( $inputVal, 2 ) ) {
            $this->_setError( $inputName, $ruleName, $this->_getLabel( $inputName ) );

        }
        //echo "<P>i:". $inputVal . "</P>";
        //echo "<P>ruleName:" . $ruleName . "</P>";
        //print_r($rulergs);

    }
    //cake model
    //判斷 英數混合
    protected function _validateAlnum( $inputName , $ruleName, array $ruleargs ) {
        $inputVal = $this->post( $inputName );
        if ( !$this->_cx_checkValue( $inputVal, 3 )) {
            $this->_setError( $inputName, $ruleName, $this->_getLabel( $inputName ) );

        }
    }
    //cake model
    //判斷英文
    protected function _validateAlpha( $inputName , $ruleName, array $ruleargs ) {
        $inputVal = $this->post( $inputName );
        if ( !$this->_cx_checkValue( $inputVal, 1 ) ) {
            $this->_setError( $inputName, $ruleName, $this->_getLabel( $inputName ) );

        }
    }

    //cake model
    //判斷sql 危險字元
    protected function _validateSql( $inputName , $ruleName, array $ruleargs ) {
        $inputVal = $this->post( $inputName );
        if ( !$this->_cx_checkValue( $inputVal, 4 )) {
            // echo "error:" . $inputVal;
            $this->_setError( $inputName, $ruleName, $this->_getLabel( $inputName ) );

        }
        // echo "<br>";
    }

    private function _cx_checkValue( $Input, $Type='', $len='' ) {
        $Str = trim( $Input ); //清空前後空白
        $Clean_Str = $Str;
        if ( !get_magic_quotes_gpc() )$Clean_Str = mysql_real_escape_string( $Str );//消去危險字元
        if ( $len && strlen( $Clean_Str ) > $len ) { //最大字串長度檢查,若太大就切斷後面的
            $Str = substr( $Clean_Str, 0, $len );
        }
        $Chk = true;
        switch ( $Type ) {
        case '1':   //英文
            $Chk = ctype_alpha( $Clean_Str );
            break;
        case '2':   //數字
            $Chk = ctype_digit( $Clean_Str );
            break;
        case '3':   //英數混合
            $Chk = ctype_alnum( $Clean_Str );
            break;
        case '4':   //SQL 危險字元
            $_symbol_q = 'null';
            if ( substr_count( $Clean_Str, "'" )<>0 )       $_symbol_q = $_symbol_q ."'";
            if ( substr_count( $Clean_Str, "+" )<>0 )       $_symbol_q = $_symbol_q ."+";
            if ( substr_count( $Clean_Str, "%" )<>0 )       $_symbol_q = $_symbol_q ."%";
            if ( substr_count( $Clean_Str, "=" )<>0 )       $_symbol_q = $_symbol_q ."=";
            if ( substr_count( $Clean_Str, "--" )<>0 )      $_symbol_q = $_symbol_q ."--";
            if ( substr_count( $Clean_Str, " _" )<>0 )      $_symbol_q = $_symbol_q ." _";
            if ( substr_count( $Clean_Str, " and " )<>0 )   $_symbol_q = $_symbol_q ." and ";
            if ( substr_count( $Clean_Str, " or " )<>0 )    $_symbol_q = $_symbol_q ." or ";
            if ( substr_count( $Clean_Str, "script" )<>0 )  $_symbol_q = $_symbol_q ."script";
            if ( substr_count( $Clean_Str, "UNION" )<>0 )   $_symbol_q = $_symbol_q ."UNION";
            if ( substr_count( $Clean_Str, "SELECT" )<>0 )  $_symbol_q = $_symbol_q ."SELECT";
            if ( substr_count( $Clean_Str, "FROM" )<>0 )    $_symbol_q = $_symbol_q ."FROM";
            if ( substr_count( $Clean_Str, "WHERE" )<>0 )   $_symbol_q = $_symbol_q ."WHERE";
            if ( substr_count( $Clean_Str, "VALUES" )<>0 )  $_symbol_q = $_symbol_q ."VALUES";
            if ( substr_count( $Clean_Str, "UPDATE" )<>0 )  $_symbol_q = $_symbol_q ."UPDATE";
            if ( substr_count( $Clean_Str, "INSERT" )<>0 )  $_symbol_q = $_symbol_q ."INSERT";
            // echo "xxx:" . $Clean_Str . "  q:" .$_symbol_q . " = ";
            $Chk = true;
            // exit;
            ( $_symbol_q != 'null' ) and $Chk = false;
            break;
        default:
            $Chk = true;
            break;
        }
        ( $Clean_Str == '' ) and $Clean_Str = true;

        // if ( $Chk == 1 ) {
        //     // echo "Chk:1 =>";
        //     return 1;
        // }else {
        //     return 2;
        // }
        return $Chk;
    }

}
