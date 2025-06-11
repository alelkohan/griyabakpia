<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Karyawan extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Karyawan_model');
    }

    public function index()
    {
        $this->load->view('karyawan/karyawan');
    }
    public function index_absensi()
    {
        $this->load->view('karyawan/absensi_karyawan');
    }

    private function set_output($data)
    {
        $this->output
        ->set_status_header(200)
        ->set_content_type('application/json', 'utf-8')
        ->set_output(json_encode($data, JSON_PRETTY_PRINT))
        ->_display();
        exit;
    }

    public function get_all_karyawan()
    {
        $daftar_karyawan = $this->Karyawan_model->get_karyawan();
        $daftar_input = array();
        $no = 1;

        foreach ($daftar_karyawan as $key) {
            $status_badge = ($key->status_karyawan == "Nonaktif")
            ? '<span class="badge rounded text-danger bg-danger-subtle">' . $key->status_karyawan . '</span>'
            : '<span class="badge rounded text-success bg-success-subtle">' . $key->status_karyawan . '</span>';

            // Format data untuk dimasukkan ke dalam DataTable
            $bahan_input = array(
                $no++,
                '<div class="d-flex align-items-center">
                <img src="' . base_url('upload/karyawan/' . $key->foto_karyawan) . '" class="me-2 thumb-md align-self-center rounded" alt="foto ' . $key->nama_karyawan . '" style="width: 40px; height: 40px; object-fit: cover; border-radius: 6px;">
                <div class="flex-grow-1 text-truncate"> 
                <h6 class="m-0">' . $key->nama_karyawan . '</h6>
                <p class="fs-12 text-muted mb-0">' . $key->kelamin_karyawan . '</p>                                                                                           
                </div><!--end media body-->
                </div>',
                $key->alamat_karyawan,
                $key->nomor_telepon,                
                $key->peran_karyawan,
                $key->tanggal_masuk,
                $key->status_tempat_tinggal,
                $status_badge,
                '<div class="dropdown d-inline-block">
                <a class="dropdown-toggle arrow-none" id="dLabel' . $key->id_karyawan . '" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                <i class="las la-ellipsis-v fs-20 text-muted"></i>
                </a>
                <div class="text-end">
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dLabel' . $key->id_karyawan . '">
                <button class="dropdown-item btn-karyawan-edit" data-id="' . $key->id_karyawan .'">Edit</button>
                <button class="dropdown-item btn-karyawan-delete" data-id="' . $key->id_karyawan .'">Hapus</button>
                </div>
                </div>
                </div>',
            );
            array_push($daftar_input, $bahan_input);
        };

        // Kirim data dalam bentuk JSON
        $response = array(
            'data' => $daftar_input
        );
        $this->set_output($response);
    }

    public function get_all_absensi()
    {
        $daftar_absensi = $this->Karyawan_model->get_absen();
        $daftar_input = array();
        $no = 1;

        foreach ($daftar_absensi as $key) {
            switch ($key->status) {
                case 'hadir':
                $status = '<span class="badge rounded text-success bg-success-subtle">Hadir</span>';
                break;
                case 'izin':
                $status = '<span class="badge rounded text-warning bg-warning-subtle"> Izin </span>';
                break;
                case 'sakit':
                $status = '<span class="badge rounded text-info bg-info-subtle">Sakit</span>';
                break;
                case 'alpha':
                $status = '<span class="badge rounded text-danger bg-danger-subtle">Alpha</span>';
                break;
                default:
                $status = '<span class="badge rounded text-secondary bg-secondary-subtle">Tidak diketahui</span>';
            }

            $data = array(
                $no++,
                '<div class="d-flex align-items-center">
                <img src="' . base_url('upload/karyawan/' . $key->foto_karyawan) . '" class="me-2 thumb-md align-self-center rounded" alt="foto ' . $key->nama_karyawan . '" style="width: 40px; height: 40px; object-fit: cover; border-radius: 6px;">
                <div class="flex-grow-1 text-truncate"> 
                <h6 class="m-0">' . $key->nama_karyawan . '</h6>
                <p class="fs-12 text-muted mb-0">' . $key->kelamin_karyawan . '</p>                                                                                           
                </div><!--end media body-->
                </div>',
                $key->peran_karyawan,
                (!empty($key->waktu_masuk) && date('H:i', strtotime($key->waktu_masuk)) !== '00:00') ? date('H:i', strtotime($key->waktu_masuk)) : '-',
                $key->lembur !== null ? $key->lembur . ' Jam' : '-',
                (!empty($key->waktu_pulang) && date('H:i', strtotime($key->waktu_pulang)) !== '00:00') ? date('H:i', strtotime($key->waktu_pulang)) : '-',
                $status,
                '<div class="dropdown d-inline-block">
                <a class="dropdown-toggle arrow-none" id="dLabel' . $key->id_absensi . '" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                <i class="las la-ellipsis-v fs-20 text-muted"></i>
                </a>
                <div class="text-end">
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dLabel' . $key->id_absensi . '">
                <button class="dropdown-item btn-absensi-edit" data-id="' . $key->id_absensi .'">Edit</button>
                <button class="dropdown-item btn-absensi-delete" data-id="' . $key->id_absensi .'">Hapus</button>
                </div>
                </div>
                </div>',
            );

            array_push($daftar_input, $data);
        }

        // Kirim data dalam bentuk JSON
        $response = array(
            'data' => $daftar_input
        );
        $this->set_output($response);
    }

    public function create_karyawan()
    {
        // Cek apakah ada file foto yang diupload
        if (!empty($_FILES['foto_karyawan']['name'])) {
            $config['upload_path']   = './upload/karyawan/';
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['max_size']      = 10048;
            $config['file_name']     = time() . '_' . $_FILES['foto_karyawan']['name'];

            $this->upload->initialize($config);

            if (!$this->upload->do_upload('foto_karyawan')) {
                $this->set_output(['status' => 'error', 'message' => $this->upload->display_errors()]);
                return;
            } else {
                $foto = $this->upload->data('file_name');
            }
        }
        // } else {
        //     $foto = 'default.png'; // fallback jika tidak ada foto diupload
        // }

        $password = $this->input->post('password');
        // if (strlen($password) < 6) {
        //     return $this->set_output(['status' => 'error', 'message' => 'Password minimal 6 karakter']);
        // }

        // Ambil data dari POST
        $data = [
            'nama_karyawan'         => $this->input->post('nama_karyawan', true),
            'alamat_karyawan'       => $this->input->post('alamat_karyawan', true),
            'nomor_telepon'         => $this->input->post('nomor_telepon', true),
            'peran_karyawan'        => $this->input->post('peran_karyawan', true),
            'tanggal_masuk'         => $this->input->post('tanggal_masuk', true),
            'status_tempat_tinggal' => $this->input->post('status_tempat_tinggal', true),
            'status_karyawan'       => $this->input->post('status_karyawan', true),
            'foto_karyawan'         => $foto,
            'password'              => password_hash($password, PASSWORD_DEFAULT)
        ];

        // Simpan ke database
        $insert = $this->Karyawan_model->insert_karyawan($data);

        if ($insert) {
            $this->set_output(['status' => 'success']);
        } else {
            $this->set_output(['status' => 'error', 'message' => 'Gagal menambahkan data ke database.']);
        }
    }

    public function update_karyawan($id_karyawan)
    {
        // Ambil data karyawan lama dari DB (buat cek foto lama)
        $karyawan_lama = $this->Karyawan_model->get_karyawan_by_id($id_karyawan);

        if (!$karyawan_lama) {
            return $this->set_output(['status' => 'error', 'message' => 'Data karyawan tidak ditemukan']);
        }

        // Cek apakah ada file foto yang diupload
        if (!empty($_FILES['foto_karyawan']['name'])) {
            $config['upload_path']   = './upload/karyawan/';
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['max_size']      = 10048;
            $config['file_name']     = time() . '_' . $_FILES['foto_karyawan']['name'];

            $this->upload->initialize($config);

            if (!$this->upload->do_upload('foto_karyawan')) {
                $this->set_output(['status' => 'error', 'message' => $this->upload->display_errors()]);
                return;
            } else {
                // Hapus foto lama jika ada
                if ($karyawan_lama->foto_karyawan && file_exists('./upload/karyawan/' . $karyawan_lama->foto_karyawan)) {
                    unlink('./upload/karyawan/' . $karyawan_lama->foto_karyawan);
                }
                $foto = $this->upload->data('file_name');
            }
        } else {
            // Kalau gak upload foto baru, tetap pakai foto lama
            $foto = $karyawan_lama->foto_karyawan;
        }

        // Password opsional di edit: kalau diisi update, kalau gak biarkan
        $password = $this->input->post('password');
        if ($password) {
            if (strlen($password) < 6) {
                return $this->set_output(['status' => 'error', 'message' => 'Password minimal 6 karakter']);
            }
            $password_hashed = password_hash($password, PASSWORD_DEFAULT);
        } else {
            $password_hashed = $karyawan_lama->password; // pakai password lama kalau gak diubah
        }

        // Ambil data dari POST
        $data = [
            'nama_karyawan'         => $this->input->post('nama_karyawan', true),
            'alamat_karyawan'       => $this->input->post('alamat_karyawan', true),
            'nomor_telepon'         => $this->input->post('nomor_telepon', true),
            'peran_karyawan'        => $this->input->post('peran_karyawan', true),
            'tanggal_masuk'         => $this->input->post('tanggal_masuk', true),
            'status_tempat_tinggal' => $this->input->post('status_tempat_tinggal', true),
            'status_karyawan'       => $this->input->post('status_karyawan', true),
            'foto_karyawan'         => $foto,
            'password'              => $password_hashed
        ];

        // Update ke database
        $update = $this->Karyawan_model->update_karyawan($id_karyawan, $data);

        if ($update) {
            $this->set_output(['status' => 'success']);
        } else {
            $this->set_output(['status' => 'error', 'message' => 'Gagal update data ke database.']);
        }
    }

    public function delete_karyawan()
    {
        $id_karyawan = $this->input->post('id_karyawan');
        $karyawan = $this->Karyawan_model->get_karyawan_by_id($id_karyawan);

        unlink('./upload/karyawan/' . $karyawan->foto_karyawan);

        $delete = $this->Karyawan_model->delete_karyawan($id_karyawan);

        $response = array(
            'status'    => 'sukses',
            'pesan'        => 'Berhasil dihapus',
        );
        $this->set_output($response);
    }

    public function absen_masuk_karyawan()
    {
        $id_karyawan = $this->input->post('id_karyawan');
        $status = $this->input->post('status_kehadiran');
        $jam_input = $this->input->post('waktu_masuk');

        for ($i = 0; $i < count($id_karyawan); $i++) {

            if ($status[$i] === 'hadir') {
                $waktu = date('Y-m-d') . ' ' . $jam_input[$i] . ':00';
            } else {
                $waktu = date('Y-m-d') . ' 00:00:00';; // atau biarkan field ini nullable
            }

            $data = [
                'id_karyawan' => $id_karyawan[$i],
                'status' => $status[$i],
                'waktu_masuk' => $waktu
            ];
            $insert = $this->Karyawan_model->insert_absensi($data);
        }

        // var_dump($data);
        // die();
        if ($insert) {
            $this->set_output(['status' => 'success']);
        } else {
            $this->set_output(['status' => 'error', 'message' => 'Gagal menambahkan data ke database.']);
        }
    }

    public function absen_pulang_karyawan()
    {
        $id_karyawan = $this->input->post('id_karyawan');
        $jam_pulang = $this->input->post('waktu_pulang');

        for ($i = 0; $i < count($id_karyawan); $i++) {
            $waktu_pulang = date('Y-m-d') . ' ' . $jam_pulang[$i] . ':00';

            $update = $this->Karyawan_model->update_pulang($id_karyawan[$i], $waktu_pulang);
        }

        if ($update) {
            $this->set_output(['status' => 'success']);
        } else {
            $this->set_output(['status' => 'error', 'message' => 'Gagal update waktu pulang.']);
        }
    }

    public function add_lembur()
    {
        $id_karyawan = $this->input->post('id_karyawan');
        $lembur = $this->input->post('lembur');

        $today = date('Y-m-d');

        for ($i = 0; $i < count($id_karyawan); $i++) {
            $jam_lembur = preg_replace('/\D/', '', $lembur[$i]); // buang " jam" dll

            if ($jam_lembur !== "") {
                $this->Karyawan_model->update_lembur($id_karyawan[$i], $jam_lembur, $today);
            }

        }

        $this->set_output(['status' => 'success', 'message' => 'Lembur berhasil ditambahkan!']);
    }

    public function update_absensi($id_karyawan)
    {
        $id_absensi = $this->input->post('id_absensi');
        $waktu_masuk = $this->input->post('waktu_masuk');
        $waktu_pulang1 = $this->input->post('waktu_pulang');
        $lembur1 = $this->input->post('lembur');


        $tanggal = date('Y-m-d'); // default: hari ini

        if (!empty($waktu_pulang1)) {
            $lembur2 = preg_replace('/\D/', '', $lembur1);
        } else {
            $lembur2 = null;  // biar di DB masuk NULL
        }

        if (!empty($waktu_pulang1)) {
            $waktu_pulang2 = $tanggal . ' ' . $waktu_pulang1 . ':00';
        } else {
            $waktu_pulang2 = null;  // biar di DB masuk NULL
        }

        $data = [
            'waktu_masuk' => $tanggal . ' ' . $waktu_masuk . ':00',   // hasil: Y-m-d H:i:00
            'waktu_pulang' => $waktu_pulang2,
            'lembur' => $lembur2,
        ];

        $this->Karyawan_model->update_absensi($id_absensi, $data);

        echo json_encode(['status' => 'success']);
    }

    public function delete_absensi()
    {
        $id_absensi = $this->input->post('id_absensi');

        $delete = $this->Karyawan_model->delete_absensi($id_absensi);

        $response = array(
            'status'    => 'sukses',
            'pesan'        => 'Berhasil dihapus',
        );
        $this->set_output($response);
    }

    public function modal_add_karyawan()
    {
        $this->load->view('karyawan/karyawan_add_modal');
    }

    public function modal_edit_karyawan($id_karyawan)
    {
        $data['karyawan'] = $this->Karyawan_model->get_karyawan_by_id($id_karyawan);        
        $this->load->view('karyawan/karyawan_edit_modal', $data);
    }

    public function modal_absenmasuk_karyawan()
    {
        $data['karyawan'] = $this->Karyawan_model->get_belum_absen(); 
        $this->load->view('karyawan/karyawan_absenmasuk_modal', $data);
    }

    public function modal_absenpulang_karyawan()
    {        
        $data['karyawan'] = $this->Karyawan_model->get_belum_pulang();
        $this->load->view('karyawan/karyawan_absenpulang_modal', $data);
    }

    public function modal_edit_absensi($id_absensi)
    {
        $data['absensi'] = $this->Karyawan_model->get_absensi_by_id($id_absensi);
        $this->load->view('karyawan/edit_absensi_modal', $data);
    }

    public function modal_lembur()
    {
        $data['karyawan'] = $this->Karyawan_model->get_belum_lembur();

        $this->load->view('karyawan/add_lembur_modal', $data);
    }

}
