<?php
class SmpModel extends CI_Model
{
	public function getSiswa($slug_nama_lengkap)
	{
		$array = array('slug_nama_lengkap' => $slug_nama_lengkap, 'deleted_at' => null);
		return $this->db->where($array)->get('smp');
	}

	public function getDapodik($smp_id)
	{
		$array = array('smp_id' => $smp_id, 'deleted_at' => null);
		return $this->db->where($array)->get('smp_dapodik');
	}

	public function getPernyataan($smp_id)
	{
		$array = array('smp_id' => $smp_id, 'deleted_at' => null);
		return $this->db->where($array)->get('smp_pernyataan');
	}

	public function getBeasiswa($smp_id)
	{
		$array = array('smp_id' => $smp_id, 'deleted_at' => null);
		return $this->db->where($array)->get('smp_beasiswa');
	}

	public function getKeuangan($smp_id)
	{
		$array = array('smp_id' => $smp_id, 'deleted_at' => null);
		return $this->db->where($array)->get('smp_keuangan');
	}
}
