<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Tbtk extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('TbtkModel');
	}

	public function pdf_pendaftaran($slug_nama_lengkap)
	{
		setlocale(LC_ALL, 'id-ID', 'id_ID');
		$tanggal_hari_ini = date('[Ymd]');
		$dataPendaftaran = $this->TbtkModel->getSiswa($slug_nama_lengkap)->row();

		$tingkat = 'tingkat';
		if ($dataPendaftaran->pilihan_tingkat === '1') {
			$tingkat = 'TB';
		} elseif ($dataPendaftaran->pilihan_tingkat === '2') {
			$tingkat = 'TK A';
		} else {
			$tingkat = 'TK B';
		}

		$bukti_pembayaran = 'http://localhost:8080/upload/bukti_pembayaran/tbtk/'.$dataPendaftaran->bukti_pembayaran.'';

		$pdf = new TBTKPDF('P', PDF_UNIT, 'A4', true, 'UTF-8', false);

		$pdf->SetTitle('TBTK Pendaftaran '.$dataPendaftaran->no_registrasi.' '.$tanggal_hari_ini);
		$pdf->SetSubject('TBTK Pendaftaran '.$dataPendaftaran->no_registrasi.' '.$tanggal_hari_ini);

		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		$pdf->AddPage();
		$html = '<br><br><br><br>
		<h2>DATA PENDAFTARAN</h2><br>
		<table border="0" cellspacing="3" cellpadding="4">
			<tr>
				<td><b>Tanggal Pendaftaran</b></td>
				<td colspan="2">: '.strftime("%d %B %Y",strtotime($dataPendaftaran->created_at)).'</td>
			</tr>
			<tr>
				<td><b>No. Registrasi</b></td>
				<td colspan="2">: '.$dataPendaftaran->no_registrasi.'</td>
			</tr>
			<tr>
				<td><b>Nama Lengkap</b></td>
				<td colspan="2">: '.$dataPendaftaran->nama_lengkap.'</td>
			</tr>
			<tr>
				<td><b>Alamat Email</b></td>
				<td colspan="2">: '.$dataPendaftaran->email.'</td>
			</tr>
			<tr>
				<td><b>Tempat Tanggal Lahir</b></td>
				<td colspan="2">: '.$dataPendaftaran->kota_lahir.', '.strftime("%d %B %Y",strtotime($dataPendaftaran->tanggal_lahir)).'</td>
			</tr>
			<tr>
				<td><b>Asal Sekolah</b></td>
				<td colspan="2">: '.$dataPendaftaran->asal_sekolah.'</td>
			</tr>
			<tr>
				<td><b>Tingkat Yang Dituju</b></td>
				<td colspan="2">: '.$tingkat.'</td>
			</tr>
			<tr>
				<td><b>Nama Orangtua</b></td>
				<td colspan="2">: '.$dataPendaftaran->nama_orangtua.'</td>
			</tr>
			<tr>
				<td><b>No. Whatsapp</b></td>
				<td colspan="2">: '.$dataPendaftaran->no_whatsapp.'</td>
			</tr>
			<tr>
				<td><b>Bukti Pendaftaran</b></td>
				<td colspan="2">: ';
				$pdf->Image($bukti_pembayaran, 80, 150, 60, 100, '' , '', '', 'true', 300, '', false, false, 1, false, false, false);
		$html .='
				</td>
			</tr>
		</table>';
		$pdf->lastPage();
		$pdf->writeHTML($html, true, false, true, false, '');
		
		$pdf->Output('tbtk pendaftaran '.$dataPendaftaran->no_registrasi.' '.$tanggal_hari_ini.'.pdf');
	}

	public function pdf_dapodik($slug_nama_lengkap)
	{
		setlocale(LC_ALL, 'id-ID', 'id_ID');
		$tanggal_hari_ini = date('[Ymd]');
		$dataPendaftaran = $this->TbtkModel->getSiswa($slug_nama_lengkap)->row();
		$dataDapodik = $this->TbtkModel->getDapodik($dataPendaftaran->id)->row();

		$tingkat = 'tingkat';
		if ($dataPendaftaran->pilihan_tingkat === '1') {
			$tingkat = 'TB';
		} elseif ($dataPendaftaran->pilihan_tingkat === '2') {
			$tingkat = 'TK A';
		} else {
			$tingkat = 'TK B';
		}

		$ayah_pendapatan = 'penghasilan';
		if($dataDapodik->ayah_pendapatan === 'gol1') {
			$ayah_pendapatan = 'Tidak Berpenghasilan';
		} elseif($dataDapodik->ayah_pendapatan === 'gol2') {
			$ayah_pendapatan = 'Kurang dari Rp 2.000.000';
		} elseif($dataDapodik->ayah_pendapatan === 'gol3') {
			$ayah_pendapatan = 'Rp 2.000.000 - 5.000.000';
		} elseif($dataDapodik->ayah_pendapatan === 'gol4') {
			$ayah_pendapatan = 'Rp 5.000.000 - 10.000.000';
		} elseif($dataDapodik->ayah_pendapatan === 'gol5') {
			$ayah_pendapatan = 'Lebih dari Rp 10.000.000';
		}

		$ibu_pendapatan = 'penghasilan';
		if($dataDapodik->ibu_pendapatan === 'gol1') {
			$ibu_pendapatan = 'Tidak Berpenghasilan';
		} elseif($dataDapodik->ibu_pendapatan === 'gol2') {
			$ibu_pendapatan = 'Kurang dari Rp 2.000.000';
		} elseif($dataDapodik->ibu_pendapatan === 'gol3') {
			$ibu_pendapatan = 'Rp 2.000.000 - 5.000.000';
		} elseif($dataDapodik->ibu_pendapatan === 'gol4') {
			$ibu_pendapatan = 'Rp 5.000.000 - 10.000.000';
		} elseif($dataDapodik->ibu_pendapatan === 'gol5') {
			$ibu_pendapatan = 'Lebih dari Rp 10.000.000';
		}

		$wali_pendapatan = 'penghasilan';
		if($dataDapodik->wali_pendapatan === 'gol1') {
			$wali_pendapatan = 'Tidak Berpenghasilan';
		} elseif($dataDapodik->wali_pendapatan === 'gol2') {
			$wali_pendapatan = 'Kurang dari Rp 2.000.000';
		} elseif($dataDapodik->wali_pendapatan === 'gol3') {
			$wali_pendapatan = 'Rp 2.000.000 - 5.000.000';
		} elseif($dataDapodik->wali_pendapatan === 'gol4') {
			$wali_pendapatan = 'Rp 5.000.000 - 10.000.000';
		} elseif($dataDapodik->wali_pendapatan === 'gol5') {
			$wali_pendapatan = 'Lebih dari Rp 10.000.000';
		}

		$pas_foto = 'http://localhost:8080/upload/pas_foto/tbtk/'.$dataDapodik->pas_foto.'';
		$scan_kk = 'http://localhost:8080/upload/scan_kk/tbtk/'.$dataDapodik->scan_kk.'';
		$scan_ak = 'http://localhost:8080/upload/scan_ak/tbtk/'.$dataDapodik->scan_ak.'';

		$pdf = new TBTKPDF('P', PDF_UNIT, 'A4', true, 'UTF-8', false);

		$pdf->SetTitle('TBTK Dapodik '.$dataPendaftaran->no_registrasi.' '.$tanggal_hari_ini);
		$pdf->SetSubject('TBTK Dapodik '.$dataPendaftaran->no_registrasi.' '.$tanggal_hari_ini);

		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		$pdf->setJPEGQuality(75);

		$pdf->AddPage();
		$html = '<br><br><br><br>
		<h2>DATA DAPODIK</h2><br>
		<table border="0" cellspacing="3" cellpadding="4">
			<tr>
				<td colspan="3"><b>DATA PRIBADI</b></td>
			</tr>
			<tr>
				<td><b>Tanggal Pendaftaran</b></td>
				<td colspan="2">: '.strftime("%d %B %Y",strtotime($dataPendaftaran->created_at)).'</td>
			</tr>
			<tr>
				<td><b>No. Registrasi</b></td>
				<td colspan="2">: '.$dataPendaftaran->no_registrasi.'</td>
			</tr>
			<tr>
				<td><b>Alamat Email</b></td>
				<td colspan="2">: '.$dataPendaftaran->email.'</td>
			</tr>
			<tr>
				<td><b>Tingkat Yang Dituju</b></td>
				<td colspan="2">: '.$tingkat.'</td>
			</tr>
			<tr>
				<td><b>Nama Lengkap</b></td>
				<td colspan="2">: '.$dataDapodik->nama_lengkap.'</td>
			</tr>
			<tr>
				<td><b>Nama Panggilan</b></td>
				<td colspan="2">: '.$dataDapodik->nama_panggilan.'</td>
			</tr>
			<tr>
				<td><b>Tempat Tanggal Lahir</b></td>
				<td colspan="2">: '.$dataDapodik->kota_lahir.', '.strftime("%d %B %Y",strtotime($dataDapodik->tanggal_lahir)).'</td>
			</tr>
			<tr>
				<td><b>Jenis Kelamin</b></td>
				<td colspan="2">: '.$dataDapodik->jenis_kelamin.'</td>
			</tr>
			<tr>
				<td><b>Kewarganegaraan</b></td>
				<td colspan="2">: '.$dataDapodik->kewarganegaraan.'</td>
			</tr>
			<tr>
				<td><b>Agama</b></td>
				<td colspan="2">: '.$dataDapodik->agama.'</td>
			</tr>';
		if($dataDapodik->agama === 'Katolik') : 
		$html .= '
			<tr>
				<td><b>Paroki</b></td>
				<td colspan="2">: '.$dataDapodik->paroki.'</td>
			</tr>';
		endif;
		$html .= '
			<tr>
				<td><b>Kebutuhan Khusus</b></td>
				<td colspan="2">: '.$dataDapodik->kebutuhan_khusus.'</td>
			</tr>';
		if($dataDapodik->kebutuhan_khusus === 'Ya') : 
		$html .= '
			<tr>
				<td><b>Jenis Kebutuhan Khusus</b></td>
				<td colspan="2">: '.$dataDapodik->jenis_kebutuhan_khusus.'</td>
			</tr>';
		endif;
		$html .= '
			<tr>
				<td><b>Anak Ke</b></td>
				<td colspan="2">: '.$dataDapodik->anak_keberapa.'</td>
			</tr>
			<tr>
				<td><b>Jumlah Saudara Kandung</b></td>
				<td colspan="2">: '.$dataDapodik->saudara_kandung.'</td>
			</tr>
			<tr>
				<td><b>Golongan Darah</b></td>
				<td colspan="2">: '.strtoupper($dataDapodik->gol_darah).'</td>
			</tr>
			<tr>
				<td><b>Tinggi Badan</b></td>
				<td colspan="2">: '.$dataDapodik->tinggi.' cm</td>
			</tr>
			<tr>
				<td><b>Berat Badan</b></td>
				<td colspan="2">: '.$dataDapodik->berat.' kg</td>
			</tr>
			<tr>
				<td><b>Lingkar Kepala</b></td>
				<td colspan="2">: '.$dataDapodik->kepala.' cm</td>
			</tr>
			<tr>
				<td><b>NISN</b></td>
				<td colspan="2">: '.$dataDapodik->nisn.'</td>
			</tr>
		</table>';
		$pdf->lastPage();
		$pdf->writeHTML($html, true, false, true, false, '');

		$pdf->AddPage();
		$html = '<br><br><br><br><br>
		<table border="0" cellspacing="3" cellpadding="4">
			<tr>
				<td colspan="3"><b>DATA KEPENDUDUKAN</b></td>
			</tr>
			<tr>
				<td><b>NIK</b></td>
				<td colspan="2">: '.$dataDapodik->nik.'</td>
			</tr>
			<tr>
				<td><b>Nomor Kartu Keluarga</b></td>
				<td colspan="2">: '.$dataDapodik->nak.'</td>
			</tr>
		</table>';
		$html .= '<br><br><br>
		<table border="0" cellspacing="3" cellpadding="4">
			<tr>
				<td colspan="3"><b>DATA TEMPAT TINGGAL</b></td>
			</tr>
			<tr>
				<td><b>Alamat Sesuai KK</b></td>
				<td colspan="2">: '.$dataDapodik->kk_alamat.' RT '.$dataDapodik->kk_rt.' RW '.$dataDapodik->kk_rw.', <br>  Kelurahan '.$dataDapodik->kk_kelurahan.', Kecamatan '.$dataDapodik->kk_kecamatan.', <br>  '.$dataDapodik->kk_kota.' '.$dataDapodik->kk_kodepos.'</td>
			</tr>';
		if($dataDapodik->tt_alamat != null) : 
		$html .= '
			<tr>
				<td><b>Alamat Tempat Tinggal</b></td>
				<td colspan="2">: '.$dataDapodik->tt_alamat.' RT '.$dataDapodik->tt_rt.' RW '.$dataDapodik->tt_rw.', <br>  Kelurahan '.$dataDapodik->tt_kelurahan.', Kecamatan '.$dataDapodik->tt_kecamatan.', <br>  '.$dataDapodik->tt_kota.' '.$dataDapodik->tt_kodepos.'</td>
			</tr>';
		endif;
		$html .= '
			<tr>
				<td><b>Tinggal Bersama</b></td>
				<td colspan="2">: '.$dataDapodik->tinggal_bersama.'</td>
			</tr>
			<tr>
				<td><b>Moda Transportasi</b></td>
				<td colspan="2">: '.$dataDapodik->transportasi.'</td>
			</tr>
			<tr>
				<td><b>Jarak Tempuh ke Sekolah</b></td>
				<td colspan="2">: '.$dataDapodik->jarak_tempuh.' km</td>
			</tr>
			<tr>
				<td><b>Waktu Tempuh ke Sekolah</b></td>
				<td colspan="2">: '.$dataDapodik->waktu_tempuh.' menit</td>
			</tr>
		</table>';
		$html .= '<br><br><br>
		<table border="0" cellspacing="3" cellpadding="4">
			<tr>
				<td colspan="3"><b>DATA ASAL SEKOLAH</b></td>
			</tr>
			<tr>
				<td><b>Nama Sekolah</b></td>
				<td colspan="2">: '.$dataDapodik->asal_sekolah.'</td>
			</tr>
			<tr>
				<td><b>Alamat</b></td>
				<td colspan="2">: '.$dataDapodik->asal_sekolah_alamat.'</td>
			</tr>
			<tr>
				<td><b>Kota</b></td>
				<td colspan="2">: '.$dataDapodik->asal_sekolah_kota.'</td>
			</tr>
		</table>';
		$pdf->lastPage();
		$pdf->writeHTML($html, true, false, true, false, '');

		$pdf->AddPage();
		$html = '<br><br><br><br><br>
		<table border="0" cellspacing="3" cellpadding="4">
			<tr>
				<td colspan="3"><b>DATA AYAH</b></td>
			</tr>
			<tr>
				<td><b>Nama Lengkap</b></td>
				<td colspan="2">: '.$dataDapodik->ayah_nama_lengkap.'</td>
			</tr>
			<tr>
				<td><b>NIK</b></td>
				<td colspan="2">: '.$dataDapodik->ayah_nik.'</td>
			</tr>
			<tr>
				<td><b>Tempat Tanggal Lahir</b></td>
				<td colspan="2">: '.$dataDapodik->ayah_kota_lahir.', '.strftime("%d %B %Y",strtotime($dataDapodik->ayah_tanggal_lahir)).'</td>
			</tr>
			<tr>
				<td><b>Agama</b></td>
				<td colspan="2">: '.$dataDapodik->ayah_agama.'</td>
			</tr>
			<tr>
				<td><b>Kewarganegaraan</b></td>
				<td colspan="2">: '.$dataDapodik->ayah_kewarganegaraan.'</td>
			</tr>
			<tr>
				<td><b>Pendidikan Terakhir</b></td>
				<td colspan="2">: '.$dataDapodik->ayah_pendidikan.'</td>
			</tr>
			<tr>
				<td><b>Pekerjaan</b></td>
				<td colspan="2">: '.$dataDapodik->ayah_pekerjaan.'</td>
			</tr>
			<tr>
				<td><b>Jabatan</b></td>
				<td colspan="2">: '.$dataDapodik->ayah_jabatan.'</td>
			</tr>
			<tr>
				<td><b>Penghasilan</b></td>
				<td colspan="2">: '.$ayah_pendapatan.'</td>
			</tr>
			<tr>
				<td><b>Nama Perusahaan</b></td>
				<td colspan="2">: '.$dataDapodik->ayah_nama_perusahaan.'</td>
			</tr>
			<tr>
				<td><b>Alamat Perusahaan</b></td>
				<td colspan="2">: '.$dataDapodik->ayah_alamat_perusahaan.'</td>
			</tr>
			<tr>
				<td><b>Berkebutuhan Khusus</b></td>
				<td colspan="2">: '.$dataDapodik->ayah_kebutuhan_khusus.'</td>
			</tr>
			<tr>
				<td><b>Jenis Kebutuhan Khusus</b></td>
				<td colspan="2">: '.$dataDapodik->ayah_jenis_kebutuhan_khusus.'</td>
			</tr>
			<tr>
				<td><b>No. HP/Whatsapp</b></td>
				<td colspan="2">: '.$dataDapodik->ayah_telepon.'</td>
			</tr>
			<tr>
				<td><b>Email</b></td>
				<td colspan="2">: '.$dataDapodik->ayah_email.'</td>
			</tr>
		</table>';
		$pdf->lastPage();
		$pdf->writeHTML($html, true, false, true, false, '');

		$pdf->AddPage();
		$html = '<br><br><br><br><br>
		<table border="0" cellspacing="3" cellpadding="4">
			<tr>
				<td colspan="3"><b>DATA IBU</b></td>
			</tr>
			<tr>
				<td><b>Nama Lengkap</b></td>
				<td colspan="2">: '.$dataDapodik->ibu_nama_lengkap.'</td>
			</tr>
			<tr>
				<td><b>NIK</b></td>
				<td colspan="2">: '.$dataDapodik->ibu_nik.'</td>
			</tr>
			<tr>
				<td><b>Tempat Tanggal Lahir</b></td>
				<td colspan="2">: '.$dataDapodik->ibu_kota_lahir.', '.strftime("%d %B %Y",strtotime($dataDapodik->ibu_tanggal_lahir)).'</td>
			</tr>
			<tr>
				<td><b>Agama</b></td>
				<td colspan="2">: '.$dataDapodik->ibu_agama.'</td>
			</tr>
			<tr>
				<td><b>Kewarganegaraan</b></td>
				<td colspan="2">: '.$dataDapodik->ibu_kewarganegaraan.'</td>
			</tr>
			<tr>
				<td><b>Pendidikan Terakhir</b></td>
				<td colspan="2">: '.$dataDapodik->ibu_pendidikan.'</td>
			</tr>
			<tr>
				<td><b>Pekerjaan</b></td>
				<td colspan="2">: '.$dataDapodik->ibu_pekerjaan.'</td>
			</tr>
			<tr>
				<td><b>Jabatan</b></td>
				<td colspan="2">: '.$dataDapodik->ibu_jabatan.'</td>
			</tr>
			<tr>
				<td><b>Penghasilan</b></td>
				<td colspan="2">: '.$ibu_pendapatan.'</td>
			</tr>
			<tr>
				<td><b>Nama Perusahaan</b></td>
				<td colspan="2">: '.$dataDapodik->ibu_nama_perusahaan.'</td>
			</tr>
			<tr>
				<td><b>Alamat Perusahaan</b></td>
				<td colspan="2">: '.$dataDapodik->ibu_alamat_perusahaan.'</td>
			</tr>
			<tr>
				<td><b>Berkebutuhan Khusus</b></td>
				<td colspan="2">: '.$dataDapodik->ibu_kebutuhan_khusus.'</td>
			</tr>
			<tr>
				<td><b>Jenis Kebutuhan Khusus</b></td>
				<td colspan="2">: '.$dataDapodik->ibu_jenis_kebutuhan_khusus.'</td>
			</tr>
			<tr>
				<td><b>No. HP/Whatsapp</b></td>
				<td colspan="2">: '.$dataDapodik->ibu_telepon.'</td>
			</tr>
			<tr>
				<td><b>Email</b></td>
				<td colspan="2">: '.$dataDapodik->ibu_email.'</td>
			</tr>
		</table>';
		$pdf->lastPage();
		$pdf->writeHTML($html, true, false, true, false, '');

		if($dataDapodik->wali_nama_lengkap != null) : 
		$pdf->AddPage();
		$html = '<br><br><br><br><br>
		<table border="0" cellspacing="3" cellpadding="4">
			<tr>
				<td colspan="3"><b>DATA WALI</b></td>
			</tr>
			<tr>
				<td><b>Nama Lengkap</b></td>
				<td colspan="2">: '.$dataDapodik->wali_nama_lengkap.'</td>
			</tr>
			<tr>
				<td><b>NIK</b></td>
				<td colspan="2">: '.$dataDapodik->wali_nik.'</td>
			</tr>
			<tr>
				<td><b>Tempat Tanggal Lahir</b></td>
				<td colspan="2">: '.$dataDapodik->wali_kota_lahir.', '.strftime("%d %B %Y",strtotime($dataDapodik->wali_tanggal_lahir)).'</td>
			</tr>
			<tr>
				<td><b>Agama</b></td>
				<td colspan="2">: '.$dataDapodik->wali_agama.'</td>
			</tr>
			<tr>
				<td><b>Kewarganegaraan</b></td>
				<td colspan="2">: '.$dataDapodik->wali_kewarganegaraan.'</td>
			</tr>
			<tr>
				<td><b>Pendidikan Terakhir</b></td>
				<td colspan="2">: '.$dataDapodik->wali_pendidikan.'</td>
			</tr>
			<tr>
				<td><b>Pekerjaan</b></td>
				<td colspan="2">: '.$dataDapodik->wali_pekerjaan.'</td>
			</tr>
			<tr>
				<td><b>Jabatan</b></td>
				<td colspan="2">: '.$dataDapodik->wali_jabatan.'</td>
			</tr>
			<tr>
				<td><b>Penghasilan</b></td>
				<td colspan="2">: '.$wali_pendapatan.'</td>
			</tr>
			<tr>
				<td><b>Nama Perusahaan</b></td>
				<td colspan="2">: '.$dataDapodik->wali_nama_perusahaan.'</td>
			</tr>
			<tr>
				<td><b>Alamat Perusahaan</b></td>
				<td colspan="2">: '.$dataDapodik->wali_alamat_perusahaan.'</td>
			</tr>
			<tr>
				<td><b>No. HP/Whatsapp</b></td>
				<td colspan="2">: '.$dataDapodik->wali_telepon.'</td>
			</tr>
			<tr>
				<td><b>Email</b></td>
				<td colspan="2">: '.$dataDapodik->wali_email.'</td>
			</tr>
			<tr>
				<td><b>Hubungan Dengan Anak</b></td>
				<td colspan="2">: '.$dataDapodik->wali_hubungan_anak.'</td>
			</tr>
		</table>';
		$pdf->lastPage();
		$pdf->writeHTML($html, true, false, true, false, '');
		endif;

		$pdf->AddPage();
		$html = '<br><br><br><br><br>
		<table border="0" cellspacing="3" cellpadding="4">
			<tr>
				<td colspan="3"><b>PAS FOTO</b></td>
			</tr>';
		$pdf->Image($pas_foto, 20, 60, 80, 100, '' , '', '', 'true', 300, '', false, false, 1, false, false, false);
		$html .='</table>';
		$pdf->lastPage();
		$pdf->writeHTML($html, true, false, true, false, '');

		$pdf->AddPage();
		$pdf->StartTransform();
		$pdf->Rotate(90);
		$pdf->Image($scan_kk, -240, 27, 235, 180, '' , '', '', 'true', 300, '', false, false, 1, false, false, false);
		$pdf->StopTransform();
		$pdf->lastPage();

		$pdf->AddPage();
		$pdf->StartTransform();
		$pdf->Rotate(90);
		$pdf->Image($scan_ak, -240, 27, 235, 180, '' , '', '', 'true', 300, '', false, false, 1, false, false, false);
		$pdf->StopTransform();
		$pdf->lastPage();
		
		$pdf->Output('tbtk pendaftaran '.$dataPendaftaran->no_registrasi.' '.$tanggal_hari_ini.'.pdf');
	}

	public function surat_pernyataan($slug_nama_lengkap)
	{
		setlocale(LC_ALL, 'id-ID', 'id_ID');
		$tanggal_hari_ini = date('[Ymd]');
		$dataPendaftaran = $this->TbtkModel->getSiswa($slug_nama_lengkap)->row();
		$dataPernyataan = $this->TbtkModel->getPernyataan($dataPendaftaran->id)->row();

		if($dataPendaftaran->pilihan_tingkat === '1')
		{
			$sub_unit = 'TB';
		} else {
			$sub_unit = 'TK';
		}

		$tanda_tangan = 'http://localhost:8080/upload/tanda_tangan2/tbtk/'.$dataPernyataan->tanda_tangan.'';

		$pdf = new TBTKSURAT('P', PDF_UNIT, 'F4', true, 'UTF-8', false);

		$pdf->SetTitle('TBTK Surat Pernyataan '.$dataPendaftaran->no_registrasi.' '.$tanggal_hari_ini);
		$pdf->SetSubject('TBTK Surat Pernyataan '.$dataPendaftaran->no_registrasi.' '.$tanggal_hari_ini);

		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		$pdf->AddPage();
		$html = '<br><br><br>
		<h2 style="text-align:center">SURAT PERNYATAAN ORANG TUA / WALI</h2><br>
		<table border="0" cellspacing="3" cellpadding="4">
			<tr>
				<td colspan="3">Saya yang bertanda tangan dibawah ini,</td>
			</tr>
			<tr>
				<td><b>Nama Lengkap</b></td>
				<td colspan="2">: '.$dataPernyataan->nama_lengkap.'</td>
			</tr>
			<tr>
				<td><b>Alamat Lengkap</b></td>
				<td colspan="2">: '.$dataPernyataan->alamat.'</td>
			</tr>
			<tr>
				<td><b>No.Telp/Handphone</b></td>
				<td colspan="2">: '.$dataPernyataan->handphone.'</td>
			</tr>
			<br>
			<tr>
				<td colspan="3">adalah orangtua/wali dari calon peserta didik : </td>
			</tr>
			<tr>
				<td><b>Nama Peserta Didik</b></td>
				<td colspan="2">: '.$dataPendaftaran->nama_lengkap.'</td>
			</tr>
			<tr>
				<td><b>Tempat Tanggal Lahir</b></td>
				<td colspan="2">: '.$dataPendaftaran->kota_lahir.', '.strftime("%d %B %Y",strtotime($dataPendaftaran->tanggal_lahir)).'</td>
			</tr>
			<tr>
				<td><b>No. Registrasi</b></td>
				<td colspan="2">: '.$dataPendaftaran->no_registrasi.'</td>
			</tr>
			<br>
			<tr>
				<td colspan="3" style="text-align:justify">
				Dengan ini menyatakan bahwa jika anak kami diterima di '.$sub_unit.' KATOLIK
				SANTA URSULA BANDUNG, maka kami sebagai orang tua/wali TIDAK BERKEBERATAN untuk
				MENERIMA segala peraturan yang berlaku, selama anak kami menjalankan masa
				pendidikannya di <br>'.$sub_unit.' KATOLIK SANTA URSULA BANDUNG.
				</td>
			</tr>
			<br>
			<tr>
				<td colspan="3" style="text-align:justify">Pernyataan tersebut adalah :</td>
			</tr>
			<tr>
				<td colspan="3" style="text-align:justify">
				1. MENYETUJUI SEPENUHNYA ketentuan-ketentuan yang berlaku dan berkaitan dengan
				sistem pembelajaran dan penilaian di '.$sub_unit.' KATOLIK SANTA URSULA
				BANDUNG;
				</td>
			</tr>
			<tr>
				<td colspan="3" style="text-align:justify">
				2. HADIR SETIAP MENDAPAT UNDANGAN dari pihak sekolah;
				</td>
			</tr>
			<tr>
				<td colspan="3" style="text-align:justify">
				3. Jika terjadi hal yang tidak menyenangkan atau tidak berkenan, masalah
                diselesaikan secara KEKELUARGAAN;
				</td>
			</tr>
			<tr>
				<td colspan="3" style="text-align:justify">
				4. Apabila di kemudian hari anak kami ditemukan MELAKUKAN TINDAKAN yang tidak
				sesuai dengan visi misi sekolah, kami bersedia MENERIMA pembatalan penerimaan
				calon peserta didik dikeluarkan dari '.$sub_unit.' KATOLIK SANTA URSULA
				BANDUNG.
				</td>
			</tr>
			<br>
			<tr>
				<td colspan="3" style="text-align:justify">
				Demikian pernyataan ini kami terima dan kami buat atas kesadaran sendiri demi
				kelancaran pendidikan putra/putri kami di lembaga pendidikan '.$sub_unit.'
				KATOLIK SANTA URSULA BANDUNG dan tanpa paksaan orang lain.
				</td>
			</tr>
			<tr>
				<td colspan="3" style="text-align:right"><br><br><br><br><br></td>';
		$pdf->Image($tanda_tangan, 135, 267, 55, '', '' , '', '', 'true', 300, '', false, false, 1, false, false, false);
		$html .= '
			</tr>
			<tr>
				<td colspan="2" style="text-align:left">Dibuat : '.strftime("%d %B %Y",strtotime($dataPernyataan->created_at)).'</td>
				<td style="text-align:center">'.$dataPernyataan->nama_lengkap.'</td>
			</tr>
		</table>';
		$pdf->lastPage();
		$pdf->writeHTML($html, true, false, true, false, '');
		
		$pdf->Output('tbtk pendaftaran '.$dataPendaftaran->no_registrasi.' '.$tanggal_hari_ini.'.pdf');
	}

	public function surat_keuangan($slug_nama_lengkap)
	{
		setlocale(LC_ALL, 'id-ID', 'id_ID');
		$tanggal_hari_ini = date('[Ymd]');
		$dataPendaftaran = $this->TbtkModel->getSiswa($slug_nama_lengkap)->row();
		$dataBeasiswa = $this->TbtkModel->getBeasiswa($dataPendaftaran->id)->row();
		$dataKeuangan = $this->TbtkModel->getKeuangan($dataPendaftaran->id)->row();

		if ($dataBeasiswa != null) {
			$pengurangan_uang_pangkal = $dataBeasiswa->uang_pangkal;
			$uang_pangkal = 7500000 - $pengurangan_uang_pangkal;
			if($dataPendaftaran->pilihan_tingkat === '1')
			{
				$sub_unit = 'TB';
				$pengurangan_uang_sekolah = $dataBeasiswa->uang_sekolah;
				$uang_sekolah = 725000 - $pengurangan_uang_sekolah;
			} else {
				$sub_unit = 'TK';
				$pengurangan_uang_sekolah = $dataBeasiswa->uang_sekolah;
				$uang_sekolah = 700000 - $pengurangan_uang_sekolah;
			}
		} else {
			$uang_pangkal = 7500000;
			if($dataPendaftaran->pilihan_tingkat === '1')
			{
				$sub_unit = 'TB';
				$uang_sekolah = 725000;
			} else {
				$sub_unit = 'TK';
				$uang_sekolah = 700000;
			}
		}

		$month = strtotime($dataPendaftaran->created_at);
        if(strftime("%m",$month) <= 8) {
            $bulan_tahap_1 = strftime("%B %Y",strtotime("+1 month", $month));
            $bulan_tahap_2 = strftime("%B %Y",strtotime("+2 month", $month));
            $bulan_tahap_3 = strftime("%B %Y",strtotime("+3 month", $month));
            $bulan_tahap_4 = strftime("%B %Y",strtotime("+4 month", $month));
        } else {
            $bulan_tahap_1 = strftime("%B %Y",$month);
            $bulan_tahap_2 = strftime("%B %Y",strtotime("+1 month", $month));
            $bulan_tahap_3 = strftime("%B %Y",strtotime("+2 month", $month));
            $bulan_tahap_4 = strftime("%B %Y",strtotime("+3 month", $month));
        }

		$tanda_tangan = 'http://localhost:8080/upload/tanda_tangan/tbtk/'.$dataKeuangan->tanda_tangan.'';

		$pdf = new TBTKSURAT('P', PDF_UNIT, 'F4', true, 'UTF-8', false);

		$pdf->SetTitle('TBTK Surat Keuangan '.$dataPendaftaran->no_registrasi.' '.$tanggal_hari_ini);
		$pdf->SetSubject('TBTK Surat Keuangan '.$dataPendaftaran->no_registrasi.' '.$tanggal_hari_ini);

		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		$pdf->AddPage();
		$html = '<br><br><br>
		<h2 style="text-align:center">SURAT PERNYATAAN PELUNASAN KEUANGAN</h2><br>
		<table border="0" cellspacing="3" cellpadding="4">
			<tr>
				<td colspan="3">Saya yang bertanda tangan dibawah ini,</td>
			</tr>
			<tr>
				<td><b>Nama Lengkap</b></td>
				<td colspan="2">: '.$dataKeuangan->nama_lengkap.'</td>
			</tr>
			<tr>
				<td><b>Alamat Lengkap</b></td>
				<td colspan="2">: '.$dataKeuangan->alamat.'</td>
			</tr>
			<tr>
				<td><b>No.Telp/Handphone</b></td>
				<td colspan="2">: '.$dataKeuangan->handphone.'</td>
			</tr>
			<br>
			<tr>
				<td colspan="3">adalah orangtua dari calon peserta didik : </td>
			</tr>
			<tr>
				<td><b>Nama Peserta Didik</b></td>
				<td colspan="2">: '.$dataPendaftaran->nama_lengkap.'</td>
			</tr>
			<tr>
				<td><b>No. Registrasi</b></td>
				<td colspan="2">: '.$dataPendaftaran->no_registrasi.'</td>
			</tr>
			<br>
			<tr>
				<td colspan="3" style="text-align:justify">
				yang akan menempuh pendidikan di '.$sub_unit.' Santa Ursula Tahun
                Pelajaran 2022/2023, maka saya bersedia mendukung dan menyetujui pembayaran :
				</td>
			</tr>
			<tr>
				<td colspan="3" style="text-align:justify">
				1. Dana Pendidikan sebesar Rp. '.number_format($uang_pangkal, 0, '', '.').',-
				</td>
			</tr>
			<tr>
				<td colspan="3" style="text-align:justify">
				2. Uang Sekolah (SPP) selama 12 bulan mulai dibayar dari bulan Juli 2022 tiap bulan sebesar
				Rp. '.number_format($uang_sekolah, 0, '', '.').',-
				</td>
			</tr>
			<br>
			<tr>
				<td colspan="3">KETERANGAN : </td>
			</tr>
			<tr>
				<td colspan="3">Jumlah Dana tersebut di atas belum termasuk :</td>
			</tr>
			<tr>
				<td colspan="3">1. Biaya Seragam</td>
			</tr>
			<tr>
				<td colspan="3">2. Alat dan Bahan Prakarya</td>
			</tr>
			<tr>
				<td colspan="3">3. UKGS</td>
			</tr>
			<tr>
				<td colspan="3">4. Eksploring 1 Tahun</td>
			</tr>
			<tr>
				<td colspan="3">5. Ekstrakurikuler Selama 1 Tahun</td>
			</tr>
			<tr>
				<td colspan="3">6. Etika Makan Selama 1 Tahun</td>
			</tr>
		</table>';
		$pdf->lastPage();
		$pdf->writeHTML($html, true, false, true, false, '');

		$pdf->AddPage();
		$html = '<br><br><br><br><br>
		<table border="0" cellspacing="3" cellpadding="4">
			<tr>
				<td colspan="3">Tahap pembayaran dibagi menjadi '.$dataKeuangan->tahap_pembayaran.' tahap dengan rincian : </td>
			</tr>';
        if (($dataKeuangan->uang_tahap_1 !== 0) && ($dataKeuangan->uang_tahap_2 == 0) && ($dataKeuangan->uang_tahap_3 == 0) && ($dataKeuangan->uang_tahap_4 == 0)) {
        $html .= '
			<tr>
				<td colspan="3">
				1. [Tahap 1] Tanggal '.$dataKeuangan->tanggal_pembayaran.' '.$bulan_tahap_1.' sebesar Rp. '.number_format($dataKeuangan->uang_tahap_1, 0, '', '.').',-
				</td>
			</tr>
			<br>
			<tr>
				<td colspan="3">CATATAN : </td>
			</tr>
			<tr>
				<td colspan="3">Sebisa mungkin pembayaran dilakukan pada tanggal yang sama di tiap tahapnya.</td>
			</tr>
			<br>
			<tr>
				<td colspan="3">PERHATIAN : </td>
			</tr>
			<tr>
				<td colspan="3" style="text-align:justify">1. Bila Anda mengundurkan diri, semua pembayaran yang telah dilakukan tidak
				dapat ditarik kembali dengan alasan apa pun.</td>
			</tr>
			<tr>
				<td colspan="3" style="text-align:justify">2. Segala bentuk pertanyaan dapat menghubungi kontak Panitia PPDB TB-TK Santa
				Ursula yang disediakan di halaman website.</td>
			</tr>
			<tr>
				<td colspan="3" style="text-align:justify">3. Pembayaran dilakukan dengan mentransfer ke Virtual Account yang didapatkan
				saat pendaftaran yaitu : '. $dataPendaftaran->no_virtual.'.</td>
			</tr>
			<tr>
				<td colspan="3" style="text-align:justify">4. Dilarang melakukan pembayaran dengan mentransfer ke Rekening Yayasan Prasama
				Bhakti.</td>
			</tr>
			<tr>
				<td colspan="3" style="text-align:justify">5. Setelah melakukan pembayaran, bukti transfer diupload pada Halaman Dashboard
				Calon Peserta Didik -> Data Calon Peserta Didik -> Data Pembayaran.</td>
			</tr>
			<br>
			<tr>
				<td colspan="3" style="text-align:justify">Saya akan melakukan pembayaran pada tanggal sesuai dengan yang telah saya
				sepakati di atas. Demikian pernyataan ini saya buat dengan sesungguhnya.</td>
			</tr>
			<tr>
				<td colspan="3" style="text-align:right"><br><br><br><br><br></td>';
            $pdf->Image($tanda_tangan, 135, 199, 55, '', '', '', '', 'true', 300, '', false, false, 1, false, false, false);
            $html .= '
			</tr>
			<tr>
				<td colspan="2" style="text-align:left">Dibuat : '.strftime("%d %B %Y", strtotime($dataKeuangan->created_at)).'</td>
				<td style="text-align:center">'.$dataKeuangan->nama_lengkap.'</td>
			</tr>
		</table>';
        } else if($dataKeuangan->uang_tahap_1 !== 0 && $dataKeuangan->uang_tahap_2 !== 0 && $dataKeuangan->uang_tahap_3 == 0 && $dataKeuangan->uang_tahap_4 == 0) {
		$html .= '
			<tr>
				<td colspan="3">
				1. [Tahap 1] Tanggal '.$dataKeuangan->tanggal_pembayaran.' '.$bulan_tahap_1.' sebesar Rp. '.number_format($dataKeuangan->uang_tahap_1, 0, '', '.').',-
				</td>
			</tr>
			<tr>
				<td colspan="3">
				2. [Tahap 2] Tanggal '.$dataKeuangan->tanggal_pembayaran.' '.$bulan_tahap_2.' sebesar Rp. '.number_format($dataKeuangan->uang_tahap_2, 0, '', '.').',-
				</td>
			</tr>
			<br>
			<tr>
				<td colspan="3">CATATAN : </td>
			</tr>
			<tr>
				<td colspan="3">Sebisa mungkin pembayaran dilakukan pada tanggal yang sama di tiap tahapnya.</td>
			</tr>
			<br>
			<tr>
				<td colspan="3">PERHATIAN : </td>
			</tr>
			<tr>
				<td colspan="3" style="text-align:justify">1. Bila Anda mengundurkan diri, semua pembayaran yang telah dilakukan tidak
				dapat ditarik kembali dengan alasan apa pun.</td>
			</tr>
			<tr>
				<td colspan="3" style="text-align:justify">2. Segala bentuk pertanyaan dapat menghubungi kontak Panitia PPDB TB-TK Santa
				Ursula yang disediakan di halaman website.</td>
			</tr>
			<tr>
				<td colspan="3" style="text-align:justify">3. Pembayaran dilakukan dengan mentransfer ke Virtual Account yang didapatkan
				saat pendaftaran yaitu : '. $dataPendaftaran->no_virtual.'.</td>
			</tr>
			<tr>
				<td colspan="3" style="text-align:justify">4. Dilarang melakukan pembayaran dengan mentransfer ke Rekening Yayasan Prasama
				Bhakti.</td>
			</tr>
			<tr>
				<td colspan="3" style="text-align:justify">5. Setelah melakukan pembayaran, bukti transfer diupload pada Halaman Dashboard
				Calon Peserta Didik -> Data Calon Peserta Didik -> Data Pembayaran.</td>
			</tr>
			<br>
			<tr>
				<td colspan="3" style="text-align:justify">Saya akan melakukan pembayaran pada tanggal sesuai dengan yang telah saya
				sepakati di atas. Demikian pernyataan ini saya buat dengan sesungguhnya.</td>
			</tr>
			<tr>
				<td colspan="3" style="text-align:right"><br><br><br><br><br></td>';
            $pdf->Image($tanda_tangan, 135, 208, 55, '', '', '', '', 'true', 300, '', false, false, 1, false, false, false);
            $html .= '
			</tr>
			<tr>
				<td colspan="2" style="text-align:left">Dibuat : '.strftime("%d %B %Y", strtotime($dataKeuangan->created_at)).'</td>
				<td style="text-align:center">'.$dataKeuangan->nama_lengkap.'</td>
			</tr>
		</table>';
		} else if($dataKeuangan->uang_tahap_1 !== 0 && $dataKeuangan->uang_tahap_2 !== 0 && $dataKeuangan->uang_tahap_3 !== 0 && $dataKeuangan->uang_tahap_4 == 0) {
		$html .= '
			<tr>
				<td colspan="3">
				1. [Tahap 1] Tanggal '.$dataKeuangan->tanggal_pembayaran.' '.$bulan_tahap_1.' sebesar Rp. '.number_format($dataKeuangan->uang_tahap_1, 0, '', '.').',-
				</td>
			</tr>
			<tr>
				<td colspan="3">
				2. [Tahap 2] Tanggal '.$dataKeuangan->tanggal_pembayaran.' '.$bulan_tahap_2.' sebesar Rp. '.number_format($dataKeuangan->uang_tahap_2, 0, '', '.').',-
				</td>
			</tr>
			<tr>
				<td colspan="3">
				3. [Tahap 3] Tanggal '.$dataKeuangan->tanggal_pembayaran.' '.$bulan_tahap_3.' sebesar Rp. '.number_format($dataKeuangan->uang_tahap_3, 0, '', '.').',-
				</td>
			</tr>
			<br>
			<tr>
				<td colspan="3">CATATAN : </td>
			</tr>
			<tr>
				<td colspan="3">Sebisa mungkin pembayaran dilakukan pada tanggal yang sama di tiap tahapnya.</td>
			</tr>
			<br>
			<tr>
				<td colspan="3">PERHATIAN : </td>
			</tr>
			<tr>
				<td colspan="3" style="text-align:justify">1. Bila Anda mengundurkan diri, semua pembayaran yang telah dilakukan tidak
				dapat ditarik kembali dengan alasan apa pun.</td>
			</tr>
			<tr>
				<td colspan="3" style="text-align:justify">2. Segala bentuk pertanyaan dapat menghubungi kontak Panitia PPDB TB-TK Santa
				Ursula yang disediakan di halaman website.</td>
			</tr>
			<tr>
				<td colspan="3" style="text-align:justify">3. Pembayaran dilakukan dengan mentransfer ke Virtual Account yang didapatkan
				saat pendaftaran yaitu : '. $dataPendaftaran->no_virtual.'.</td>
			</tr>
			<tr>
				<td colspan="3" style="text-align:justify">4. Dilarang melakukan pembayaran dengan mentransfer ke Rekening Yayasan Prasama
				Bhakti.</td>
			</tr>
			<tr>
				<td colspan="3" style="text-align:justify">5. Setelah melakukan pembayaran, bukti transfer diupload pada Halaman Dashboard
				Calon Peserta Didik -> Data Calon Peserta Didik -> Data Pembayaran.</td>
			</tr>
			<br>
			<tr>
				<td colspan="3" style="text-align:justify">Saya akan melakukan pembayaran pada tanggal sesuai dengan yang telah saya
				sepakati di atas. Demikian pernyataan ini saya buat dengan sesungguhnya.</td>
			</tr>
			<tr>
				<td colspan="3" style="text-align:right"><br><br><br><br><br></td>';
            $pdf->Image($tanda_tangan, 135, 217, 55, '', '', '', '', 'true', 300, '', false, false, 1, false, false, false);
            $html .= '
			</tr>
			<tr>
				<td colspan="2" style="text-align:left">Dibuat : '.strftime("%d %B %Y", strtotime($dataKeuangan->created_at)).'</td>
				<td style="text-align:center">'.$dataKeuangan->nama_lengkap.'</td>
			</tr>
		</table>';
		} else if($dataKeuangan->uang_tahap_1 !== 0 && $dataKeuangan->uang_tahap_2 !== 0 && $dataKeuangan->uang_tahap_3 !== 0 && $dataKeuangan->uang_tahap_4 !== 0) {
		$html .= '
			<tr>
				<td colspan="3">
				1. [Tahap 1] Tanggal '.$dataKeuangan->tanggal_pembayaran.' '.$bulan_tahap_1.' sebesar Rp. '.number_format($dataKeuangan->uang_tahap_1, 0, '', '.').',-
				</td>
			</tr>
			<tr>
				<td colspan="3">
				2. [Tahap 2] Tanggal '.$dataKeuangan->tanggal_pembayaran.' '.$bulan_tahap_2.' sebesar Rp. '.number_format($dataKeuangan->uang_tahap_2, 0, '', '.').',-
				</td>
			</tr>
			<tr>
				<td colspan="3">
				3. [Tahap 3] Tanggal '.$dataKeuangan->tanggal_pembayaran.' '.$bulan_tahap_3.' sebesar Rp. '.number_format($dataKeuangan->uang_tahap_3, 0, '', '.').',-
				</td>
			</tr>
			<tr>
				<td colspan="3">
				4. [Tahap 4] Tanggal '.$dataKeuangan->tanggal_pembayaran.' '.$bulan_tahap_4.' sebesar Rp. '.number_format($dataKeuangan->uang_tahap_4, 0, '', '.').',-
				</td>
			</tr>
			<br>
			<tr>
				<td colspan="3">CATATAN : </td>
			</tr>
			<tr>
				<td colspan="3">Sebisa mungkin pembayaran dilakukan pada tanggal yang sama di tiap tahapnya.</td>
			</tr>
			<br>
			<tr>
				<td colspan="3">PERHATIAN : </td>
			</tr>
			<tr>
				<td colspan="3" style="text-align:justify">1. Bila Anda mengundurkan diri, semua pembayaran yang telah dilakukan tidak
				dapat ditarik kembali dengan alasan apa pun.</td>
			</tr>
			<tr>
				<td colspan="3" style="text-align:justify">2. Segala bentuk pertanyaan dapat menghubungi kontak Panitia PPDB TB-TK Santa
				Ursula yang disediakan di halaman website.</td>
			</tr>
			<tr>
				<td colspan="3" style="text-align:justify">3. Pembayaran dilakukan dengan mentransfer ke Virtual Account yang didapatkan
				saat pendaftaran yaitu : '. $dataPendaftaran->no_virtual.'.</td>
			</tr>
			<tr>
				<td colspan="3" style="text-align:justify">4. Dilarang melakukan pembayaran dengan mentransfer ke Rekening Yayasan Prasama
				Bhakti.</td>
			</tr>
			<tr>
				<td colspan="3" style="text-align:justify">5. Setelah melakukan pembayaran, bukti transfer diupload pada Halaman Dashboard
				Calon Peserta Didik -> Data Calon Peserta Didik -> Data Pembayaran.</td>
			</tr>
			<br>
			<tr>
				<td colspan="3" style="text-align:justify">Saya akan melakukan pembayaran pada tanggal sesuai dengan yang telah saya
				sepakati di atas. Demikian pernyataan ini saya buat dengan sesungguhnya.</td>
			</tr>
			<tr>
				<td colspan="3" style="text-align:right"><br><br><br><br><br></td>';
            $pdf->Image($tanda_tangan, 135, 226, 55, '', '', '', '', 'true', 300, '', false, false, 1, false, false, false);
            $html .= '
			</tr>
			<tr>
				<td colspan="2" style="text-align:left">Dibuat : '.strftime("%d %B %Y", strtotime($dataKeuangan->created_at)).'</td>
				<td style="text-align:center">'.$dataKeuangan->nama_lengkap.'</td>
			</tr>
		</table>';
		}
		$pdf->lastPage();
		$pdf->writeHTML($html, true, false, true, false, '');
		
		$pdf->Output('tbtk keuangan '.$dataPendaftaran->no_registrasi.' '.$tanggal_hari_ini.'.pdf');
	}
}

class TBTKPDF extends TCPDF
{
	public function Header()
	{
		// Left Logo
		$image_left = base_url('public/logo_serviam.png');
		$this->Image($image_left, 12, 13, 28, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
		// Title
		$this->SetY(15);
		$this->SetFont('times', '', 14);
		$this->Cell(0, 13, 'YAYASAN PRASAMA BHAKTI', 0, true, 'C', 0, '', 0, false, 'M', 'M');
		$this->SetFont('times', 'B', 22);
		$this->Cell(0, 13, 'TB-TK KATOLIK SANTA URSULA', 0, true, 'C', 0, '', 0, false, 'M', 'M');
		$this->SetFont('times', 'B', 12);
		$this->Cell(0, 10, 'TERAKREDITASI A', 0, true, 'C', 0, '', 0, false, 'M', 'M');
		$this->SetFont('times', '', 12);
		$this->Cell(0, 10, 'Jalan Bengawan No. 2 Bandung 40114, Telepon (022) 7211367', 0, true, 'C', 0, '', 0, false, 'M', 'M');
		$this->SetFont('times', '', 12);
		$this->Cell(0, 13, 'Website: tk.santaursula-bdg.sch.id Email: tk.ursula.bdg@gmail.com', 0, true, 'C', 0, '', 0, false, 'M', 'M');
		$this->writeHTML("<hr>", true, false, false, false, '');
		// Right Logo
		$image_left = base_url('public/logo_entrepreneur.png');
		$this->Image($image_left, 172, 13, 26, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
	}

	public function Footer()
	{
		// Position at 15 mm from bottom
		$this->SetY(-15);
		// Set font
		$this->SetFont('helvetica', 'I', 8);
		// Page number
		$this->Cell(0, 10, 'Page ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
	}
}

class TBTKSURAT extends TCPDF
{
	public function Header()
	{
		// Left Logo
		$image_left = base_url('public/logo_serviam.png');
		$this->Image($image_left, 12, 13, 28, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
		// Title
		$this->SetY(15);
		$this->SetFont('times', '', 14);
		$this->Cell(0, 13, 'YAYASAN PRASAMA BHAKTI', 0, true, 'C', 0, '', 0, false, 'M', 'M');
		$this->SetFont('times', 'B', 22);
		$this->Cell(0, 13, 'TB-TK KATOLIK SANTA URSULA', 0, true, 'C', 0, '', 0, false, 'M', 'M');
		$this->SetFont('times', 'B', 12);
		$this->Cell(0, 10, 'TERAKREDITASI A', 0, true, 'C', 0, '', 0, false, 'M', 'M');
		$this->SetFont('times', '', 12);
		$this->Cell(0, 10, 'Jalan Bengawan No. 2 Bandung 40114, Telepon (022) 7211367', 0, true, 'C', 0, '', 0, false, 'M', 'M');
		$this->SetFont('times', '', 12);
		$this->Cell(0, 13, 'Website: tk.santaursula-bdg.sch.id Email: tk.ursula.bdg@gmail.com', 0, true, 'C', 0, '', 0, false, 'M', 'M');
		$this->writeHTML("<hr>", true, false, false, false, '');
		// Right Logo
		$image_left = base_url('public/logo_entrepreneur.png');
		$this->Image($image_left, 172, 13, 26, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
	}
}
