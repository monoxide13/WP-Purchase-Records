<?php
if(!function_exists('hit_log')){
   function hit_log($log){
      $logFile = fopen('/var/www/rv/rv.log', 'a');
	  if($logFile==false)
	  	return;
      if ( is_array( $log ) || is_object( $log ) ) {
         fwrite($logFile, print_r( $log, true ));
      } else {
         fwrite($logFile, $log."\n");
      }
	  fclose($logFile);
   }
}
