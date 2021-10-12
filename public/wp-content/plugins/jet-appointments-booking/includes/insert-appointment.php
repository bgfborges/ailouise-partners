<?php


namespace JET_APB;

use JET_APB\Vendor\Actions_Core\Base_Handler_Exception;
use Jet_Form_Builder\Exceptions\Action_Exception;

/**
 * @method setRequest( $key, $value )
 * @method getSettings()
 * @method hasGateway()
 * @method getRequest( $key = '', $ifNotExist = false )
 * @method issetRequest( $key )
 *
 * Trait Insert_Appointment
 * @package JET_APB
 */
trait Insert_Appointment {

	/**
	 * @return array
	 * @throws Base_Handler_Exception
	 */
	public function run_action() {
		
		$args                = $this->getSettings();
		$data                = $this->getRequest();
		$notification_log    = false;
		$appointment_id_list = [];
		$parent_appointment  = false;
		$appointments_field  = ! empty( $args['appointment_date_field'] ) ? $args['appointment_date_field'] : false;
		$appointments        = ! empty( $data[ $appointments_field ] ) ? json_decode( wp_specialchars_decode( stripcslashes( $data[ $appointments_field ] ), ENT_COMPAT ), true ) : false ;
		$email_field         = ! empty( $args['appointment_email_field'] ) ? $args['appointment_email_field'] : false;
		$email               = ! empty( $data[ $email_field ] ) ? sanitize_email( $data[ $email_field ] ) : false;
		
		if ( ! $appointments || ! $email || ! is_email( $email ) ) {
			throw new Base_Handler_Exception( 'failed', '', $email_field );
		}
		
		$db_columns         = Plugin::instance()->settings->get( 'db_columns' );
		$format             = Plugin::instance()->settings->get( 'slot_time_format' );
		$multi_booking      = Plugin::instance()->settings->get( 'multi_booking' );
		$appointments_count = count( $appointments );
		$group_ID           = $multi_booking && $appointments_count > 1 ? Plugin::instance()->db->appointments->get_max_int( 'group_ID' ) + 1 : NULL ;
		
		if( $appointments_count > 1 ){
			usort( $appointments, function( $item_1, $item_2 ){
				return ( $item_1->slot < $item_2->slot ) ? 1 : -1;
			});
		}
		
		foreach ( $appointments as $key => $appointment ){
			$price = ! empty( $appointment['price'] ) ? intval( $appointment['price'] ) : false ;
			
			foreach ( $appointment as $field => $value ){
				switch ( $field ) {
					case 'slotEnd':
						unset( $appointment[ $field ] );
						$field = 'slot_end';
						$value = intval( $value );
						break;
					case 'date':
					case 'slot':
					case 'provider':
					case 'service':
						$value = intval( $value );
						break;
					
					default:
						$value = NULL;
						unset( $appointment[ $field ] );
						break;
				}
				
				if( NULL !== $value ){
					$appointment[ $field ] = $value;
				}
			}
			
			if ( ! empty( $db_columns ) ) {
				foreach ( $db_columns as $column ) {
					$custom_field  = 'appointment_custom_field_' . $column;
					$field_name    = ! empty( $args[ $custom_field ] ) ? $args[ $custom_field ] : false;
					
					$appointment[ $column ] = ! empty( $data[ $field_name ] ) ? esc_attr( $data[ $field_name ] ) : '';
				}
			}

			if ( Plugin::instance()->wc->get_status() && Plugin::instance()->wc->get_product_id() ) {
				$appointment['status'] = 'on-hold';
			} elseif ( $this->hasGateway() ) {
				$appointment['status']   = 'on-hold';
				
				if( ! empty( $this->getRequest( 'inserted_post_id' ) ) ) {
					$appointment['order_id'] = $this->getRequest( 'inserted_post_id' );
				}
			}
			
			$appointment[ 'type' ]       = 'slot';
			$appointment[ 'user_email' ] = $email;
			$appointment[ 'group_ID' ]   = $group_ID;

			if ( ! Plugin::instance()->db->appointment_available( $appointment ) ) {
				$notification_log = false;
				continue;
			}
			
			$appointment_id = Plugin::instance()->db->add_appointment( $appointment );
			
			$appointment_id_list[]            = $appointment_id;
			$appointment['ID']                = $appointment_id;
			$appointment['price']             = $price;
			$appointment[ 'human_read_date' ] = sprintf(
				'%1$s, %2$s - %3$s',
				date_i18n( get_option( 'date_format' ), $appointment['date'] ),
				date_i18n( $format, $appointment['slot'] ),
				date_i18n( $format, $appointment['slot_end'] )
			);
			
			$appointments[ $key ] = $appointment;
			
			if( ! $parent_appointment ) {
				$parent_appointment = $appointment;
			}
		
			$notification_log = true;
		}
		
		if ( ! $notification_log ) {
			throw new Base_Handler_Exception( 'Appointment time already taken', 'error' );
		}

		$this->setRequest( 'appointment_id', $parent_appointment[ 'ID' ] );
		$this->setRequest( 'appointment_id_list', $appointment_id_list );

		return $appointments;
	}

	/**
	 * The function.
	 */
	public function parse_webhook_args( $args, $notification, $instance_notification ) {
		$appointment_field_key = $this->getFieldNameByType( 'appointment_date' );

		if( ! $appointment_field_key || empty( $args["body"][ $appointment_field_key ] ) ){
			return $args;
		}

		$multi_booking = Plugin::instance()->settings->get( 'multi_booking' );
		$appointments = json_decode( stripcslashes( $args["body"][ $appointment_field_key ] ) );

		foreach ( $appointments as $key => $appointment ) {
			$appointment->serviceTitle = get_the_title( $appointment->service );
			$appointment->providerTitle = get_the_title( $appointment->provider );

			$appointments[ $key ] = $appointment;
		}

		$first_appointment = $appointments[ 0 ];

		$args["body"][ $appointment_field_key . '_new' ] = $first_appointment;

		if( $multi_booking ){
			$args["body"][ $appointment_field_key . '_list' ] = $appointments;
		}

		$args["body"][ $appointment_field_key ] =  $first_appointment->date . '|' . $first_appointment->slot . '|' . $first_appointment->slotEnd ;

		return $args;
	}

}