<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_reports extends CI_Model {

	public function report_user($user_report, $user_reported, $message)
	{
		$data = array(
			'user_report'	=> $user_report,
			'user_reported'	=> $user_reported,
			'message'		=> $message
		);

		$query = $this->db->insert('reports', $data);

		if ($query) {
			return true;
		} else {
			return false;
		}
	}

	public function get_all($solved = -1)
	{
		$this->db->order_by('added', 'desc');

		if (isset($solved) && $solved >= 0) {
			$this->db->where('solved', $solved);
		}
		
		$query = $this->db->get('reports');

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}
	}

	public function mark_as_resolved($id)
	{
		$data = array(
			'solved' => 1
		);

		$this->db->where('id', $id);
		
		$query = $this->db->update('reports', $data);

		if ($query) {
			return true;
		} else {
			return false;
		}
	}
}