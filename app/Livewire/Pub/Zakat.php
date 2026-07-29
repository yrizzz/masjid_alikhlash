<?php

namespace App\Livewire\Pub;

use App\Models\ZakatPayment;
use App\Services\ZakatService;
use Livewire\Component;

class Zakat extends Component
{
    public string $type = 'fitrah';

    /** Input mentah kalkulator per jenis zakat. */
    public array $input = [
        'people' => 1, 'assets' => '', 'debts' => '', 'income' => '',
        'other' => '', 'needs' => '', 'gold' => '', 'silver' => '',
        'capital' => '', 'profit' => '', 'receivable' => '',
    ];

    public array $result = [];

    /* Form penyaluran */
    public bool $showPay = false;
    public string $name = '';
    public string $phone = '';
    public ?ZakatPayment $created = null;

    public function mount(): void
    {
        if ($user = auth()->user()) {
            $this->name  = $user->name;
            $this->phone = (string) $user->phone;
        }

        $this->calculate();
    }

    public function updatedType(): void
    {
        $this->calculate();
    }

    public function calculate(): void
    {
        $this->result = app(ZakatService::class)->calculate($this->type, $this->input);
    }

    public function pay(): void
    {
        $this->validate([
            'name'  => 'required|min:3',
            'phone' => 'required|min:8',
        ], [], ['name' => 'nama', 'phone' => 'nomor WhatsApp']);

        abort_if(($this->result['amount'] ?? 0) <= 0, 422);

        $this->created = ZakatPayment::create([
            'user_id'     => auth()->id(),
            'name'        => $this->name,
            'phone'       => $this->phone,
            'type'        => $this->type,
            'base_amount' => $this->result['base'] ?? ($this->result['per_jiwa'] ?? 0),
            'amount'      => $this->result['amount'],
            'people'      => (int) ($this->input['people'] ?: 1),
            'status'      => 'pending',
        ]);

        $this->showPay = false;
        $this->dispatch('toast', message: 'Data zakat tercatat. Silakan selesaikan pembayaran.', variant: 'success');
    }

    public function render()
    {
        $service = app(ZakatService::class);

        return view('livewire.pub.zakat', [
            'nisab'      => $service->nisab(),
            'goldPrice'  => $service->goldPrice(),
            'ricePrice'  => $service->ricePrice(),
            'types'      => ZakatPayment::TYPES,
            'channels'   => \App\Models\PaymentChannel::active()->get(),
        ])->layout('components.layouts.public', ['title' => 'Kalkulator Zakat']);
    }
}
