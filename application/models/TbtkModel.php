<?php
class TbtkModel extends CI_Model
{
	public function getSiswa($slug_nama_lengkap)
	{
		$array = array('slug_nama_lengkap' => $slug_nama_lengkap, 'deleted_at' => null);
		return $this->db->where($array)->get('tbtk');
	}

	public function getDapodik($tbtk_id)
	{
		$array = array('tbtk_id' => $tbtk_id, 'deleted_at' => null);
		return $this->db->where($array)->get('tbtk_dapodik');
	}

	public function getPernyataan($tbtk_id)
	{
		$array = array('tbtk_id' => $tbtk_id, 'deleted_at' => null);
		return $this->db->where($array)->get('tbtk_pernyataan');
	}

	public function getBeasiswa($tbtk_id)
	{
		$array = array('tbtk_id' => $tbtk_id, 'deleted_at' => null);
		return $this->db->where($array)->get('tbtk_beasiswa');
	}

	public function getKeuangan($tbtk_id)
	{
		$array = array('tbtk_id' => $tbtk_id, 'deleted_at' => null);
		return $this->db->where($array)->get('tbtk_keuangan');
	}
}
