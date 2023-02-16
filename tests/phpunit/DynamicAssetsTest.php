<?php

namespace WpUtm\Test;

class DynamicAssetsTest extends \WP_UnitTestCase {
	public function test_non_enqueued_assets_dont_run() {
		\ob_start();
		\wp_head();
		\ob_end_clean();
		$this->assertEmpty( \get_option( 'nonexisting_asset_js_ran' ) );
	}

	public function test_enqueued_assets_run() {
		\ob_start();
		\wp_head();
		\ob_end_clean();
		$this->assertNotEmpty( \get_option( 'wputm_main_js_ran' ) );
	}
}
