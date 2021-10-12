<?php

	namespace JET_APB\Admin\Pages;

	use JET_APB\Admin\Helpers\Page_Config;
	use JET_APB\Plugin;

	/**
	 * Base dashboard page
	 */
	class Appointments extends Base {

		/**
		 * Page slug
		 *
		 * @return string
		 */
		public function slug () {
			return 'jet-apb-appointments';
		}

		/**
		 * Page title
		 *
		 * @return string
		 */
		public function title () {
			return esc_html__( 'Appointments', 'jet-appointments-booking' );
		}

		/**
		 * Return  page config object
		 *
		 * @return [type] [description]
		 */
		public function page_config () {
			$columns = Plugin::instance()->db->appointments->get_column_list();
			$providers = Plugin::instance()->tools->get_posts( 'providers', [
				'post_status'    => 'any',
				'posts_per_page' => -1
			] );
			
			if( ! $providers ){
				$key = array_search( 'provider', $columns );
				unset( $columns[$key] );
			}

			$services = Plugin::instance()->tools->get_posts( 'services', [
					'post_status'    => 'any',
					'posts_per_page' => -1
			] );
			
			if( ! $services ){
				$key = array_search( 'service', $columns );
				unset( $columns[$key] );
			}
			
			$multi_booking = Plugin::instance()->settings->get( 'multi_booking' );
			$multi_booking_settings = $multi_booking ? [
				'multi_booking' => $multi_booking,
				'min' => Plugin::instance()->settings->get( 'min_slot_count' ),
				'max' => Plugin::instance()->settings->get( 'max_slot_count' ),
			] : false ;

			return new Page_Config( $this->slug(), array(
				'api'             => Plugin::instance()->rest_api->get_urls( false ),
				'edit_link'       => add_query_arg( array(
					'post'   => '%id%',
					'action' => 'edit',
				), admin_url( 'post.php' ) ),
				'columns'         => array_values( $columns ),
				'statuses_schema' => Plugin::instance()->statuses->get_schema(),
				'statuses_list'   => Plugin::instance()->statuses->get_statuses(),
				'multi_booking_settings' => $multi_booking_settings,
                'items_sequence'  => [
                    'ID',
                    'order_id',
	                'group_ID',
	                'status',
                    'service',
                    'provider',
                    'date',
                    'date_end',
                    'slot',
                    'slot_end',
                    'user_id',
                    'user_name',
                    'user_email',
                    'phone',
                    'comments',
                ],
				'config'          => [
					'groupView'        => $multi_booking ? true : false,
					'filters'          => [],
					'labels'           => [
						'ID'         => esc_html__( 'ID', 'jet-appointments-booking' ),
						'user_id'    => esc_html__( 'User ID', 'jet-appointments-booking' ),
						'group_ID'   => esc_html__( 'Parent ID', 'jet-appointments-booking' ),
						'user_email' => esc_html__( 'User e-mail', 'jet-appointments-booking' ),
						'user_name'  => esc_html__( 'User Name', 'jet-appointments-booking' ),
						'provider'   => esc_html__( 'Provider', 'jet-appointments-booking' ),
						'service'    => esc_html__( 'Service', 'jet-appointments-booking' ),
						'date'       => esc_html__( 'Date', 'jet-appointments-booking' ),
						'date_end'   => esc_html__( 'End Date', 'jet-appointments-booking' ),
						'slot'       => esc_html__( 'Start Time', 'jet-appointments-booking' ),
						'slot_end'   => esc_html__( 'End Time', 'jet-appointments-booking' ),
						'status'     => esc_html__( 'Status', 'jet-appointments-booking' ),
						'order_id'   => esc_html__( 'Related Order', 'jet-appointments-booking' ),
						'phone'      => esc_html__( 'Phone', 'jet-appointments-booking' ),
						'comments'   => esc_html__( 'Comments', 'jet-appointments-booking' ),
						'actions'    => esc_html__( 'Actions', 'jet-appointments-booking' ),
						'appointments_list' => esc_html__( 'Appointment List', 'jet-appointments-booking' ),
					],
					'columnsVisibility' => [
						'ID',
						'service',
						'provider',
						'user_name',
						'user_email',
						'user_id',
						'date',
						'slot',
						'slot_end',
						'status',
						'order_id',
						'actions',
					]
				],
				'filters'         => [
					'service'  => [
						'type'       => 'select',
						'label'      => esc_html__( 'Service', 'jet-appointments-booking' ),
						'value'      => $services,
						'visibility' => true,
					],
					'provider' => [
						'type'       => 'select',
						'label'      => esc_html__( 'Provider', 'jet-appointments-booking' ),
						'value'      => $providers,
						'visibility' => true,
					],
					'status'   => [
						'type'       => 'select',
						'label'      => esc_html__( 'Status', 'jet-appointments-booking' ),
						'value'      => Plugin::instance()->statuses->get_statuses(),
						'visibility' => true,
					],
					'date'     => [
						'type'         => 'date-picker',
						'label'        => esc_html__( 'Date', 'jet-appointments-booking' ),
						'label_button' => esc_html__( 'Clear', 'jet-appointments-booking' ),
						'value'        => '',
						'visibility'   => true,
					],
					'search'   => [
						'type'         => 'search',
						'label'        => esc_html__( 'Search', 'jet-appointments-booking' ),
						'label_button' => esc_html__( 'Clear', 'jet-appointments-booking' ),
						'value'        => '',
						'visibility'   => true,
					],
				]
			) );
		}

		/**
		 * Page render funciton
		 *
		 * @return void
		 */
		public function render () {
			?>
			<div id="jet-apb-appointments-page"></div>
			<?php
		}

		/**
		 * Page specific assets
		 *
		 * @return [type] [description]
		 */
		public function assets () {
			$this->enqueue_script( 'momentjs', 'admin/lib/moment.min.js' );
			$this->enqueue_script( 'vuex', 'admin/lib/vuex.min.js' );
			$this->enqueue_script( 'v-calendar', 'admin/lib/v-calendar.umd.min.js' );
			$this->enqueue_script( 'vuejs-datepicker', 'admin/lib/vuejs-datepicker.min.js' );
			$this->enqueue_script( 'v-gantt-chart', 'admin/lib/v-gantt-chart.js' );

			$this->enqueue_script( $this->slug(), 'admin/appointments.js' );
			wp_set_script_translations( $this->slug(), 'jet-appointments-booking', JET_APB_PATH . 'languages' );

			$this->enqueue_style( $this->slug(), 'admin/appointments.css' );
		}

		/**
		 * Page components templates
		 *
		 * @return [type] [description]
		 */
		public function vue_templates () {
			return array(
				'add-new-appointment',
				'appointments',
				'config',
				'appointments-filter',
				'appointments-view',
				'appointments-list',
				'appointments-calendar',
				'appointments-timeline',
				'pagination',
				'popup',
			);
		}

	}