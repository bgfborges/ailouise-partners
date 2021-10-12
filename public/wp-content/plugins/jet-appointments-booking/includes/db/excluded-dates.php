<?php
namespace JET_APB\DB;

/**
 * Database manager class
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Define DB class
 */
class Excluded_Dates extends Base {

	/**
	 * Returns table name
	 * @return [type] [description]
	 */
	public function table() {
		return $this->wpdb()->prefix . 'jet_appointments_excluded';
	}

	/**
	 * Returns columns schema
	 * @return [type] [description]
	 */
	public function schema() {
		return array(
			'ID'       => 'bigint(20) NOT NULL AUTO_INCREMENT',
			'service'  => 'text',
			'provider' => 'text',
			'date'     => 'bigint(20) NOT NULL',
		);
	}

	/**
	 * Create DB table for apartment units
	 *
	 * @return [type] [description]
	 */
	public function get_table_schema() {

		$charset_collate = $this->wpdb()->get_charset_collate();
		$table           = $this->table();
		$default_columns = $this->schema();
		$columns_schema  = '';

		foreach ( $default_columns as $column => $desc ) {
			$columns_schema .= $column . ' ' . $desc . ',';
		}

		return "CREATE TABLE $table (
			$columns_schema
			PRIMARY KEY (ID)
		) $charset_collate;";

	}

}
