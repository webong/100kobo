<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_wallet extends CI_Model {

	public function create_wallets($user_id)
	{
		$data = array(
			'user_id'	=> $user_id,
	   		'amount'	=> 0
		);

		// Create free wallet for user
		$query = $this->db->insert('wallet_free', $data);

		if ($query) {
			// Create wallet for user
			$data['amount'] = 0;

			$query = $this->db->insert('wallet', $data);

			if ($query) {
				// Create topup wallet for user
				$data['amount'] = 0;

				$query = $this->db->insert('wallet_topup', $data);

				if ($query) {
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	public function calculate_wallet($user_id, $wallet)
	{
		$this->db->where('user_id', $user_id);

		$query = $this->db->get($wallet);

		if ($query->num_rows() > 0) {
			return $query->row()->amount;
		} else {
			return false;
		}
	}

	/*
	* @type:
	* 		1 - Sign Up
	*		2 - Comment
	*		3 - Gallery Unlocked
	*		4 - Top-up
	*		5 - Withdraw
	*		6 - Voucher for cash
	*		7 - N1,000 award
	*		8 - 5 mins active
	*		9 - Paid
	*/
	public function add_amount($user_from, $user_to, $amount = 0, $wallet = 'wallet', $type = 0)
	{
		$inc = 'amount+'.$amount;
		$this->db->set('amount', $inc, FALSE);

		$this->db->where('user_id', $user_to);

		$query = $this->db->update($wallet);

		if ($query) {
			// Add a record to wallet_history to track transactions

			$data = array(
				'user_from'	=> $user_from,
				'user_to'	=> $user_to,
		   		'amount'	=> $amount,
		   		'type'		=> $type
			);

			$this->db->insert('wallet_history', $data);

			return true;
		} else {
			return false;
		}
	}

	public function deduct_amount($user_id, $amount, $wallet)
	{
		$dec = 'amount-'.$amount;
		$this->db->set('amount', $dec, FALSE);

		$this->db->where('user_id', $user_id);

		$query = $this->db->update($wallet);

		if ($query) {
			return true;
		} else {
			return false;
		}
	}

	public function check_if_user_has_amount($user_id, $amount, $wallet)
	{
		$this->db->where('user_id', $user_id);
		$this->db->where('amount >=', $amount);

		$query = $this->db->get($wallet);

		if ($query->num_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function history($user_id)
	{
		$where = "user_from <> user_to AND (user_from='".$user_id."' OR user_to='".$user_id."')";
		$this->db->where($where);

		$this->db->order_by('id', 'desc');
		$this->db->limit(50);

		$query = $this->db->get('wallet_history');

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}
	}
}