<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;
use App\Livewire\Public\Home;
use App\Livewire\Public\Products;
use App\Livewire\Public\ProductDetail;
use App\Livewire\Public\QuoteForm;

// Webhooks de Pagamento (sem CSRF)
Route::prefix('webhooks')->name('webhooks.')->withoutMiddleware(['web'])->group(function () {
    Route::post('/mpesa', [WebhookController::class, 'mpesa'])->name('mpesa');
    Route::post('/emola', [WebhookController::class, 'emola'])->name('emola');
    Route::post('/stripe', [WebhookController::class, 'stripe'])->name('stripe');
});

// Rotas Públicas
Route::get('/', Home::class)->name('home');
Route::get('/produtos', Products::class)->name('products');
Route::get('/nossos-produtos', \App\Livewire\Public\OurProducts::class)->name('our.products');
Route::get('/produtos-fornecedores', \App\Livewire\Public\SupplierProducts::class)->name('public.supplier.products');
Route::get('/categoria/{slug}', \App\Livewire\Public\CategoryProducts::class)->name('category.products');
Route::get('/produto/{slug}', ProductDetail::class)->name('product.detail');
Route::get('/cotacao', QuoteForm::class)->name('quote.form');
Route::get('/solicitar-produto', \App\Livewire\Public\ProductRequest::class)->name('product.request');
Route::get('/carrinho', \App\Livewire\Public\Cart::class)->name('cart');
Route::get('/finalizar-compra', \App\Livewire\Public\CheckoutCart::class)->name('checkout.cart');
Route::get('/comprar/{product_id}', \App\Livewire\Public\Checkout::class)->name('checkout');
Route::get('/pedido/{order}/sucesso', \App\Livewire\Public\OrderSuccess::class)->name('order.success');
Route::get('/sobre-nos', \App\Livewire\Public\About::class)->name('about');

// Dashboard Geral (redireciona baseado no role)
Route::get('/painel', function () {
    $user = auth()->user();

    if ($user->isAdmin()) {
        return redirect()->route('admin.dashboard');
    } elseif ($user->isSupplier()) {
        return redirect()->route('supplier.dashboard');
    } else {
        return redirect()->route('home');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

// Rotas Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/painel', \App\Livewire\Admin\Dashboard::class)->name('dashboard');
    Route::get('/categorias', \App\Livewire\Admin\Categories::class)->name('categories');
    Route::get('/fornecedores', \App\Livewire\Admin\Suppliers::class)->name('suppliers');
    Route::get('/produtos', \App\Livewire\Admin\Products::class)->name('products');
    Route::get('/aprovar-produtos', \App\Livewire\Admin\ProductApprovals::class)->name('approvals');
    Route::get('/solicitacoes', \App\Livewire\Admin\ProductRequests::class)->name('product.requests');
    Route::get('/cotacoes', \App\Livewire\Admin\QuoteRequests::class)->name('quotes');
    Route::get('/anuncios', \App\Livewire\Admin\Announcements::class)->name('announcements');
    Route::get('/configuracoes', \App\Livewire\Admin\Settings::class)->name('settings');
    Route::get('/administradores', \App\Livewire\Admin\Administrators::class)->name('administrators');
    Route::get('/sobre-nos', \App\Livewire\Admin\AboutPage::class)->name('about');
});

// Rotas Fornecedor
Route::middleware(['auth', 'role:supplier'])->prefix('fornecedor')->name('supplier.')->group(function () {
    Route::get('/painel', \App\Livewire\Supplier\Dashboard::class)->name('dashboard');
    Route::get('/meus-produtos', \App\Livewire\Supplier\MyProducts::class)->name('products');
    Route::get('/stock', \App\Livewire\Supplier\StockManagement::class)->name('stock');
    Route::get('/vendas', \App\Livewire\Supplier\MySales::class)->name('sales');
    Route::get('/relatorios', \App\Livewire\Supplier\Reports::class)->name('reports');
});

// Rotas de Perfil
Route::middleware('auth')->group(function () {
    Route::get('/perfil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/perfil', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/perfil', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
