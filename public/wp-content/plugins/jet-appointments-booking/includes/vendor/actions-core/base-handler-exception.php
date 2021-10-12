<?php


namespace JET_APB\Vendor\Actions_Core;


class Base_Handler_Exception extends \Exception {

	private $additional_data;
	public $type;

	public function __construct( $message = "", $dynamicType = "", ...$additional_data ) {
		parent::__construct( $message, 0, null );

		$this->type            = $dynamicType;
		$this->additional_data = $additional_data;
	}

	public function getAdditional() {
		return $this->additional_data;
	}

}