<?php

namespace WpUtm\Test;

class ScriptsEnqueueingTest extends \WP_UnitTestCase {
	public function test_footer_scripts_enqueued_in_footer() {
		\ob_start();
		\wp_footer();
		\ob_end_clean();
		$this->assertEquals( 1, $GLOBALS['wp_scripts']->registered['wputm-footer-script']->extra['group'] );
	}

	public function test_header_scripts_enqueued_in_header() {
		\ob_start();
		\wp_footer();
		\ob_end_clean();
		$this->assertEmpty( $GLOBALS['wp_scripts']->registered['wputm-header-script']->extra );
	}
}
