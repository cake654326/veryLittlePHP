<?php
/**
 * $log->lfile('/tmp/mylog.txt');
 * // write message to the log file
 * $log->lwrite('Test message1');
 * $log->lwrite('Test message2');
 * $log->lwrite('Test message3');
 * // close log file
 * $log->lclose();
 * */
class cx_log {
	// declare log file and file pointer as private properties
	private $log_file, $fp;
	// set log file (path and name)
	public function lfile( $path ) {
		$this->log_file = $path;
	}
	// write message to the log file
	public function lwrite( $message ) {
		// if file pointer doesn't exist, then open log file
		if ( !is_resource( $this->fp ) ) {
			$this->lopen();
		}
		// define script name
		$script_name = pathinfo( $_SERVER['PHP_SELF'], PATHINFO_FILENAME );
		// define current time and suppress E_WARNING if using the system TZ settings
		// (don't forget to set the INI setting date.timezone)
		$time = @date( '[Y/m/d - H:i:s]' );
		// write current time, script name and message to the log file
		fwrite( $this->fp, "$time ($script_name) $message" . PHP_EOL );
	}
	// close log file (it's always a good idea to close a file when you're done with it)
	public function lclose() {
		fclose( $this->fp );
	}
	// open log file (private method)
	private function lopen() {
		/*
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $log_file_default = 'c:/php/logfile.txt';
        }else {
            $log_file_default = '/tmp/logfile.txt';
        }
        */
		$lfile = $this->log_file ? $this->log_file : $log_file_default;
		// open log file for writing only and place file pointer at the end of the file
		// (if the file does not exist, try to create it)
		//$this->fp = fopen( $lfile, 'a' ) or exit( "Can't open $lfile!" );
		$this->fp = fopen( $lfile, 'a' );
		if(!$this->fp)return false;
	}

}


?>
