<?php
namespace AutoloaderPerfTesting;

$IP = getenv( 'MW_INSTALL_PATH' );
if ( $IP === false ) {
	$IP = __DIR__  . '/../../..';
}
require_once $IP . '/maintenance/Maintenance.php';

use \Maintenance;

class GenerateTestClasses extends Maintenance {
	protected $reps = 10000;

	public function __construct() {
		parent::__construct();
		$this->mDescription = "Generate profiling test classes";
		$this->addOption( 'count', 'Override number of classes to create' );
	}

	public function execute() {
		$this->output( "Generating test classes..." );

		$this->reps = $this->getOption( 'count', $this->reps );
		$this->output( "Counting to {$this->reps}... " );

		for ( $i = 0; $i < $this->reps; $i++ ) {
			$classname = "TestClass{$i}";
			file_put_contents( __DIR__ . "/../generated/psr4classes/{$classname}.php",
"<?php
namespace AutoloaderPerfTesting\\Generated\\Psr4Classes;
class {$classname} {
}
" );

			file_put_contents( __DIR__ . "/../generated/legacyclasses/{$classname}.php",
"<?php
namespace AutoloaderPerfTesting\\Generated\\RandomlyNamespaced;
class {$classname} {
}
" );
		}

		$this->output( "done.\n" );
	}
}

$maintClass = 'AutoloaderPerfTesting\GenerateTestClasses';
require_once RUN_MAINTENANCE_IF_MAIN;
