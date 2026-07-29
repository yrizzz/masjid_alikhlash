<?php

namespace App\Livewire\Admin;

use App\Models\Setting;
use Livewire\Component;

class Settings extends Component
{
    /** grup => [key => [label, tipe, keterangan]] */
    public const GROUPS = [
        'Umum' => [
            'site_title'       => ['Judul Website', 'text', ''],
            'site_description' => ['Deskripsi (SEO)', 'textarea', 'Tampil di hasil pencarian Google.'],
            'logo'             => ['URL Logo', 'text', ''],
            'timezone'         => ['Zona Waktu', 'text', 'Contoh: Asia/Jakarta'],
        ],
        'Donasi & Zakat' => [
            'donation_presets'   => ['Nominal Cepat Donasi', 'text', 'Pisahkan dengan koma, contoh: 20000,50000,100000'],
            'zakat_gold_price'   => ['Harga Emas per Gram', 'number', 'Dipakai menghitung nisab (85 gr emas).'],
            'zakat_silver_price' => ['Harga Perak per Gram', 'number', ''],
            'zakat_rice_price'   => ['Harga Beras per Kg', 'number', 'Dipakai menghitung zakat fitrah.'],
            'zakat_fitrah_kg'    => ['Beras Zakat Fitrah (kg/jiwa)', 'number', 'Umumnya 2,5 kg.'],
        ],
        'Notifikasi' => [
            'wa_number'      => ['Nomor WhatsApp Admin', 'text', 'Format 62xxx — untuk konfirmasi donasi.'],
            'telegram_token' => ['Token Bot Telegram', 'text', ''],
            'notify_email'   => ['Email Penerima Notifikasi', 'text', ''],
        ],
        'Sosial Media' => [
            'facebook'  => ['Facebook', 'text', ''],
            'instagram' => ['Instagram', 'text', ''],
            'youtube'   => ['YouTube', 'text', ''],
            'tiktok'    => ['TikTok', 'text', ''],
        ],
    ];

    public array $form = [];

    public function mount(): void
    {
        foreach (self::GROUPS as $fields) {
            foreach ($fields as $key => $meta) {
                $this->form[$key] = (string) Setting::get($key, '');
            }
        }
    }

    public function save(): void
    {
        foreach ($this->form as $key => $value) {
            Setting::put($key, $value, 'umum');
        }

        $this->dispatch('toast', message: 'Pengaturan disimpan.', variant: 'success');
    }

    public function render()
    {
        return view('livewire.admin.settings', ['groups' => self::GROUPS])
            ->layout('components.layouts.app', [
                'title'       => 'Pengaturan',
                'breadcrumbs' => [['label' => 'Sistem'], ['label' => 'Pengaturan']],
            ]);
    }
}
