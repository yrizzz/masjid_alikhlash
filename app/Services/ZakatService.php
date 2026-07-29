<?php

namespace App\Services;

use App\Models\Setting;

/**
 * Kalkulator zakat. Nisab & harga acuan disimpan di tabel settings agar
 * bendahara bisa memperbarui tanpa menyentuh kode.
 */
class ZakatService
{
    /** Harga emas per gram (Rupiah). */
    public function goldPrice(): float
    {
        return (float) Setting::get('zakat_gold_price', 1_950_000);
    }

    /** Harga beras per kg untuk zakat fitrah. */
    public function ricePrice(): float
    {
        return (float) Setting::get('zakat_rice_price', 14_000);
    }

    /** Nisab = 85 gram emas. */
    public function nisab(): float
    {
        return 85 * $this->goldPrice();
    }

    /** Zakat fitrah: 2,5 kg beras (atau 3,5 liter) per jiwa. */
    public function fitrah(int $people = 1): array
    {
        $kg     = (float) Setting::get('zakat_fitrah_kg', 2.5);
        $perJiwa = $kg * $this->ricePrice();

        return [
            'people'    => $people,
            'kg_total'  => $kg * $people,
            'per_jiwa'  => $perJiwa,
            'amount'    => $perJiwa * $people,
            'note'      => "Setara {$kg} kg beras per jiwa.",
        ];
    }

    /**
     * Zakat maal: harta yang mengendap 1 haul (1 tahun) dan mencapai nisab.
     * 2,5% dari (aset - hutang).
     */
    public function maal(float $assets, float $debts = 0): array
    {
        $net   = max(0, $assets - $debts);
        $nisab = $this->nisab();
        $wajib = $net >= $nisab;

        return [
            'base'   => $net,
            'nisab'  => $nisab,
            'wajib'  => $wajib,
            'amount' => $wajib ? $net * 0.025 : 0,
            'note'   => $wajib
                ? 'Harta Anda mencapai nisab. Zakat 2,5% wajib ditunaikan.'
                : 'Harta belum mencapai nisab ('.number_format($nisab, 0, ',', '.').'). Belum wajib zakat.',
        ];
    }

    /**
     * Zakat profesi: 2,5% dari penghasilan bersih bulanan.
     * Nisab bulanan = nisab tahunan / 12.
     */
    public function profesi(float $monthlyIncome, float $otherIncome = 0, float $needs = 0): array
    {
        $gross      = $monthlyIncome + $otherIncome;
        $net        = max(0, $gross - $needs);
        $nisabMonth = $this->nisab() / 12;
        $wajib      = $net >= $nisabMonth;

        return [
            'base'   => $net,
            'nisab'  => $nisabMonth,
            'wajib'  => $wajib,
            'amount' => $wajib ? $net * 0.025 : 0,
            'note'   => $wajib
                ? 'Penghasilan mencapai nisab bulanan. Zakat 2,5% per bulan.'
                : 'Penghasilan belum mencapai nisab bulanan ('.number_format($nisabMonth, 0, ',', '.').').',
        ];
    }

    /** Zakat emas & perak: 85 gram emas / 595 gram perak, 2,5%. */
    public function emas(float $goldGram, float $silverGram = 0): array
    {
        $silverPrice = (float) Setting::get('zakat_silver_price', 15_000);
        $value       = $goldGram * $this->goldPrice() + $silverGram * $silverPrice;
        $wajib       = $goldGram >= 85 || $silverGram >= 595;

        return [
            'base'   => $value,
            'nisab'  => $this->nisab(),
            'wajib'  => $wajib,
            'amount' => $wajib ? $value * 0.025 : 0,
            'note'   => $wajib
                ? 'Mencapai nisab (85 gr emas / 595 gr perak). Zakat 2,5%.'
                : 'Belum mencapai nisab 85 gram emas atau 595 gram perak.',
        ];
    }

    /** Zakat perdagangan: (modal + laba + piutang - hutang) × 2,5%. */
    public function perdagangan(float $capital, float $profit = 0, float $receivable = 0, float $debts = 0): array
    {
        $net   = max(0, $capital + $profit + $receivable - $debts);
        $nisab = $this->nisab();
        $wajib = $net >= $nisab;

        return [
            'base'   => $net,
            'nisab'  => $nisab,
            'wajib'  => $wajib,
            'amount' => $wajib ? $net * 0.025 : 0,
            'note'   => $wajib
                ? 'Aset usaha mencapai nisab. Zakat 2,5% per haul.'
                : 'Aset usaha belum mencapai nisab ('.number_format($nisab, 0, ',', '.').').',
        ];
    }

    /** Dispatcher generik untuk komponen Livewire. */
    public function calculate(string $type, array $input): array
    {
        return match ($type) {
            'fitrah'      => $this->fitrah((int) ($input['people'] ?? 1)),
            'maal'        => $this->maal((float) ($input['assets'] ?? 0), (float) ($input['debts'] ?? 0)),
            'profesi'     => $this->profesi((float) ($input['income'] ?? 0), (float) ($input['other'] ?? 0), (float) ($input['needs'] ?? 0)),
            'emas'        => $this->emas((float) ($input['gold'] ?? 0), (float) ($input['silver'] ?? 0)),
            'perdagangan' => $this->perdagangan((float) ($input['capital'] ?? 0), (float) ($input['profit'] ?? 0), (float) ($input['receivable'] ?? 0), (float) ($input['debts'] ?? 0)),
            default       => ['base' => 0, 'nisab' => 0, 'wajib' => false, 'amount' => 0, 'note' => ''],
        };
    }
}
