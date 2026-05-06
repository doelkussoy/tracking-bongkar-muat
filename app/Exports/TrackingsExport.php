<?php

namespace App\Exports;

use App\Models\Tracking;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class TrackingsExport implements FromCollection, WithHeadings, WithEvents
{
    use Exportable;

    protected $search;
    public $start_date;
    public $end_date;

    public function __construct(string $search = null)
    {
        $this->search = $search;
    }

    public function collection()
    {
        $query = Tracking::query();

        // filter tanggal
        if ($this->start_date) {
            $query->whereDate('security_start', '>=', $this->start_date);
        }
        if ($this->end_date) {
            $query->whereDate('security_start', '<=', $this->end_date);
        }

        // filter pencarian
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('vehicle_name', 'like', "%{$this->search}%")
                  ->orWhere('company_name', 'like', "%{$this->search}%")
                  ->orWhere('plate_number', 'like', "%{$this->search}%")
                  ->orWhere('driver_name', 'like', "%{$this->search}%")
                  ->orWhere('type', 'like', "%{$this->search}%");
            });
        }

        $rows = $query->latest()->get();

        // helper tanggal & jam terpisah
        $split = fn($v) => [
            'date' => $v ? $v->format('Y-m-d') : '',
            'time' => $v ? $v->format('H:i') : ''
        ];

        return $rows->map(function ($r) use ($split) {
            $secIn  = $split($r->security_start);
            $secOut = $split($r->security_end);
            $loadS  = $split($r->loading_start);
            $loadE  = $split($r->loading_end);
            $ttbS   = $split($r->ttb_start);
            $ttbE   = $split($r->ttb_end);
            $dist   = $split($r->distribution_at);

            return [
                // Identitas
                // $r->vehicle_name,
                strtoupper($r->type ?? ''),
                $r->company_name,
                $r->plate_number,
                $r->vehicle_kind,
                $r->destination,
                $r->driver_name,
                $r->driver_phone,
                $r->driver_identity,
                $r->description,

                // Surat Jalan / Barang
                $r->sj_number,
                $r->item_name,
                $r->item_quantity,

                // Mobil Masuk (Security)
                $secIn['date'], $secIn['time'], $r->security_in_officer,

                // Bongkar / Muat
                $loadS['date'], $loadS['time'], $r->loading_start_officer,
                $loadE['date'], $loadE['time'], $r->loading_end_officer, $r->keterangan,

                // TTB / SJ
                $ttbS['date'], $ttbS['time'], $r->ttb_start_officer,
                $ttbE['date'], $ttbE['time'], $r->ttb_end_officer, $r->ttb_notes,

                // Distribusi
                $dist['date'], $dist['time'], $r->distribution_officer,

                // Mobil Keluar (Security)
                $secOut['date'], $secOut['time'], $r->security_out_officer,

                    // Ringkasan
                    $this->statusLabel($r->current_stage),
                    $r->created_at?->format('Y-m-d H:i'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            // Identitas
            'Jenis (B/M)','Nama Instansi', 'Plat Nomor', 'Jenis Kendaraan', 'Tujuan',
            'Nama Supir', 'No HP Supir', 'Identitas Supir', 'Keterangan',

            // Surat Jalan / Barang
            'No. Surat Jalan', 'Nama Barang', 'Jumlah Barang',

            // Mobil Masuk (Security)
            'Mobil Masuk - Tanggal', 'Mobil Masuk - Waktu', 'Mobil Masuk - Nama Petugas',

            // Bongkar / Muat
            'Bongkar/Muat Mulai - Tanggal', 'Bongkar/Muat Mulai - Waktu', 'Bongkar/Muat Mulai - Nama', 
            'Bongkar/Muat Selesai - Tanggal', 'Bongkar/Muat Selesai - Waktu', 'Bongkar/Muat Selesai - Nama Petugas', 'Keterangan Tambahan Bongkar/Muat',

            // TTB/SJ
            'TTB/SJ Mulai - Tanggal', 'TTB/SJ Mulai - Waktu', 'TTB/SJ Mulai - Nama Officer',
            'TTB/SJ Selesai - Tanggal', 'TTB/SJ Selesai - Waktu', 'TTB/SJ Selesai - Nama Officer', 'Keterangan Tambahan TTB/SJ',

            // Distribusi
            'Distribusi TTB/SJ - Tanggal', 'Distribusi TTB/SJ - Waktu', 'Distribusi TTB/SJ - Nama Petugas',

            // Mobil Keluar
            'Mobil Keluar - Tanggal', 'Mobil Keluar - Waktu', 'Mobil Keluar - Nama Petugas',

            // Ringkasan
            'Status Terakhir', 'Dibuat Pada',
        ];
    }

    protected function statusLabel(?string $s): string
    {
        return match ($s) {
            'security_in'     => 'Mobil Masuk',
            'loading_started' => 'Proses Bongkar/Muat',
            'loading_ended'   => 'Selesai Bongkar/Muat',
            'ttb_started'     => 'Proses TTB/SJ',
            'ttb_ended'       => 'Selesai TTB/SJ',
            'completed'       => 'Selesai',
            'canceled'        => 'Dibatalkan',
            default           => 'Berlangsung',
        };
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet;
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();
                $range = 'A1:' . $highestColumn . $highestRow;

                // Center alignment for all cells
                $sheet->getStyle($range)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                // Add borders to all cells
                $sheet->getStyle($range)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                // Make header row bold
                $sheet->getStyle('A1:' . $highestColumn . '1')->getFont()->setBold(true);
            },
        ];
    }
}
