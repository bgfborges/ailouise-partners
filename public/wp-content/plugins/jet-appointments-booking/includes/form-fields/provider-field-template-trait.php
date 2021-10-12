<?php


namespace JET_APB\Form_Fields;

use Elementor\Core\Files\CSS\Post;
use JET_APB\Plugin;

//use JET_SM\Gutenberg\Style_Manager;

/**
 * @method getArgs( $key = '', $ifNotExist = false )
 * @method isRequired()
 * @method isNotEmptyArg( $key )
 * @method getCustomTemplate( $provider_id, $args )
 * @method scopeClass( $suffix = '' )
 * @method is_block_editor()
 *
 * Trait Provider_Field_Template_Trait
 * @package JET_APB
 */
trait Provider_Field_Template_Trait {

	/**
	 * Check if provider field is already rendered
	 *
	 * @var boolean
	 */
	public $_provider_done = false;

	/**
	 * Render provider field tempalte
	 *
	 * @return [type] [description]
	 */
	public function field_template() {

		if ( ! $this->_provider_done ) {

			add_action( 'wp_footer', function () {

				ob_start();
				include JET_APB_PATH . 'assets/js/public/providers-init.js';
				$init_script = ob_get_clean();

				printf( '<script>%s</script>', $init_script );

			}, 99 );

			if ( wp_doing_ajax() ) {

				$init_script = "var JetAPBisAjax = true;\n";

				ob_start();
				include JET_APB_PATH . 'assets/js/public/providers-init.js';
				$init_script .= ob_get_clean();

				printf( '<script>%s</script>', $init_script );

			}

			$this->_provider_done = true;
		}

		$service_data = jet_apb()->form->get_service_field_data( $this->getArgs() );
		$placeholder  = $this->getArgs( 'default', esc_html__( 'Select...', 'jet-appointments-booking' ) );

		$dataset = array(
			'service'     => $service_data['form_field'],
			'api'         => Plugin::instance()->rest_api->get_urls(),
			'placeholder' => $placeholder,
		);

		$args_str = 'name="' . $this->getArgs( 'name' ) . '"';

		if ( $this->isRequired() ) {
			$args_str .= ' required';
		}

		$providers_list = '';
		$providers      = array();

		if ( ! empty( $service_data['id'] ) ) {
			$providers = Plugin::instance()->tools->get_providers_for_service( $service_data['id'] );

			if ( ! empty( $providers ) ) {
				$providers_list .= '<option value="">' . $placeholder . '</option>';
				foreach ( $providers as $provider ) {
					$providers_list .= '<option value="' . $provider->ID . '">' . $provider->post_title . '</option>';
				}
			}

		}

		if ( $this->isNotEmptyArg( 'switch_on_change' ) ) {
			$args_str .= ' data-switch="1"';
		}

		$custom_template    = $this->getArgs( 'appointment_provider_custom_template' );
		$custom_template_id = $this->getArgs( 'appointment_provider_custom_template_id' );


		if ( $custom_template && $custom_template_id ) {
			$is_blocks_editor = jet_engine()->blocks_views->is_blocks_listing( $custom_template_id );

			if ( ! $is_blocks_editor && $this->is_block_editor() ) {
				return $this->renderManual( $dataset, $args_str, $providers_list );
			}

			$options                         = '';
			$args['custom_item_template_id'] = $custom_template_id;
			$default                         = $this->getArgs( 'default' );
			$checked                         = '';

			jet_apb()->form->enqueue_deps( $custom_template_id );
			jet_engine()->frontend->set_listing( $custom_template_id );

			if ( ! jet_engine()->blocks_views->is_blocks_listing( $custom_template_id ) ) {
				$css_file = new Post( $custom_template_id );
				$css_file->enqueue();
			} elseif ( class_exists( 'JET_SM\Gutenberg\Style_Manager' ) ) {
				( new \JET_SM\Gutenberg\Style_Manager() )->render_blocks_style(
					$custom_template_id,
					'<style class="jet-sm-gb-style--jet-fb-provider-field">%s</style>'
				);
			}

			foreach ( $providers as $provider ) {
				$custom_template = $this->getCustomTemplate( $provider->ID, $args );
				$data_switch     = null;

				if ( $default ) {
					$checked = checked( $default, $provider->ID, false );
				}

				ob_start();
				?>
                <div class="<?php echo $this->scopeClass( '__field-wrap' ) ?> radio-wrap checkradio-wrap">
					<?php if ( $custom_template ) {
						echo $custom_template;
					} ?>
                    <label class="<?php echo $this->scopeClass( '__field-label' ) ?>">
                        <input
                                type="radio"
                                class="<?php echo $this->scopeClass( '__field' ) ?> radio-field checkradio-field"
                                value="<?php echo $provider->ID; ?>"
							<?php echo $checked; ?>
							<?php echo $args_str; ?>
							<?php echo $data_switch; ?>
                        >
                    </label>
                </div>
				<?php

				$options .= ob_get_clean();

			}

			wp_reset_postdata();
			wp_reset_query();

			$dataset['custom_template'] = $custom_template_id;
			$dataset['args_str']        = $args_str;

			$loader = '<div class="appointment-provider__loader appointment-provider__loader-hidden"><svg width="38" height="38" viewBox="0 0 38 38" xmlns="http://www.w3.org/2000/svg" stroke="rgba( 0, 0, 0, .3 )"><g fill="none" fill-rule="evenodd"><g transform="translate(1 1)" stroke-width="2"><circle stroke-opacity=".5" cx="18" cy="18" r="18"/><path d="M36 18c0-9.94-8.06-18-18-18"><animateTransform attributeName="transform" type="rotate" from="0 18 18" to="360 18 18" dur="1s" repeatCount="indefinite"/></path></g></g></svg></div>';

			return sprintf(
				'<div class="appointment-provider %4$s__fields-group checkradio-wrap" data-args="%1$s"><div class="appointment-provider__content">%2$s</div>%3$s</div>',
				htmlspecialchars( json_encode( $dataset ) ),
				$options,
				$loader,
				$this->scopeClass()
			);

		} else {
			return $this->renderManual( $dataset, $args_str, $providers_list );
		}

	}

	public function renderManual( $dataset, $args_str, $providers_list ) {
		return sprintf(
			'<select class="appointment-provider %4$s__field select-field" %2$s data-args="%1$s">%3$s</select>',
			htmlspecialchars( json_encode( $dataset ) ),
			$args_str,
			$providers_list,
			$this->scopeClass()
		);
	}

}