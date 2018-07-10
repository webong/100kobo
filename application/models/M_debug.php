<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_debug extends CI_Model {

	public function dump($garbage)
	{
		$data['vardump'] = $garbage;

		$query = $this->db->insert('test', $data);

		if ($query) {
			return true;
		} else {
			return false;
		}
	}
}