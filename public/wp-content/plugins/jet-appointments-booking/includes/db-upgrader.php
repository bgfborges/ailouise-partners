<?php
namespace JET_APB;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'DB_Upgrader' ) ) {

	/**
	 * Define DB_Upgrader class
	 */
	class DB_Upgrader {

		/**
		 * Constructor for the class
		 */
		public function __construct() {

			/**
			 * Plugin initialized on new DB_Upgrader call.
			 * Please ensure, that it called only on admin context
			 */
			$db_updater_data = jet_engine()->framework->get_included_module_data( 'cherry-x-db-updater.php' );

			new \CX_DB_Updater(
				array(
					'path'      => $db_updater_data['path'],
					'url'       => $db_updater_data['url'],
					'slug'      => 'jet-appointments-booking',
					'version'   => '1.5.0',
					'callbacks' => array(
						'1.5.0' => array(
							array( $this, 'update_db_1_5_0' ),
						),
					),
					'labels'    => array(
						'start_update' => esc_html__( 'Start Update', 'jet-appointments-booking' ),
						'data_update'  => esc_html__( 'Data Update', 'jet-appointments-booking' ),
						'messages'     => array(
							'error'   => esc_html__( 'Module DB Updater init error in %s - version and slug is required arguments', 'jet-appointments-booking' ),
							'update'  => esc_html__( 'We need to update your database to the latest version.', 'jet-appointments-booking' ),
							'updated' => esc_html__( 'DB Update complete, thank you for updating to the latest version!', 'jet-appointments-booking' ),
						),
					),
				)
			);
		}

		/**
		 * Update db updater 1.5.0
		 *
		 * @return void
		 */
		public function update_db_1_5_0() {
			$db_manager = Plugin::instance()->db->appointments;
			
			if (!$db_manager->is_table_exists()) {
				return;
			}
			
			$curent_column_list = $db_manager->get_column_list();
			$table_schema   =  $db_manager->schema();
			$table          = $db_manager->table();
			$columns_schema = '';
			
			foreach ( $table_schema as $new_column => $column_type ){
				if( in_array( $new_column, $curent_column_list) ){
					continue;
				}
				
				$columns_schema .= " ADD COLUMN $new_column $column_type,";
			}
			
			$columns_schema = rtrim( $columns_schema, ',' );
			
			if( ! $columns_schema){
				return;
			}
			
			$sql = "ALTER TABLE $table $columns_schema;";
			
			$db_manager->wpdb()->query( $sql );
		}
	}

}