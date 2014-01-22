<?php
namespace AutoloaderPerfTesting;

$IP = getenv( 'MW_INSTALL_PATH' );
if ( $IP === false ) {
	$IP = __DIR__  . '/../../..';
}
require_once $IP . '/maintenance/Maintenance.php';

use \Exception;
use \Maintenance;

class RunProfiling extends Maintenance {

	public function __construct() {
		parent::__construct();
		$this->mDescription = "Run AutoLoader profiling test";
	}

	public function execute() {
		$this->output( "Beginning tests..." );

		$reps = 10000;

		Profiler::begin("AutoloaderPerfTesting_lookupLegacy");
		for ( $i = 0; $i < $reps; $i++ ) {
			if ( !class_exists( 'AutoloaderPerfTesting\Generated\RandomlyNamespaced\TestClass' . $i ) ) {
				throw new Exception( "Failed to find a test class." );
			}
		}
		Profiler::end();

		if ( is_callable( 'AutoLoader::registerNamespace' ) ) {
			Profiler::begin("AutoloaderPerfTesting_lookupPsr4");
			for ( $i = 0; $i < $reps; $i++ ) {
				if ( !class_exists( 'AutoloaderPerfTesting\Generated\Psr4Classes\TestClass' . $i ) ) {
					throw new Exception( "Failed to find a test class." );
				}
			}
			Profiler::end();
		}

		$this->output( "done.\n" );
	}
}

$maintClass = 'AutoloaderPerfTesting\RunProfiling';
require_once RUN_MAINTENANCE_IF_MAIN;
