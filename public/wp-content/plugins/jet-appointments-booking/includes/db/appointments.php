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
class Appointments extends Base {

	public $defaults = array(
		'status' => 'pending',
	);

	/**
	 * Additinal DB columns list
	 *
	 * @var array
	 */
	public $additional_db_columns = array();

	/**
	 * Returns table name
	 * @return [type] [description]
	 */
	public function table() {
		return $this->wpdb()->prefix . 'jet_appointments';
	}

	/**
	 * Returns additional DB columns
	 * @return [type] [description]
	 */
	public function get_additional_db_columns() {
		return $this->additional_db_columns;
	}

	/**
	 * Returns currently queried appointment ID
	 *
	 * @return [type] [description]
	 */
	public function get_queried_item_id() {

		$object = jet_engine()->listings->data->get_current_object();

		if ( is_object( $object ) ) {

			if ( isset( $object->post_type ) && 'jet_apb_list' === $object->post_type ) {
				return $object->ID;
			} else {
				return false;
			}

		} elseif ( is_array( $object ) ) {
			return isset( $object['ID'] ) ? $object['ID'] : false;
		} else {
			return false;
		}
	}

	/**
	 * Add new DB column
	 *
	 * @param [type] $column [description]
	 */
	public function add_column( $column ) {
		$this->additional_db_columns[] = $column;
	}

	/**
	 * Returns columns schema
	 * @return [type] [description]
	 */
	public function schema() {
		return array(
			'ID'         => 'bigint(20) NOT NULL AUTO_INCREMENT',
			'group_ID'   => 'bigint(20)',
			'status'     => 'text',
			'service'    => 'text',
			'provider'   => 'text',
			'order_id'   => 'bigint(20)',
			'user_id'    => 'bigint(20)',
			'user_name'  => 'text NOT NULL',
			'user_email' => 'text',
			'date'       => 'bigint(20) NOT NULL',
			'date_end'   => 'bigint(20) NOT NULL',
			'slot'       => 'bigint(20) NOT NULL',
			'slot_end'   => 'bigint(20) NOT NULL',
			'type'       => 'text',
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

		$default_columns    = $this->schema();
		$additional_columns = $this->get_additional_db_columns();
		$columns_schema     = '';

		foreach ( $default_columns as $column => $desc ) {
			$columns_schema .= $column . ' ' . $desc . ',';
		}

		if ( is_array( $additional_columns ) && ! empty( $additional_columns ) ) {
			foreach ( $additional_columns as $column ) {
				$columns_schema .= $column . ' text,';
			}
		}

		return "CREATE TABLE $table (
			$columns_schema
			PRIMARY KEY (ID)
		) $charset_collate;";

	}

	/**
	 * Query appointments with capacity counted
	 *
	 * @return [type] [description]
	 */
	public function query_with_capacity( $args = array() ) {

		$table = $this->table();

		$query = "SELECT service, provider, date, slot, slot_end, COUNT( slot ) AS slot_count FROM $table";
		$rel   = 'AND';

		if ( isset( $args['after'] ) ) {
			$after = $args['after'];
			unset( $args['after'] );
			$args['ID>'] = $after;
		}

		if ( isset( $args['before'] ) ) {
			$before = $args['before'];
			unset( $args['before'] );
			$args['ID<'] = $before;
		}

		$query .= $this->add_where_args( $args, $rel );
		$query .= " GROUP BY `slot`;";

		$raw = $this->wpdb()->get_results( $query, ARRAY_A );

		return $raw;

	}

}