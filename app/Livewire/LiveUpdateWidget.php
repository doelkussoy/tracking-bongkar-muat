<?php
// app/Livewire/LiveUpdateWidget.php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Tracking;
use Illuminate\Support\Collection;

class LiveUpdateWidget extends Component
{
    public Collection $liveRecords;
    public $liveLimit = 2;
    public $liveTotal = 0;
    // search input for filtering live records
    public string $search = '';

    public function mount()
    {
        $this->loadDataForLiveUpdate();
    }

    public function loadDataForLiveUpdate()
    {
        // 1. Ambil data dengan urutan:
        //    - "active" dulu (karena 'completed' = 1, 'active' = 0)
        //    - Lalu urutkan berdasarkan data terbaru
        $query = Tracking::query();

        // jika ada search, filter beberapa kolom yang relevan
        if (!empty(trim($this->search))) {
            $s = "%" . trim($this->search) . "%";
            $query->where(function ($q) use ($s) {
                $q->where('plate_number', 'like', $s)
                  ->orWhere('vehicle_name', 'like', $s)
                  ->orWhere('driver_name', 'like', $s)
                  ->orWhere('description', 'like', $s);
            });
        }

        $this->liveRecords = $query->orderByRaw("CASE WHEN current_stage = 'completed' THEN 1 ELSE 0 END")
                                  ->latest() // 'latest()' adalah 'created_at DESC'
                                  ->take($this->liveLimit)
                                  ->get();
        
        // 2. Hitung total data untuk tombol "Muat Lebih Banyak"
        $this->liveTotal = Tracking::count();
    }

    /**
     * Fungsi baru untuk tombol "Muat Lebih Banyak".
     */
    public function loadMoreLive()
    {
        // Tambah 3 data lagi setiap kali diklik
        $this->liveLimit += 3;
    }

    /**
     * Dipanggil saat user menekan Enter pada input search.
     * Karena input menggunakan wire:model.defer, action ini akan mengirim nilai `search` ke server.
     */
    public function performSearch()
    {
        if (trim($this->search) !== '') {
            $this->liveLimit = 50;
        } else {
            $this->liveLimit = 2;
        }

        $this->loadDataForLiveUpdate();
    }

    /**
     * Clear search dari server side dan reload data.
     */
    public function clearSearch()
    {
        $this->search = '';
        $this->liveLimit = 2;
        $this->loadDataForLiveUpdate();
    }

    /**
     * Dipanggil otomatis oleh Livewire saat properti `search` berubah.
     * Jika ada kata kunci, tingkatkan limit agar hasil pencarian tidak terpotong.
     */
    public function updatedSearch($value)
    {
        if (trim($value) !== '') {
            $this->liveLimit = 50; // tampilkan lebih banyak hasil saat mencari
        } else {
            $this->liveLimit = 2; // kembalikan ke default saat kosong
        }
    }

    public function render()
    {
        // Panggil fungsi ini di render agar wire:poll berfungsi
        $this->loadDataForLiveUpdate();
        return view('livewire.live-update-widget');
    }
}