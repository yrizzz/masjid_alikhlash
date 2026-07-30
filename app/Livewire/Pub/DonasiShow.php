<?php

namespace App\Livewire\Pub;

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\PaymentChannel;
use Livewire\Component;

class DonasiShow extends Component
{
    public Campaign $campaign;

    /* Formulir donasi */
    public $amount = '';
    public string $name = '';
    public string $phone = '';
    public string $email = '';
    public string $message = '';
    public bool $anonymous = false;
    public ?int $channelId = null;

    public ?Donation $created = null;
    public int $donorsPerPage = 10;

    public function mount(Campaign $campaign): void
    {
        $this->campaign = $campaign;
        $campaign->increment('views');

        if ($user = auth()->user()) {
            $this->name  = $user->name;
            $this->phone = (string) $user->phone;
            $this->email = (string) $user->email;
        }

        $this->channelId = PaymentChannel::active()->value('id');
    }

    public function loadMoreDonors(): void
    {
        $this->donorsPerPage += 10;
    }

    public function pick(int $value): void
    {
        $this->amount = $value;
    }

    public function submit(): void
    {
        $this->validate([
            'amount'    => 'required|numeric|min:1000',
            'name'      => 'required|min:3',
            'phone'     => 'required|min:8',
            'channelId' => 'required|exists:payment_channels,id',
        ], [
            'amount.min' => 'Nominal donasi minimal Rp 1.000.',
        ], [
            'amount' => 'nominal', 'name' => 'nama', 'phone' => 'nomor WhatsApp', 'channelId' => 'kanal pembayaran',
        ]);

        $this->created = Donation::create([
            'campaign_id'        => $this->campaign->id,
            'user_id'            => auth()->id(),
            'payment_channel_id' => $this->channelId,
            'name'               => $this->name,
            'phone'              => $this->phone,
            'email'              => $this->email ?: null,
            'amount'             => $this->amount,
            'message'            => $this->message ?: null,
            'is_anonymous'       => $this->anonymous,
            'type'               => 'infaq',
            'status'             => 'pending',
        ]);

        $this->dispatch('toast', message: 'Donasi tercatat. Silakan selesaikan pembayaran.', variant: 'success');
    }

    public function render()
    {
        $presets = array_filter(array_map('intval', explode(',', (string) setting('donation_presets', '20000,50000,100000,250000,500000'))));
        $allDonations = $this->campaign->donations()->paid()->latest('paid_at');
        $totalDonorsCount = (clone $allDonations)->count();
        $donations = (clone $allDonations)->take($this->donorsPerPage)->get();

        return view('livewire.pub.donasi-show', [
            'donations'        => $donations,
            'totalDonorsCount' => $totalDonorsCount,
            'hasMoreDonors'    => $totalDonorsCount > $this->donorsPerPage,
            'channels'         => PaymentChannel::active()->get(),
            'presets'          => $presets ?: [20000, 50000, 100000, 250000, 500000],
            'updates'          => $this->campaign->updates,
            'others'           => Campaign::active()->where('id', '!=', $this->campaign->id)->take(3)->get(),
        ])->layout('components.layouts.public', [
            'title'       => $this->campaign->title,
            'description' => $this->campaign->excerpt,
        ]);
    }
}
