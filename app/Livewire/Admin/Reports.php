<?php

namespace App\Livewire\Admin;

use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Supplier;
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
    #[Url(as: 'from')]
    public string $dateFrom = '';

    #[Url(as: 'to')]
    public string $dateTo = '';

    #[Url(as: 'supplier')]
    public ?int $supplierId = null;

    public function mount(): void
    {
        $this->ensureAdmin();
        if ($this->dateFrom === '') $this->dateFrom = now()->startOfMonth()->toDateString();
        if ($this->dateTo === '')   $this->dateTo   = now()->toDateString();
    }

    public function setPeriod(string $p): void
    {
        $today = now();
        match ($p) {
            'week'  => [$this->dateFrom = $today->copy()->startOfWeek()->toDateString(), $this->dateTo = $today->copy()->endOfWeek()->toDateString()],
            'month' => [$this->dateFrom = $today->copy()->startOfMonth()->toDateString(), $this->dateTo = $today->copy()->endOfMonth()->toDateString()],
            'year'  => [$this->dateFrom = $today->copy()->startOfYear()->toDateString(), $this->dateTo = $today->copy()->endOfYear()->toDateString()],
            default => null,
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
        [$from, $to] = $this->range();

        $rows = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->leftJoin('products', 'order_items.product_id', '=', 'products.id')
            ->leftJoin('suppliers', 'products.supplier_id', '=', 'suppliers.id')
            ->where('orders.payment_status', 'paid')
            ->whereBetween('orders.created_at', [$from, $to])
            ->when($this->supplierId, fn ($q) => $q->where('products.supplier_id', $this->supplierId))
            ->select(
                'orders.created_at as order_date',
                'orders.order_number',
                'suppliers.company_name as supplier',
                'order_items.product_name',
                'order_items.quantity',
                'order_items.price',
                'order_items.subtotal',
                'orders.customer_name'
            )
            ->orderBy('orders.created_at', 'desc')
            ->get();

        $siteName = Setting::get('site_name', config('app.name', 'MozCommodities'));
        $filename = sprintf('relatorio-vendas-%s-a-%s.xlsx', $from->toDateString(), $to->toDateString());

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Vendas');

        // Title
        $sheet->setCellValue('A1', "{$siteName} — Relatório de Vendas");
        $sheet->mergeCells('A1:H1');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '047857']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(30);

        // Subtitle
        $sheet->setCellValue('A2', "Período: {$from->format('d/m/Y')} a {$to->format('d/m/Y')} · Apenas pedidos pagos · Gerado a " . now()->format('d/m/Y H:i'));
        $sheet->mergeCells('A2:H2');
        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['size' => 10, 'italic' => true, 'color' => ['rgb' => '6B7280']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // Headers
        $headers = ['Data', 'Pedido', 'Fornecedor', 'Produto', 'Quantidade', 'Preço (MZN)', 'Subtotal (MZN)', 'Cliente'];
        $sheet->fromArray($headers, null, 'A4');
        $sheet->getStyle('A4:H4')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '065F46']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '047857']]],
        ]);
        $sheet->getRowDimension(4)->setRowHeight(25);

        // Data rows
        $row = 5;
        $totalSubtotal = 0;
        foreach ($rows as $r) {
            $sheet->fromArray([
                Carbon::parse($r->order_date)->format('d/m/Y H:i'),
                $r->order_number,
                $r->supplier ?? 'Próprio',
                $r->product_name,
                (float) $r->quantity,
                (float) $r->price,
                (float) $r->subtotal,
                $r->customer_name,
            ], null, "A{$row}");
            $totalSubtotal += (float) $r->subtotal;

            if ($row % 2 === 0) {
                $sheet->getStyle("A{$row}:H{$row}")->getFill()
                    ->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F0FDF4');
            }
            $row++;
        }

        // Totals row
        $totalRow = $row;
        $sheet->setCellValue("A{$totalRow}", 'TOTAL');
        $sheet->mergeCells("A{$totalRow}:F{$totalRow}");
        $sheet->setCellValue("G{$totalRow}", $totalSubtotal);
        $sheet->getStyle("A{$totalRow}:H{$totalRow}")->applyFromArray([
            'font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '047857']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension($totalRow)->setRowHeight(28);

        // Number formats
        $lastDataRow = $row - 1;
        if ($lastDataRow >= 5) {
            $sheet->getStyle("E5:E{$lastDataRow}")->getNumberFormat()->setFormatCode('#,##0.00');
            $sheet->getStyle("F5:G{$lastDataRow}")->getNumberFormat()->setFormatCode('#,##0.00');
        }
        $sheet->getStyle("G{$totalRow}")->getNumberFormat()->setFormatCode('#,##0.00');

        // Borders + alignment for data
        if ($lastDataRow >= 5) {
            $sheet->getStyle("A5:H{$lastDataRow}")->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'D1D5DB']]],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
            ]);
            $sheet->getStyle("E5:G{$lastDataRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        }

        // Column widths
        foreach (['A' => 18, 'B' => 22, 'C' => 24, 'D' => 28, 'E' => 14, 'F' => 16, 'G' => 18, 'H' => 24] as $col => $w) {
            $sheet->getColumnDimension($col)->setWidth($w);
        }

        // Freeze header rows
        $sheet->freezePane('A5');

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    protected function ensureAdmin(): void
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') abort(403);
    }

    public function render()
    {
        [$from, $to] = $this->range();

        // Vendas por fornecedor
        $bySupplier = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->leftJoin('products', 'order_items.product_id', '=', 'products.id')
            ->leftJoin('suppliers', 'products.supplier_id', '=', 'suppliers.id')
            ->where('orders.payment_status', 'paid')
            ->whereBetween('orders.created_at', [$from, $to])
            ->select(
                DB::raw('COALESCE(suppliers.id, 0) as supplier_id'),
                DB::raw("COALESCE(suppliers.company_name, 'Produto próprio') as supplier_name"),
                DB::raw('SUM(order_items.subtotal) as total_revenue'),
                DB::raw('SUM(order_items.quantity) as total_qty'),
                DB::raw('COUNT(DISTINCT orders.id) as orders_count'),
                DB::raw('COUNT(DISTINCT order_items.product_id) as products_sold')
            )
            ->groupBy('supplier_id', 'supplier_name')
            ->orderByDesc('total_revenue')
            ->get();

        $totals = [
            'revenue'  => $bySupplier->sum('total_revenue'),
            'qty'      => $bySupplier->sum('total_qty'),
            'orders'   => $bySupplier->sum('orders_count'),
            'suppliers'=> $bySupplier->where('supplier_id', '!=', 0)->count(),
        ];

        // Top produtos vendidos (geral ou filtrado por fornecedor)
        $topProducts = OrderItem::whereHas('order', fn ($q) => $q->where('payment_status', 'paid')->whereBetween('created_at', [$from, $to]))
            ->when($this->supplierId, function ($q) {
                $productIds = Product::where('supplier_id', $this->supplierId)->pluck('id');
                $q->whereIn('product_id', $productIds);
            })
            ->select(
                'product_id',
                'product_name',
                DB::raw('SUM(quantity) as total_qty'),
                DB::raw('SUM(subtotal) as total_revenue'),
                DB::raw('COUNT(DISTINCT order_id) as orders_count'),
                DB::raw('AVG(price) as avg_price')
            )
            ->groupBy('product_id', 'product_name')
            ->orderByDesc('total_revenue')
            ->limit(20)
            ->get();

        // Vendas por categoria
        $byCategory = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->leftJoin('products', 'order_items.product_id', '=', 'products.id')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->where('orders.payment_status', 'paid')
            ->whereBetween('orders.created_at', [$from, $to])
            ->when($this->supplierId, fn ($q) => $q->where('products.supplier_id', $this->supplierId))
            ->select(
                DB::raw("COALESCE(categories.name, 'Sem categoria') as category"),
                DB::raw('SUM(order_items.subtotal) as total_revenue'),
                DB::raw('SUM(order_items.quantity) as total_qty')
            )
            ->groupBy('category')
            ->orderByDesc('total_revenue')
            ->get();

        // Status dos pedidos no período
        $byStatus = DB::table('orders')
            ->whereBetween('created_at', [$from, $to])
            ->select('payment_status', DB::raw('COUNT(*) as cnt'), DB::raw('SUM(total) as total'))
            ->groupBy('payment_status')
            ->get()
            ->keyBy('payment_status');

        // Detalhe quando um fornecedor está selecionado
        $detail = null;
        if ($this->supplierId) {
            $supplier = Supplier::find($this->supplierId);
            $productIds = Product::where('supplier_id', $this->supplierId)->pluck('id');
            $byDay = OrderItem::whereIn('product_id', $productIds)
                ->whereHas('order', fn ($q) => $q->where('payment_status', 'paid')->whereBetween('created_at', [$from, $to]))
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->select(
                    DB::raw('DATE(orders.created_at) as day'),
                    DB::raw('SUM(order_items.subtotal) as total'),
                    DB::raw('SUM(order_items.quantity) as qty'),
                    DB::raw('COUNT(DISTINCT orders.id) as orders_count')
                )
                ->groupBy('day')->orderBy('day')->get()
                ->map(fn ($r) => [
                    'day' => Carbon::parse($r->day)->format('d/m'),
                    'total' => (float) $r->total,
                    'qty' => (float) $r->qty,
                    'orders' => (int) $r->orders_count,
                ]);
            $detail = ['supplier' => $supplier, 'byDay' => $byDay];
        }

        $suppliers = Supplier::orderBy('company_name')->get();

        return view('livewire.admin.reports', [
            'bySupplier'   => $bySupplier,
            'topProducts'  => $topProducts,
            'byCategory'   => $byCategory,
            'byStatus'     => $byStatus,
            'totals'       => $totals,
            'detail'       => $detail,
            'suppliers'    => $suppliers,
            'fromLabel'    => $from->format('d/m/Y'),
            'toLabel'      => $to->format('d/m/Y'),
        ])->layout('components.layouts.admin');
    }
}
