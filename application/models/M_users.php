<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_users extends CI_Model {

	public function can_login()
	{
		$this->db->where('username', $this->input->post('username'));
		$this->db->where('password', md5($this->input->post('password')));
		$this->db->where('confirmed', 1);

		$query = $this->db->get('users');

		if ($query->num_rows() == 1) {
			return true;
		} else {
			return false;
		}
	}

	public function is_banned()
	{
		$this->db->where('username', $this->input->post('username'));
		$this->db->where('role', 0);

		$query = $this->db->get('users');

		if ($query->num_rows() == 1) {
			return true;
		} else {
			return false;
		}
	}

	public function add_temp_user($key)
	{
		$data = array(
			'username'		=> $this->input->post('username'),
			'password'		=> md5($this->input->post('password')),
			'email'			=> $this->input->post('email'),
			'confirm_code'	=> $key
		);

		$query = $this->db->insert('users', $data);

		if ($query) {
			return true;
		} else {
			return false;
		}
	}

	public function is_key_valid($key)
	{
		$this->db->where('confirm_code', $key);
		$this->db->where('confirmed', 0);

		$query = $this->db->get('users');

		if ($query->num_rows() == 1) {
			return true;
		} else {
			return false;
		}
	}

	public function confirm_user($key)
	{
		$data = array('confirmed' => 1);

		$this->db->where('confirm_code', $key);

		$query = $this->db->update('users', $data);

		if ($query) {
			return true;
		} else {
			return false;
		}
	}

	public function resend_password()
	{
		$data = array(
			'username'	=> $this->input->post('username'),
			'email'		=> $this->input->post('email')
		);

		$this->db->where('username', $this->input->post('username'));
		$this->db->where('email', $this->input->post('email'));

		$query = $this->db->get('users');

		if ($query->num_rows() == 1) {
			$user = $query->row();
			$id = $user->id;

			// Generate new password
			$this->load->helper('string');
			$password = random_string('alnum', 10);

			$this->db->where('id', $id);
			$this->db->set('password', md5($password));

			if ($this->db->update('users')) {
				return $password;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	public function get_user_info($id)
	{
		$this->db->where('id', $id);

		$query = $this->db->get('users');

		if ($query->num_rows() == 1) {
			return $query->row();
		} else {
			return false;
		}
	}

	public function get_user_id($username)
	{
		$this->db->where('username', $username);

		$query = $this->db->get('users');

		if ($query->num_rows() == 1) {
			return $query->row()->id;
		} else {
			return false;
		}
	}

	public function get_user_access($username)
	{
		$this->db->where('username', $username);

		$query = $this->db->get('users');

		if ($query->num_rows() == 1) {
			return $query->row()->role;
		} else {
			return false;
		}
	}

	public function get_user_by_key($key)
	{
		$this->db->where('confirm_code', $key);

		$query = $this->db->get('users');

		if ($query->num_rows() == 1) {
			return $query->row();
		} else {
			return false;
		}
	}

	public function can_change_password()
	{
		$this->db->where('password', md5($this->input->post('current')));
		$this->db->where('username', $this->session->all_userdata()['username']);

		$query = $this->db->get('users');

		if ($query->num_rows() == 1) {
			return true;
		} else {
			return false;
		}
	}

	public function update_password()
	{
		$data = array('password' => md5($this->input->post('new')));
		$this->db->where('username', $this->session->all_userdata()['username']);

		$query = $this->db->update('users', $data);

		if ($query) {
			return true;
		} else {
			return false;
		}
	}

	public function update_about($about, $image)
	{
		$data = array('about' => $about);

		if (isset($image)) {
			$data['image'] = $image;
		} else {
			$data['image'] = '';
		}

		$this->db->where('username', $this->session->all_userdata()['username']);

		$query = $this->db->update('users', $data);

		if ($query) {
			return true;
		} else {
			return false;
		}
	}

	public function update_account($first_name, $surname, $phone)
	{
		$data = array(
			'first_name'	=> $first_name,
			'surname'		=> $surname,
			'phone'			=> $phone
		);

		$this->db->where('username', $this->session->all_userdata()['username']);

		$query = $this->db->update('users', $data);

		if ($query) {
			return true;
		} else {
			return false;
		}
	}

	public function update_bank($account_name, $bank_name, $account_number, $sort_code)
	{
		$data = array(
			'account_name'		=> $account_name,
			'bank_name'			=> $bank_name,
			'account_number'	=> $account_number,
			'sort_code'			=> $sort_code
		);

		$this->db->where('id', $this->session->all_userdata()['user_id']);

		$query = $this->db->update('users', $data);

		if ($query) {
			return true;
		} else {
			return false;
		}
	}

	public function not_updated($username)
	{
		$this->db->where('username', $username);

		$query = $this->db->get('users');

		if ($query->num_rows() == 1) {
			if ($query->row()->first_name == '' &&
				$query->row()->surname == '' &&
				$query->row()->phone == '')
			{
				return true;
			} else {
				return false;
			}
		} else {
			return true;
		}
	}

	public function mark_as_paid($user_id)
	{
		$data = array(
			'role' => 2
		);

		$this->db->where('id', $user_id);
		
		$query = $this->db->update('users', $data);

		if ($query) {
			// Check if user is not last in PAID table
			$break = false;

			$this->db->distinct('user_id');
			$this->db->order_by('id', 'desc');
			$this->db->limit(1);

			$query = $this->db->get('paid');

			if ($query->num_rows() == 1) {
				if ($query->row()->user_id == $user_id)
					$break = true;
			}

			// Add to paid list
			if (!$break) {
				$data = array(
					'user_id' => $user_id
				);
			
				$query = $this->db->insert('paid', $data);
			}
			
			return true;

		} else {
			return false;
		}
	}

	public function get_all_users($role)
	{
		$this->db->order_by('id', 'asc');

		if ($role > -1) {
			$this->db->where('role', $role);
		}

		$query = $this->db->get('users');

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}
	}

	public function admin_add($username)
	{
		$data = array('role' => 3);

		$this->db->where('username', $username);

		$query = $this->db->update('users', $data);

		if ($query) {
			return true;
		} else {
			return false;
		}
	}

	public function admin_remove($username)
	{
		$data = array('role' => 1);

		$this->db->where('username', $username);

		$query = $this->db->update('users', $data);

		if ($query) {
			return true;
		} else {
			return false;
		}
	}

	public function user_ban($username)
	{
		$data = array('role' => 0);

		$this->db->where('username', $username);

		$query = $this->db->update('users', $data);

		if ($query) {
			return true;
		} else {
			return false;
		}
	}

	public function update_profile_image($image)
	{
		$data['image'] = $image;

		$this->db->where('username', $this->session->all_userdata()['username']);

		$query = $this->db->update('users', $data);

		if ($query) {
			return true;
		} else {
			return false;
		}
	}

	public function get_1000th_paid()
	{
		$this->db->order_by('id', 'desc');
		$this->db->offset(999);
		$this->db->limit(1);

		$query = $this->db->get('paid');

		if ($query->num_rows() == 1) {
			return $query->row();
		} else {
			return false;
		}
	}

	public function get_100th_paid()
	{
		$this->db->order_by('id', 'desc');
		$this->db->offset(99);
		$this->db->limit(1);

		$query = $this->db->get('paid');

		if ($query->num_rows() == 1) {
			return $query->row();
		} else {
			return false;
		}
	}

	public function get_100th_paid_mlm()
	{
		$query = $this->db->query('SELECT TRUNCATE(COUNT(DISTINCT user_id) / 5 + 1, 0) AS count_users FROM paid');

		if ($query->num_rows() == 1) {

			$count_users = $query->result()[0]->count_users;
			$query2 = $this->db->query('SELECT TRUNCATE((COUNT(DISTINCT user_id) - 1) / 5 + 1, 0) AS count_users FROM paid');
			
			if ($query2->num_rows() == 1) {
				
				$count_users_before = $query2->result()[0]->count_users;

				if ($count_users > $count_users_before) {

					$count_users_before--;
					$query3 = $this->db->query('SELECT DISTINCT user_id FROM paid GROUP BY id ORDER BY id ASC LIMIT 1 OFFSET ' . $count_users_before);

					if ($query3->num_rows() == 1) {
						return $query3->result()[0];
					}
				}
			}
		}

		return false;
	}

	public function get_100th_paid_mlm_cur()
	{
		$query = $this->db->query('SELECT TRUNCATE(COUNT(DISTINCT user_id) / 5 + 1, 0) AS count_users FROM paid');

		if ($query->num_rows() == 1) {
			
			$count_users = $query->result()[0]->count_users;

			if ($count_users > 0) {
				
				$count_users--;
				$query2 = $this->db->query('SELECT DISTINCT user_id FROM paid GROUP BY id ORDER BY id ASC LIMIT 1 OFFSET ' . $count_users);

				if ($query2->num_rows() == 1) {
					return $query2->result()[0];
				}
			}
			
		}

		return false;
	}

	public function user_was_paid($user_id)
	{
		$query = $this->db->query('SELECT COUNT(id) AS count_users FROM paid WHERE user_id = ' . $user_id);

		if ($query->num_rows() == 1) {
			if ($query->result()[0]->count_users == 0) {
				return false;
			}
		}

		return true;
	}

	public function mlm_N5000()
	{
		$count_paid = $this->count_paid();

		if ($count_paid % 5 === 0) {
			$offset = floor($count_paid / 5) - 1;

			$query = $this->db->query('SELECT user_id FROM paid GROUP BY id ORDER BY id ASC LIMIT 1 OFFSET ' . $offset);

			if ($query->num_rows() == 1) {
				return (int)$query->result()[0]->user_id;
			}
		}

		return false;
	}

	public function mlm_N50()
	{
		$count_paid = $this->count_paid();

		$offset = floor($count_paid / 5);

		$query = $this->db->query('SELECT user_id FROM paid GROUP BY id ORDER BY id ASC LIMIT 1 OFFSET ' . $offset);

		if ($query->num_rows() == 1) {
			return (int)$query->result()[0]->user_id;
		}

		return false;
	}

	public function count_paid()
	{
		$query = $this->db->query('SELECT COUNT(DISTINCT(user_id)) AS count FROM paid');

		if ($query->num_rows() == 1) {
			return (int)$query->result()[0]->count;
		}

		return false;
	}
}