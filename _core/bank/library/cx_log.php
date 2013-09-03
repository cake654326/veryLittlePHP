<?php
/**
 * EX:
 * $log->lfile('/tmp/mylog.txt');
 * // write message to the log file
 * $log->lwrite('Test message1');
 * $log->lwrite('Test message2');
 * $log->lwrite('Test message3');
 * // close log file
 * $log->lclose();
 * */
class cx_log {
	private $log_file, $fp;
	public function lfile( $path ) {
		$this->log_file = $path;
	}
	public function lwrite( $message ) {

		if ( !is_resource( $this->fp ) ) {
			$this->lopen();
		}

		$script_name = pathinfo( $_SERVER['PHP_SELF'], PATHINFO_FILENAME );

		$time = @date( '[Y/m/d - H:i:s]' );

		fwrite( $this->fp, "$time ($script_name) $message" . PHP_EOL );
	}

	public function lclose() {
		fclose( $this->fp );
	}

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
		//$this->fp = fopen( $lfile, 'a' ) or exit();
		$this->fp = fopen( $lfile, 'a' );



		if(!$this->fp)return false;
	}

}


?>
