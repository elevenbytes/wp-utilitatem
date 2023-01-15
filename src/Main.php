<?php
declare(strict_types=1);

namespace WpUtm;

use DI\ContainerBuilder;
use DI\Container;
use Psr\Container\ContainerInterface;

use WpUtm\Interfaces\IDynamicCss;
use WpUtm\Interfaces\IDynamicJs;

class Main {

	private ?ContainerInterface $container = null;

	/**
	 * @param $args {
	 *    @type array $definitions Container definitions.
	 * }
	 */
	public function __construct( array $args ) {
		$_args = \wp_parse_args(
			$args,
			array(
				'definitions' => array(),
				'main_file'   => '',
			)
		);

		$this->get_container( $_args['definitions'] );
	}

	public function make( string $thing, array $args ) {
		if ( ! empty( $args ) ) {
			return $this->container->make( $thing, $args );
		}

		return $this->container->make( $thing );
	}

	public function get( string $thing ) {
		return $this->container->get( $thing );
	}

	public function get_container( array $definitions = array() ): ContainerInterface {
		if ( $this->container ) {
			return $this->container;
		}

		$definitions[ AssetsRegistration::class ] = \DI\autowire()->constructor( \DI\get( IDynamicCss::class ), \DI\get( IDynamicJs::class ), \DI\get( Util::class ), \DI\get( 'prefix' ) );
		$definitions[ Util::class ]               = \DI\autowire()->constructor( \DI\get( 'main_file' ), \DI\get( 'type' ) );

		$builder = new ContainerBuilder();
		$builder->addDefinitions( $definitions );
		$this->container = $builder->build();
		return $this->container;
	}
}
