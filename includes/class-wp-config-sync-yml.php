<?php

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;

class Wp_Config_Sync_Yml {
	
	protected $dump;
	
	protected $parse;
	
	public function dump( $data, $file ) {
		$this->dump = Yaml::dump( $data );
		return $this->dumpToFile( $file, $this->dump );
	}
	
	public function parse( $file ) {
		try {
			$this->parse = Yaml::parseFile( $file );
			return $this->parse;
		} catch ( ParseException $e ) {
			WP_CLI::error( $e->getMessage() );
		}
	}
	
	private function dumpToFile( $file, $data ) {
		if( file_put_contents( $file, $data ) ) {
			return true;
		} else {
			throw new Exception("Could not write to file.");
		}
	}
}
