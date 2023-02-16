<?php

namespace WpUtm;

class Util {

	public function __construct(
		public string $main_file,
		public string $type
	) {}

	public function get_asset_url( string $path ) {
		$path = ltrim( $path, '/' );
		if ( $this->type === 'theme' ) {
			return \get_stylesheet_directory_uri() . '/' . $path;
		}

		return \plugins_url( $path, $this->main_file );
	}

	public function get_asset_abs_path( string $path ) {
		$path = ltrim( $path, '/' );

		if ( $this->type === 'theme' ) {
			return \get_stylesheet_directory() . '/' . $path;
		}

		return \plugin_dir_path( $this->main_file ) . $path;
	}

}
