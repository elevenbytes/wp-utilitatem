<?php
declare(strict_types=1);

namespace WpUtm\Plugin;

defined( 'ABSPATH' ) || exit;

class Main {

	public function __construct(
		public Assets $assets
	) {}

	public function init(): void {
		$this->assets->init();
	}
}
