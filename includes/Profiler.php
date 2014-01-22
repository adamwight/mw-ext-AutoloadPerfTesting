<?php
namespace AutoloaderPerfTesting;

use \XHProfRuns_Default;

class Profiler {
	static $name;

	static function begin( $testName ) {
		self::$name = $testName;
		xhprof_enable( XHPROF_FLAGS_CPU | XHPROF_FLAGS_MEMORY );
	}

	static function end() {
		$xhprof_data = xhprof_disable();

		require_once 'xhprof_lib/utils/xhprof_lib.php';
		require_once 'xhprof_lib/utils/xhprof_runs.php';

		$xhprof_runs = new XHProfRuns_Default();

		$run_id = $xhprof_runs->save_run( $xhprof_data, self::$name );
	}

	static function assertXhprof() {
		if ( !function_exists( 'xhprof_enable' ) ) {
			throw new Exception( "XHProf is not installed.  Try again later." );
		}
	}
}
