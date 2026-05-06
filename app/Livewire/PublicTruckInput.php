<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Tracking;

class PublicTruckInput extends Component
{
    // Jenis kegiatan
    public $type = '';

    // Data perusahaan & kendaraan
    public $company_name;     // Nama Instansi / Vendor
    public $plate_number;     // Nomor Polisi
    public $vehicle_kind;     // Jenis Kendaraan
    public $destination;      // Tujuan

    // Data sopir
    public $driver_name;      // Nama Sopir
    public $driver_phone;     // No HP Sopir
    public $driver_phone_local; // nomor tanpa prefix (input untuk UX, dipetakan ke driver_phone)
    public $driver_identity;  // Identitas (KTP / SIM / dll)

    // Field khusus BONGKAR
    public $sj_number;        // No. Surat Jalan
    public $item_name;        // Nama Barang
    public $item_quantity;    // Jumlah Barang

    // Keterangan
    public $description;

    public function submit()
    {
        // VALIDASI
        $this->validate([
            'type'           => 'required|in:bongkar,muat',
            'company_name'   => 'required|string|max:255',
            'plate_number'   => 'required|string|max:255',
            'driver_name'    => 'required|string|max:255',

            'vehicle_kind'   => 'nullable|string|max:255',
            'destination'    => 'nullable|string|max:255',
            'driver_phone'   => 'nullable|string|max:255',
            'driver_identity'=> 'nullable|string|max:255',

            'sj_number'      => 'nullable|string|max:255',
            'item_name'      => 'nullable|string|max:255',
            'item_quantity'  => 'nullable|string|max:255',

            'description'    => 'nullable|string',
        ]);

        // Untuk MUAT, field khusus BONGKAR dikosongkan
        $sjNumber     = $this->type === 'bongkar' ? $this->sj_number     : null;
        $itemName     = $this->type === 'bongkar' ? $this->item_name     : null;
        $itemQuantity = $this->type === 'bongkar' ? $this->item_quantity : null;

        // SIMPAN KE DATABASE
        Tracking::create([
            // NOTE: vehicle_name tetap diisi, disamakan dengan company_name
            'vehicle_name'      => $this->vehicle_kind,
            'company_name'      => $this->company_name,
            'plate_number'      => $this->plate_number,
            'vehicle_kind'      => $this->vehicle_kind,
            'destination'       => $this->destination,

            'driver_name'       => $this->driver_name,
            'driver_phone'      => $this->driver_phone,
            'driver_identity'   => $this->driver_identity,

            'type'              => $this->type,
            'description'       => $this->description,

            'sj_number'         => $sjNumber,
            'item_name'         => $itemName,
            'item_quantity'     => $itemQuantity,

            'security_start'      => null,
            'security_in_officer' => 'Input Mandiri (Supir)',
            'current_stage'       => 'security_in',
        ]);

        session()->flash('message', 'Data berhasil dikirim! Silakan lapor ke Security.');

        // Reset form (kalau user kembali lagi ke halaman ini)
        $this->reset([
            'type',
            'company_name',
            'plate_number',
            'vehicle_kind',
            'destination',
            'driver_phone_local',
            'driver_name',
            'driver_phone',
            'driver_identity',
            'sj_number',
            'item_name',
            'item_quantity',
            'description',
        ]);
        $this->type = '';

        // Kembali ke halaman utama (login / dashboard)
        return redirect('/');
    }

    public function render()
    {
        return view('livewire.public-truck-input')
            ->layout('layouts.app');
    }

    /**
     * When the user types the local phone part, compose the full driver_phone with country prefix 62.
     * This handler strips non-digit characters and leading zeros from the local input.
     */
    public function updatedDriverPhoneLocal($value)
    {
        $digits = preg_replace('/\D+/', '', $value);
        $digits = ltrim($digits, '0');
        if ($digits === '') {
            $this->driver_phone = null;
        } else {
            $this->driver_phone = '62' . $digits;
        }
    }
}
