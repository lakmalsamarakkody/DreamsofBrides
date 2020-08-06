<?php

class CommonNavbar extends Controller {
	public function index($data) {
		return $this->load_view('common/navbar', $data);
	}
}

?>