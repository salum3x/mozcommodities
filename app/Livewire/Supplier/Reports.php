<?php

namespace App\Livewire\Supplier;

use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Url;
use Livewire\Component;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Reports extends Component
{
    #[Url(as: 'period')]
    public string $period = 'month'; // week, month, year, all, custom

    #[Url(as: 'from')]
    public string $dateFrom = '';

    #[Url(as: 'to')]
    public string $dateTo = '';

    public function mount(): void
    {
        if ($this->dateFrom === '') $this->dateFrom = now()->startOfMonth()->toDateString();
        if ($this->dateTo === '')   $this->dateTo   = now()->toDateString();
    }

    public function setPeriod(string $p): void
    {
        $this->period = $p;
        $today = now();
        match ($p) {
            'week'   => [$this->dateFrom = $today->copy()->startOfWeek()->toDateString(), $this->dateTo = $today->copy()->endOfWeek()->toDateString()],
            'month'  => [$this->dateFrom = $today->copy()->startOfMonth()->toDateString(), $this->dateTo = $today->copy()->endOfMonth()->toDateString()],
            'year'   => [$this->dateFrom = $today->copy()->startOfYear()->toDateString(), $this->dateTo = $today->copy()->endOfYear()->toDateString()],
            'all'    => [$this->dateFrom = '2024-01-01', $this->dateTo = $today->toDateString()],
            default  => null,
        };
    }

    protected function range(): array
    {
        $from = $this->dateFrom ? Carbon::parse($this->dateFrom)->startOfDay() : now()->startOfMonth();
        $to   = $this->dateTo   ? Carbon::parse($this->dateTo)->endOfDay()     : now()->endOfDay();
        return [$from, $to];
    }

    public function exportCsv(): ?StreamedResponse
    {
        $supplier = auth()->user()->supplier;
        if (!$supplier) return null;

        [$from, $to] = $this->range();
        $productIds = Product::where('supplier_id', $supplier->id)->pluck('id');
        $rows = OrderItem::whereIn('product_id', $productIds)
            ->whereHas('order', fn ($q) => $q->where('payment_status', 'paid')->whereBetween('created_at', [$from, $to]))
            ->with(['order'])
            ->latest()
            ->get();

        $siteName = Setting::get('site_name', config('app.name', 'MozCommodities'));
        $filename = sprintf('vendas-%s-a-%s.xlsx', $from->toDateString(), $to->toDateString());

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Vendas');

        $sheet->setCellValue('A1', "{$siteName} — {$supplier->company_name}");
        $sheet->mergeCells('A1:G1');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '047857']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(30);

        $sheet->setCellValue('A2', "Período: {$from->format('d/m/Y')} a {$to->format('d/m/Y')} · Apenas pedidos pagos · Gerado a " . now()->format('d/m/Y H:i'));
        $sheet->mergeCells('A2:G2');
        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['size' => 10, 'italic' => true, 'color' => ['rgb' => '6B7280']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        $headers = ['Data', 'Pedido', 'Produto', 'Quantidade', 'Preço (MZN)', 'Subtotal (MZN)', 'Cliente'];
        $sheet->fromArray($headers, null, 'A4');
        $sheet->getStyle('A4:G4')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '065F46']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '047857']]],
        ]);
        $sheet->getRowDimension(4)->setRowHeight(25);

        $r = 5;
        $total = 0;
        foreach ($rows as $item) {
            $sheet->fromArray([
                $item->order?->created_at?->format('d/m/Y H:i'),
                $item->order?->order_number,
                $item->product_name,
                (float) $item->quantity,
                (float) $item->price,
                (float) $item->subtotal,
                $item->order?->customer_name,
            ], null, "A{$r}");
            $total += (float) $item->subtotal;
            if ($r % 2 === 0) {
                $sheet->getStyle("A{$r}:G{$r}")->getFill()
                    ->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F0FDF4');
            }
            $r++;
        }

        $totalRow = $r;
        $sheet->setCellValue("A{$totalRow}", 'TOTAL');
        $sheet->mergeCells("A{$totalRow}:E{$totalRow}");
        $sheet->setCellValue("F{$totalRow}", $total);
        $sheet->getStyle("A{$totalRow}:G{$totalRow}")->applyFromArray([
            'font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '047857']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension($totalRow)->setRowHeight(28);

        $lastDataRow = $r - 1;
        if ($lastDataRow >= 5) {
            $sheet->getStyle("D5:D{$lastDataRow}")->getNumberFormat()->setFormatCode('#,##0.00');
            $sheet->getStyle("E5:F{$lastDataRow}")->getNumberFormat()->setFormatCode('#,##0.00');
            $sheet->getStyle("A5:G{$lastDataRow}")->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'D1D5DB']]],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
            ]);
            $sheet->getStyle("D5:F{$lastDataRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        }
        $sheet->getStyle("F{$totalRow}")->getNumberFormat()->setFormatCode('#,##0.00');

        foreach (['A' => 18, 'B' => 22, 'C' => 30, 'D' => 14, 'E' => 16, 'F' => 18, 'G' => 24] as $col => $w) {
            $sheet->getColumnDimension($col)->setWidth($w);
        }
        $sheet->freezePane('A5');

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function render()
    {
        $supplier = auth()->user()->supplier;
        if (!$supplier) {
            return view('livewire.supplier.reports', [
                'supplier' => null,
                'stats' => ['total_revenue' => 0, 'total_items_sold' => 0, 'total_orders' => 0, 'average_order_value' => 0, 'revenue_change' => 0],
                'topProducts' => collect(),
                'salesByDay' => collect(),
                'recentSales' => collect(),
                'productStats' => ['total_products' => 0, 'active_products' => 0, 'pending_approval' => 0, 'out_of_stock' => 0],
            ])->layout('components.layouts.supplier', ['title' => 'Relatórios']);
        }

        [$from, $to] = $this->range();
        $productIds = Product::where('supplier_id', $supplier->id)->pluck('id');

        $base = OrderItem::whereIn('product_id', $productIds)
            ->whereHas('order', fn ($q) => $q->where('payment_status', 'paid')->whereBetween('created_at', [$from, $to]));

        $stats = [
            'total_revenue'      => (clone $base)->sum('subtotal'),
            'total_items_sold'   => (clone $base)->sum('quantity'),
            'total_orders'       => (clone $base)->distinct('order_id')->count('order_id'),
            'average_order_value' => 0,
        ];
        if ($stats['total_orders'] > 0) {
            $stats['average_order_value'] = $stats['total_revenue'] / $stats['total_orders'];
        }

        // Comparar com período anterior de igual duração
        $duration = $from->diffInSeconds($to);
        $prevTo   = $from->copy()->subSecond();
        $prevFrom = $prevTo->copy()->subSeconds($duration);
        $prevRevenue = OrderItem::whereIn('product_id', $productIds)
            ->whereHas('order', fn ($q) => $q->where('payment_status', 'paid')->whereBetween('created_at', [$prevFrom, $prevTo]))
            ->sum('subtotal');
        $stats['revenue_change'] = $prevRevenue > 0
            ? round((($stats['total_revenue'] - $prevRevenue) / $prevRevenue) * 100, 1)
            : ($stats['total_revenue'] > 0 ? 100 : 0);

        // Top produtos
        $topProducts = OrderItem::whereIn('product_id', $productIds)
            ->whereHas('order', fn ($q) => $q->where('payment_status', 'paid')->whereBetween('created_at', [$from, $to]))
            ->select('product_id', 'product_name', DB::raw('SUM(quantity) as total_quantity'), DB::raw('SUM(subtotal) as total_revenue'))
            ->groupBy('product_id', 'product_name')
            ->orderByDesc('total_revenue')
            ->limit(10)
            ->get();

        // Vendas por dia (range actual)
        $salesByDay = OrderItem::whereIn('product_id', $productIds)
            ->whereHas('order', fn ($q) => $q->where('payment_status', 'paid')->whereBetween('created_at', [$from, $to]))
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->select(
                DB::raw('DATE(orders.created_at) as day'),
                DB::raw('SUM(order_items.subtotal) as total'),
                DB::raw('SUM(order_items.quantity) as qty'),
                DB::raw('COUNT(DISTINCT orders.id) as orders_count')
            )
            ->groupBy('day')
            ->orderBy('day')
            ->get()
            ->map(fn ($r) => [
                'day'    => Carbon::parse($r->day)->format('d/m'),
                'total'  => (float) $r->total,
                'qty'    => (float) $r->qty,
                'orders' => (int) $r->orders_count,
            ]);

        $recentSales = OrderItem::whereIn('product_id', $productIds)
            ->whereHas('order', fn ($q) => $q->where('payment_status', 'paid')->whereBetween('created_at', [$from, $to]))
            ->with(['order', 'product'])
            ->latest()
            ->limit(15)
            ->get();

        $productStats = [
            'total_products'    => Product::where('supplier_id', $supplier->id)->count(),
            'active_products'   => Product::where('supplier_id', $supplier->id)->where('is_active', true)->where('approval_status', 'approved')->count(),
            'pending_approval'  => Product::where('supplier_id', $supplier->id)->where('approval_status', 'pending')->count(),
            'out_of_stock'      => Product::where('supplier_id', $supplier->id)->where('stock_quantity', 0)->count(),
        ];

        return view('livewire.supplier.reports', [
            'supplier' => $supplier,
            'stats' => $stats,
            'topProducts' => $topProducts,
            'salesByDay' => $salesByDay,
            'recentSales' => $recentSales,
            'productStats' => $productStats,
            'fromLabel' => $from->format('d/m/Y'),
            'toLabel' => $to->format('d/m/Y'),
        ])->layout('components.layouts.supplier', ['title' => 'Relatórios']);
    }
}
