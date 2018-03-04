<?php

class Wp_Config_Sync_Options {

	public function getOptions() {
		return wp_load_alloptions();
	}
	
	public function updateOption( $name, $value ) {
		return update_option( $name, $value );
	}
	
	public function unserializeOptions( $options ) {
		foreach( $options as $index => $value ) {
			if (unserialize( $value )) {
				$config[$index] = unserialize( $value );
			} else {
				$config[$index] = $value;
			}
		}
		
		return $config;
	}
	
	public function serializeOptions( $options ) {
		foreach( $options as $index => $option ) {
			if( is_array( $option ) ) {							
				$config[$index] = serialize( $option );
			} else {
				$config[$index] = $option;
			}
		}
		
		return $config;
	}

}