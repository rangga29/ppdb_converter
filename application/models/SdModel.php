<?php
class SdModel extends CI_Model
{
	public function getSiswa($slug_nama_lengkap)
	{
		$array = array('slug_nama_lengkap' => $slug_nama_lengkap, 'deleted_at' => null);
		return $this->db->where($array)->get('sd');
	}

	public function getDapodik($sd_id)
	{
		$array = array('sd_id' => $sd_id, 'deleted_at' => null);
		return $this->db->where($array)->get('sd_dapodik');
	}

	public function getPernyataan($sd_id)
	{
		$array = array('sd_id' => $sd_id, 'deleted_at' => null);
		return $this->db->where($array)->get('sd_pernyataan');
	}

	public function getBeasiswa($sd_id)
	{
		$array = array('sd_id' => $sd_id, 'deleted_at' => null);
		return $this->db->where($array)->get('sd_beasiswa');
	}

	public function getKeuangan($sd_id)
	{
		$array = array('sd_id' => $sd_id, 'deleted_at' => null);
		return $this->db->where($array)->get('sd_keuangan');
	}
}
