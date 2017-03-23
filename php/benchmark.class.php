<?php

class Benchmark {

	static $benchmarkStart = 0;

	
	/**
	 * starts a benchmark
	 * return start time, but one copy is kept privatly for convinience
	 * @return float
	 */
	static function start() {
		$start = microtime(true);

		// we memorize only the first call
		if(!self::$benchmarkStart) {
			self::$benchmarkStart = $start;
		}

		return $start;
	}


	/**
	 * stops the benchmark and returns the result
	 * @param float $start_ we can set the time we want for sub parts of the program if we want
	 * @return float with 3 decimals
	 */
	static function stop($start_=null) {
		$stop = microtime(true);
		if($start_) {
			$start = $start_;
			//pre("manual Start $start");
		}
		else if(self::$benchmarkStart) {
			$start = self::$benchmarkStart;
		}
		else {
			$start = null;
		}
		//pre("stop $stop");

		if($start) {
			// simple formating
			$time = $stop-$start;
			$time_format = substr((string)$time, 0, 6);
		}
		else {
			$time_format = "Start Time Error";
		}
		return $time_format;
	}
}
