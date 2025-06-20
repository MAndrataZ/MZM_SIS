<?php

namespace App\Filament\Widgets;

use App\Models\DetailTransaksi;
use App\Models\Transaksi;
use Carbon\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class StatsOverviewWidget extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 0;

    protected function getStats(): array
    {

        $startDate = ! is_null($this->filters['startDate'] ?? null) ?
            Carbon::parse($this->filters['startDate']) :
            null;

        $endDate = ! is_null($this->filters['endDate'] ?? null) ?
            Carbon::parse($this->filters['endDate']) :
            now();


        $diffInDays = $startDate ? $startDate->diffInDays($endDate) : 0;

        $pendapatan = $startDate
            ? Transaksi::whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->sum('total_pendapatan')
            : Transaksi::sum('total_pendapatan');


        $produk_terlaris = $startDate && $endDate
            ? DetailTransaksi::whereHas('transaksi', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()]);
            })
            ->select('nama_produk')
            ->selectRaw('SUM(jumlah) as total_terjual')
            ->groupBy('nama_produk')
            ->orderByDesc('total_terjual')
            ->first()
            : DetailTransaksi::select('nama_produk')
            ->selectRaw('SUM(jumlah) as total_terjual')
            ->groupBy('nama_produk')
            ->orderByDesc('total_terjual')
            ->first();

        $total_transaksi = $startDate && $endDate
            ? Transaksi::whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])->count()
            : Transaksi::count();

        $formatNumber = function (int $number): string {
            if ($number < 1000) {
                return (string) Number::format($number, 0);
            }

            if ($number < 1000000) {
                return Number::format($number / 1000, 2) . 'Ribu';
            }

            return Number::format($number / 1000000, 2) . ' Juta';
        };

        return [
            Stat::make('Pendapatan', 'Rp ' . $formatNumber($pendapatan))
                // ->description('32k increase')
                // ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),
            Stat::make('Produk Terlaris', $produk_terlaris->nama_produk ?? '-')
                ->description(($produk_terlaris->total_terjual ?? 0) . ' Produk Terjual')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('amber'),
            Stat::make('Total Transaksi', $total_transaksi ?? 0)
                // ->description('7% increase')
                // ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([15, 4, 10, 2, 12, 4, 12])
                ->color('success'),
        ];
    }
}
