<?php

namespace App\Exports;

use App\Models\User;
use App\Models\LichSuDiem;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BaoCaoLopExport implements FromQuery, WithHeadings, WithColumnWidths, WithStyles
{
    /**
     * Thông số lọc
     */
    protected $lop;
    protected $fromDate;
    protected $toDate;

    /**
     * Constructor
     */
    public function __construct($lop, $fromDate = null, $toDate = null)
    {
        $this->lop = $lop;
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
    }

    /**
     * Query lấy dữ liệu sinh viên theo lớp
     * LEFT JOIN lich_su_diem để tính tổng điểm
     */
    public function query()
    {
        return User::query()
            ->where('lop', $this->lop)
            ->where('vai_tro', 'sinh_vien')
            ->leftJoin('lich_su_diem', 'nguoi_dung.ma_nguoi_dung', '=', 'lich_su_diem.ma_nguoi_dung')
            // Filter by date range nếu có
            ->when($this->fromDate && $this->toDate, function ($query) {
                $query->whereBetween('lich_su_diem.thoi_gian_ghi_nhan', [
                    $this->fromDate . ' 00:00:00',
                    $this->toDate . ' 23:59:59'
                ]);
            })
            ->select(
                'nguoi_dung.ma_nguoi_dung as ma_sinh_vien',
                'nguoi_dung.ho_ten',
                'nguoi_dung.lop',
                \DB::raw('COALESCE(SUM(lich_su_diem.diem), 0) as tong_diem')
            )
            ->groupBy(
                'nguoi_dung.ma_nguoi_dung',
                'nguoi_dung.ho_ten',
                'nguoi_dung.lop'
            )
            ->orderBy('nguoi_dung.ho_ten', 'ASC');
    }

    /**
     * Header hàng đầu tiên
     */
    public function headings(): array
    {
        return [
            'Mã Sinh Viên',
            'Tên Sinh Viên',
            'Lớp',
            'Tổng Điểm',
            'Từ Ngày',
            'Đến Ngày',
            'Thời Gian Xuất',
        ];
    }

    /**
     * Chiều rộng cột
     */
    public function columnWidths(): array
    {
        return [
            'A' => 15,  // Mã Sinh Viên
            'B' => 25,  // Tên Sinh Viên
            'C' => 15,  // Lớp
            'D' => 12,  // Tổng Điểm
            'E' => 12,  // Từ Ngày
            'F' => 12,  // Đến Ngày
            'G' => 18,  // Thời Gian Xuất
        ];
    }

    /**
     * Styling (bold header, background, center align)
     */
    public function styles(Worksheet $sheet)
    {
        // Lấy dữ liệu để biết số hàng cuối
        $rowCount = $this->query()->count();

        // Format header (row 1)
        $sheet->getStyle('A1:G1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => '002060'], // Dark blue text
                'size' => 11,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'D9E1F2'], // Light blue background
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
        ]);

        // Center align columns C-G (Lớp, Tổng Điểm, Từ Ngày, Đến Ngày, Thời Gian Xuất)
        if ($rowCount > 0) {
            $lastRow = $rowCount + 1; // +1 vì hàng 1 là header
            $sheet->getStyle('C2:G' . $lastRow)->applyFromArray([
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ]);
        }

        // Auto filter trên header
        $sheet->setAutoFilter('A1:G1');

        return [];
    }

    /**
     * Thêm dữ liệu date range vào mỗi row
     * Laravel Excel sẽ gọi method này để map dữ liệu vào row
     */
    public function map($row): array
    {
        // Format ngày theo d/m/Y
        $fromDateFormatted = $this->fromDate
            ? \Carbon\Carbon::createFromFormat('Y-m-d', $this->fromDate)->format('d/m/Y')
            : '';

        $toDateFormatted = $this->toDate
            ? \Carbon\Carbon::createFromFormat('Y-m-d', $this->toDate)->format('d/m/Y')
            : '';

        $exportTime = now()->format('d/m/Y H:i:s');

        return [
            $row['ma_sinh_vien'],
            $row['ho_ten'],
            $row['lop'],
            (int)$row['tong_diem'],
            $fromDateFormatted,
            $toDateFormatted,
            $exportTime,
        ];
    }
}
