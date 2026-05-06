<?php

namespace App\Services;

use App\Models\InventoryMovement;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportService
{
    /**
     * Get main report data for dashboard reports page.
     */
    public function summary(): array
    {
        $today = Carbon::today();
        $monthStart = Carbon::now()->startOfMonth();
        $monthEnd = Carbon::now()->endOfMonth();

        return [
            'todaySales' => $this->todaySales($today),
            'todayRevenue' => $this->todayRevenue($today),
            'monthlySales' => $this->monthlySales($monthStart, $monthEnd),
            'monthlyRevenue' => $this->monthlyRevenue($monthStart, $monthEnd),
            'totalRevenue' => $this->totalRevenue(),
            'totalOrders' => $this->totalOrders(),
            'lowStockProducts' => $this->lowStockProducts(),
            'bestSellingProducts' => $this->bestSellingProducts(),
            'recentSales' => $this->recentSales(),
            'stockMovementSummary' => $this->stockMovementSummary(),
        ];
    }

    /**
     * Count today's completed sales.
     */
    private function todaySales(Carbon $today): int
    {
        return Sale::whereDate('completed_at', $today)
            ->where('status', 'completed')
            ->count();
    }

    /**
     * Sum today's completed sales revenue.
     */
    private function todayRevenue(Carbon $today): float
    {
        return (float) Sale::whereDate('completed_at', $today)
            ->where('status', 'completed')
            ->sum('total_amount');
    }

    /**
     * Count this month's completed sales.
     */
    private function monthlySales(Carbon $monthStart, Carbon $monthEnd): int
    {
        return Sale::whereBetween('completed_at', [$monthStart, $monthEnd])
            ->where('status', 'completed')
            ->count();
    }

    /**
     * Sum this month's completed sales revenue.
     */
    private function monthlyRevenue(Carbon $monthStart, Carbon $monthEnd): float
    {
        return (float) Sale::whereBetween('completed_at', [$monthStart, $monthEnd])
            ->where('status', 'completed')
            ->sum('total_amount');
    }

    /**
     * Sum all completed sales revenue.
     */
    private function totalRevenue(): float
    {
        return (float) Sale::where('status', 'completed')
            ->sum('total_amount');
    }

    /**
     * Count all completed sales.
     */
    private function totalOrders(): int
    {
        return Sale::where('status', 'completed')->count();
    }

    /**
     * Get low stock products.
     */
    private function lowStockProducts()
    {
        return Product::whereColumn('stock_qty', '<=', 'low_stock_threshold')
            ->orderBy('stock_qty')
            ->take(10)
            ->get();
    }

    /**
     * Get best-selling products by quantity sold.
     */
    private function bestSellingProducts()
    {
        return SaleItem::select(
                'product_id',
                'product_name',
                'product_sku',
                DB::raw('SUM(quantity) as total_quantity'),
                DB::raw('SUM(total_price) as total_sales')
            )
            ->groupBy('product_id', 'product_name', 'product_sku')
            ->orderByDesc('total_quantity')
            ->take(10)
            ->get();
    }

    /**
     * Get latest sales.
     */
    private function recentSales()
    {
        return Sale::with(['user', 'payments'])
            ->latest()
            ->take(10)
            ->get();
    }

    /**
     * Summarize stock movement count by type.
     */
    private function stockMovementSummary()
    {
        return InventoryMovement::select(
                'type',
                DB::raw('COUNT(*) as total_records'),
                DB::raw('SUM(quantity) as total_quantity')
            )
            ->groupBy('type')
            ->get();
    }
}
