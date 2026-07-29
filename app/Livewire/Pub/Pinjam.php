<?php

namespace App\Livewire\Pub;

use App\Models\Inventory;
use App\Models\InventoryLoan;
use Livewire\Component;

class Pinjam extends Component
{
    public ?int $inventoryId = null;
    public string $borrower = '';
    public string $phone = '';
    public int $quantity = 1;
    public string $borrowDate = '';
    public string $dueDate = '';
    public string $purpose = '';

    public ?InventoryLoan $created = null;

    public function mount(): void
    {
        $this->borrowDate = today()->addDay()->format('Y-m-d');
        $this->dueDate    = today()->addDays(3)->format('Y-m-d');

        if ($user = auth()->user()) {
            $this->borrower = $user->name;
            $this->phone    = (string) $user->phone;
        }
    }

    public function submit(): void
    {
        $this->validate([
            'inventoryId' => 'required|exists:inventories,id',
            'borrower'    => 'required|min:3',
            'phone'       => 'required|min:8',
            'quantity'    => 'required|integer|min:1',
            'borrowDate'  => 'required|date|after_or_equal:today',
            'dueDate'     => 'required|date|after_or_equal:borrowDate',
            'purpose'     => 'required|min:5',
        ], [], [
            'inventoryId' => 'barang', 'borrower' => 'nama', 'phone' => 'nomor WhatsApp',
            'quantity' => 'jumlah', 'borrowDate' => 'tanggal pinjam', 'dueDate' => 'tanggal kembali', 'purpose' => 'keperluan',
        ]);

        $item = Inventory::findOrFail($this->inventoryId);

        if ($this->quantity > $item->quantity) {
            $this->addError('quantity', 'Jumlah melebihi stok tersedia ('.$item->quantity.').');

            return;
        }

        $this->created = InventoryLoan::create([
            'inventory_id' => $item->id,
            'user_id'      => auth()->id(),
            'borrower'     => $this->borrower,
            'phone'        => $this->phone,
            'quantity'     => $this->quantity,
            'borrow_date'  => $this->borrowDate,
            'due_date'     => $this->dueDate,
            'purpose'      => $this->purpose,
            'status'       => 'pending',
        ]);

        $this->dispatch('toast', message: 'Permohonan peminjaman terkirim.', variant: 'success');
    }

    public function render()
    {
        return view('livewire.pub.pinjam', [
            'items' => Inventory::where('is_lendable', true)->with('category')->orderBy('name')->get(),
        ])->layout('components.layouts.public', ['title' => 'Peminjaman Inventaris']);
    }
}
