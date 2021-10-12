<?php


namespace JET_APB\Form_Fields;


trait Static_Calendar_Trait {

	public function render_static_calendar() {
		ob_start();
		include  JET_APB_PATH . 'templates/public/static-calendar.php';
		return ob_get_clean();
	}

}