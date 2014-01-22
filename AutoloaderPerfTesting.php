<?php
namespace AutoloaderPerfTesting;

use \AutoLoader;
use \Exception;

$reps = 10000;

$wgAutoloadClasses['AutoloaderPerfTesting\Profiler'] = __DIR__ . '/includes/Profiler.php';

Profiler::begin("AutoloaderPerfTesting_setupGlobal");
$dir = __DIR__ . '/generated/legacyclasses/';
for ( $i = 0; $i < $reps; $i++ ) {
	$wgAutoloadClasses['AutoloaderPerfTesting\Generated\RandomlyNamespaced\TestClass' . $i] = $dir . 'TestClass' . $i . '.php';
}
Profiler::end();

if ( is_callable( 'AutoLoader::registerNamespace' ) ) {
	// Time the register function
	Profiler::begin("AutoloaderPerfTesting_setupRegisterFunc");
	for ( $i = 0; $i < $reps; $i++ ) {
		AutoLoader::registerNamespace( 'AutoloaderPerfTesting\Bogus', __DIR__ . '/generated/null/' . $i );
	}
	Profiler::end();

	AutoLoader::registerNamespace( 'AutoloaderPerfTesting\Generated\Psr4Classes', __DIR__ . '/generated/psr4classes' );
}
