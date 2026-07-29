<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

/** Unduh laporan sebagai CSV (dapat dibuka langsung di Excel). */
class ExportController extends Controller
{
    public function __invoke(Request $request, string $type): StreamedResponse
    {
        [$name, $headers, $rows] = match ($type) {
            'keuangan' => $this->finance($request),
            'donasi'   => $this->donations($request),
            default    => abort(404),
        };

        return response()->streamDownload(function () use ($headers, $rows) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // BOM agar Excel membaca UTF-8
            fputcsv($out, $headers, ';');
            foreach ($rows as $row) {
                fputcsv($out, $row, ';');
            }
            fclose($out);
        }, $name.'-'.now()->format('Ymd-Hi').'.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    protected function finance(Request $request): array
    {
        $query = Transaction::with(['category', 'account'])->approved()->orderBy('date');

        if ($from = $request->query('from')) {
            $query->whereDate('date', '>=', $from);
        }
        if ($to = $request->query('to')) {
            $query->whereDate('date', '<=', $to);
        }

        $rows = $query->get()->map(fn ($t) => [
            $t->date->format('Y-m-d'),
            $t->code,
            $t->type === 'in' ? 'Pemasukan' : 'Pengeluaran',
            $t->category?->name ?? '-',
            $t->account?->name ?? '-',
            $t->description,
            (float) $t->amount,
        ]);

        return ['laporan-keuangan', ['Tanggal', 'Kode', 'Jenis', 'Kategori', 'Rekening', 'Keterangan', 'Nominal'], $rows];
    }

    protected function donations(Request $request): array
    {
        $rows = Donation::with('campaign')->latest()->get()->map(fn ($d) => [
            $d->created_at->format('Y-m-d H:i'),
            $d->code,
            $d->display_name,
            $d->campaign?->title ?? 'Donasi Umum',
            $d->type,
            (float) $d->amount,
            $d->status,
        ]);

        return ['laporan-donasi', ['Waktu', 'Kode', 'Donatur', 'Campaign', 'Jenis', 'Nominal', 'Status'], $rows];
    }
}
