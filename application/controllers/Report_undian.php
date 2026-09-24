<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class Report_undian extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('M_undian');
    }

    // Menampilkan Halaman Report di Admin SB
    public function index() {
        $data['pemenang'] = $this->M_undian->get_all_pemenang();
        $this->load->view('admin/report_undian', $data);
    }

    // Aksi Export Excel via PhpSpreadsheet
    public function export_excel() {
        $pemenang = $this->M_undian->get_all_pemenang();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // 1. Desain Judul Report di Excel
        $sheet->mergeCells('A1:F1');
        $sheet->setCellValue('A1', 'LAPORAN RESMI HASIL UNDIAN PEMENANG');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // 2. Set Header Tabel
        $headers = ['No', 'No Undian', 'NIK', 'Nama Pemenang', 'Kategori Hadiah', 'Status'];
        $kolomHuruf = ['A', 'B', 'C', 'D', 'E', 'F'];
        
        foreach ($headers as $index => $headerTitle) {
            $sheet->setCellValue($kolomHuruf[$index] . '3', $headerTitle);
        }

        // Kustomisasi Gaya Header (Warna Biru SB Admin 2)
        $styleHeader = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4E73DF']], // Warna Primary SB Admin
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
        ];
        $sheet->getStyle('A3:F3')->applyFromArray($styleHeader);

        // 3. Looping Data
        $baris = 4;
        $no = 1;
        foreach ($pemenang as $p) {
            $sheet->setCellValue('A' . $baris, $no++);
            $sheet->setCellValue('B' . $baris, $p->no_undian);
            
            // Kolom NIK di-set sebagai Text agar digit 0 depan aman
            $nik_lengkap = "000" . $p->nik;
            $sheet->setCellValueExplicit('C' . $baris, $nik_lengkap, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            
            $sheet->setCellValue('D' . $baris, $p->name);
            $sheet->setCellValue('E' . $baris, $p->hadiah);
            $sheet->setCellValue('F' . $baris, $p->status);

            // Beri border tipis pada data tabel
            $sheet->getStyle("A$baris:F$baris")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            $baris++;
        }

        // 4. Auto Size Kolom agar tidak terpotong
        foreach ($kolomHuruf as $k) {
            $sheet->getColumnDimension($k)->setAutoSize(true);
        }

        // 5. Kirim data ke browser untuk didownload (.xlsx)
        $nama_file = "Report_Hasil_Undian_" . date('Ymd_His') . ".xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $nama_file . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
