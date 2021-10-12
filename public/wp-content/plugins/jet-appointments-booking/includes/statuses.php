<?php
namespace JET_APB;

/**
 * Statuses class
 */
class Statuses {

	private $statuses = array();

	/**
	 * Install statuses
	 */
	public function __construct() {
		$this->statuses = array(
			'pending'    => _x( 'Pending payment', 'Order status', 'jet-appointments-booking' ),
			'processing' => _x( 'Processing', 'Order status', 'jet-appointments-booking' ),
			'on-hold'    => _x( 'On hold', 'Order status', 'jet-appointments-booking' ),
			'completed'  => _x( 'Completed', 'Order status', 'jet-appointments-booking' ),
			'cancelled'  => _x( 'Cancelled', 'Order status', 'jet-appointments-booking' ),
			'refunded'   => _x( 'Refunded', 'Order status', 'jet-appointments-booking' ),
			'failed'     => _x( 'Failed', 'Order status', 'jet-appointments-booking' ),
		);
	}

	/**
	 * Returns all registered statuses
	 *
	 * @return [type] [description]
	 */
	public function get_all() {
		return $this->statuses;
	}

	public function get_schema() {
		return array(
			'valid'       => $this->valid_statuses(),
			'in_progress' => $this->in_progress_statuses(),
			'finished'    => $this->finished_statuses(),
			'invalid'     => $this->invalid_statuses(),
		);
	}

	/**
	 * Returns valid statuses
	 * If appointment has this status - appontment slot is set as not-allowed
	 *
	 * @return [type] [description]
	 */
	public function valid_statuses() {
		return array(
			'pending',
			'processing',
			'completed',
		);
	}

	/**
	 * Returns valid but not finalized statuses
	 *
	 * @return [type] [description]
	 */
	public function in_progress_statuses() {
		return array(
			'pending',
			'processing',
		);
	}

	/**
	 * Returns valid and finished statuses
	 * @return [type] [description]
	 */
	public function finished_statuses() {
		return array_values( array_diff( $this->valid_statuses(), $this->in_progress_statuses() ) );
	}

	/**
	 * Returns invalid statuses
	 * If appointment has this status - appontment slot is set as not-allowed
	 *
	 * @return [type] [description]
	 */
	public function invalid_statuses() {
		return array(
			'on-hold',
			'cancelled',
			'refunded',
			'failed',
		);
	}

	/**
	 * Get all statuses
	 * @return [type] [description]
	 */
	public function get_statuses() {
		return $this->statuses;
	}

	/**
	 * Get all statuses
	 * @return [type] [description]
	 */
	public function get_statuses_ids() {
		return array_keys( $this->statuses );
	}

}