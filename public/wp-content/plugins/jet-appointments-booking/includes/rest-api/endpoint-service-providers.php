<?php
namespace JET_APB\Rest_API;

use Elementor\Core\Files\CSS\Post;
use JET_APB\Plugin;

class Endpoint_Service_Providers extends \Jet_Engine_Base_API_Endpoint {

	/**
	 * Returns route name
	 *
	 * @return string
	 */
	public function get_name() {
		return 'appointment-service-providers';
	}

	/**
	 * API callback
	 *
	 */
	public function callback( $request ) {

		$params          = $request->get_params();
		$service         = ! empty( $params['service'] ) ? absint( $params['service'] ) : 0;
		$custom_template = ! empty( $params['custom_template'] ) ? absint( $params['custom_template'] ) : 0;
		$args_str        = ! empty( $params['args_str'] ) ? $params['args_str'] : '';
		$is_ajax         = ! empty( $params['is_ajax'] ) ? $params['is_ajax'] : false;
		$namespace       = ! empty( $params['namespace'] ) ? $params['namespace'] : 'jet-form';

		if ( ! $service ) {
			return rest_ensure_response( array(
				'success' => false,
			) );
		}

		$providers = Plugin::instance()->tools->get_providers_for_service( $service );

		if ( ! $custom_template ) {
			return rest_ensure_response( array(
				'success' => true,
				'data'    => $providers,
			) );
		} else {

			$builder = $this->get_namespace_provider( $namespace );
			$checked = null;
			$result  = array();

			if ( $is_ajax && $custom_template ) {
				ob_start();
				if ( ! jet_engine()->blocks_views->is_blocks_listing( $custom_template ) ) {
					$css_file = new Post( $custom_template );
					$css_file->print_css();
				}
				$result[] = ob_get_clean();
			}

			foreach ( $providers as $provider ) {

				$args = array(
					'field_options_from'      => 'posts',
					'custom_item_template_id' => $custom_template,
				);

				ob_start();

				$template    = $builder->get_custom_template( $provider->ID, $args );
				$data_switch = null;

				?>
                <div class="<?= $namespace ?>__field-wrap radio-wrap checkradio-wrap">
					<?php if ( $template ) {
						echo $template;
					} ?>
                    <label class="<?= $namespace ?>__field-label">
                        <input
                                type="radio"
                                class="<?= $namespace ?>__field radio-field checkradio-field"
                                value="<?php echo $provider->ID; ?>"
							<?php echo $checked; ?>
							<?php echo $args_str; ?>
							<?php echo $data_switch; ?>
                        >
                    </label>
                </div>
				<?php

				$result[] = ob_get_clean();

			}


			return rest_ensure_response( array(
				'success' => true,
				'data'    => implode( '', $result ),
			) );

		}


	}

	/**
	 * Returns endpoint request method - GET/POST/PUT/DELTE
	 *
	 * @return string
	 */
	public function get_method() {
		return 'GET';
	}

	/**
	 * Returns arguments config
	 *
	 * @return array
	 */
	public function get_args() {
		return array(
			'service'         => array(
				'default'  => '',
				'required' => true,
			),
			'custom_template' => array(
				'default'  => '',
				'required' => false,
			),
			'args_str'        => array(
				'default'  => '',
				'required' => false,
			),
		);
	}

	private function get_namespace_provider( $namespace ) {
		if ( 'jet-form-builder' === $namespace && class_exists( '\Jet_Form_Builder\Classes\Builder_Helper' ) ) {
			return new \Jet_Form_Builder\Classes\Builder_Helper();
		}

		if ( ! class_exists( '\Jet_Engine_Booking_Forms_Builder' ) ) {
			require_once jet_engine()->modules->modules_path( 'forms/builder.php' );
		}

		return new \Jet_Engine_Booking_Forms_Builder();
	}

}