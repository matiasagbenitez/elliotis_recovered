<?php

use App\Http\Livewire\Tests\Test;
use App\Http\Livewire\Lots\LotsIndex;
use App\Http\Livewire\Sales\ShowSale;
use App\Http\Livewire\Tasks\ShowTask;
use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Areas\IndexAreas;
use App\Http\Livewire\Sales\CreateSale;
use App\Http\Livewire\Sales\IndexSales;
use App\Http\Livewire\Tasks\IndexTasks;
use App\Http\Livewire\Tests\TestOrders;
use App\Http\Livewire\Lots\SublotsAreas;
use App\Http\Livewire\Lots\SublotsIndex;
use App\Http\Livewire\Tasks\ManageTasks;
use App\Http\Livewire\Clients\EditClient;
use App\Http\Livewire\Phases\IndexPhases;
use App\Http\Livewire\Tasks\RegisterTask;
use App\Http\Livewire\Clients\CreateClient;
use App\Http\Livewire\Clients\IndexClients;
use App\Http\Livewire\Lots\SublotsProducts;
use App\Http\Livewire\Unities\IndexUnities;
use App\Http\Livewire\Ranking\PruebaRanking;
use App\Http\Livewire\IvaTypes\IndexIvaTypes;
use App\Http\Livewire\Measures\IndexMeasures;
use App\Http\Livewire\Products\IndexProducts;
use App\Http\Livewire\Purchases\ShowPurchase;
use App\Http\Livewire\Suppliers\EditSupplier;
use App\Http\Livewire\Countries\IndexCountries;
use App\Http\Livewire\Provinces\IndexProvinces;
use App\Http\Livewire\Purchases\CreatePurchase;
use App\Http\Livewire\Purchases\IndexPurchases;
use App\Http\Livewire\SaleOrders\ShowSaleOrder;
use App\Http\Livewire\Suppliers\CreateSupplier;
use App\Http\Livewire\Suppliers\IndexSuppliers;
use App\Http\Livewire\Tenderings\ShowTendering;
use App\Http\Livewire\TrunkLots\IndexTrunkLots;
use App\Http\Livewire\WoodTypes\IndexWoodTypes;
use App\Http\Livewire\Localities\IndexLocalities;
use App\Http\Livewire\Parameters\IndexParameters;
use App\Http\Livewire\SaleOrders\CreateSaleOrder;
use App\Http\Livewire\SaleOrders\SaleOrdersIndex;
use App\Http\Livewire\Tenderings\CreateTendering;
use App\Http\Livewire\Tenderings\IndexTenderings;
use App\Http\Livewire\Products\AddProductsComponent;
use App\Http\Livewire\Tenderings\ShowOfferTendering;
use App\Http\Livewire\ProductNames\IndexProductNames;
use App\Http\Livewire\ProductTypes\IndexProductTypes;
use App\Http\Livewire\TaskStatuses\IndexTaskStatuses;
use App\Http\Livewire\TypesOfTasks\IndexTypesOfTasks;
use App\Http\Livewire\IvaConditions\IndexIvaConditions;
use App\Http\Livewire\PurchaseOrders\ShowPurchaseOrder;
use App\Http\Livewire\Tenderings\ShowFinishedTendering;
use App\Http\Livewire\PurchaseOrders\CreatePurchaseOrder;
use App\Http\Livewire\PurchaseOrders\PurchaseOrdersIndex;
use App\Http\Livewire\PreviousProducts\IndexPreviousProducts;
use App\Http\Livewire\FollowingProducts\IndexFollowingProducts;
use App\Http\Livewire\PucharseParameters\IndexPucharseParameters;

Route::get('/countries', IndexCountries::class)->name('admin.countries.index');
Route::get('/provinces', IndexProvinces::class)->name('admin.provinces.index');
Route::get('/localities', IndexLocalities::class)->name('admin.localities.index');

Route::get('/iva-conditions', IndexIvaConditions::class)->name('admin.iva-conditions.index');
Route::get('/iva-types', IndexIvaTypes::class)->name('admin.iva-types.index');
Route::get('/pucharse-parameters', IndexPucharseParameters::class)->name('admin.pucharse-parameters.index');

Route::get('/clients', IndexClients::class)->name('admin.clients.index');
Route::get('/clients/create', CreateClient::class)->name('admin.clients.create');
Route::get('/clients/{client}/edit', EditClient::class)->name('admin.clients.edit');

Route::get('/suppliers', IndexSuppliers::class)->name('admin.suppliers.index');
Route::get('/suppliers/create', CreateSupplier::class)->name('admin.suppliers.create');
Route::get('/suppliers/{supplier}/edit', EditSupplier::class)->name('admin.suppliers.edit');

Route::get('/measures', IndexMeasures::class)->name('admin.measures.index');
Route::get('/unities', IndexUnities::class)->name('admin.unities.index');
Route::get('/product-names', IndexProductNames::class)->name('admin.product-names.index');
Route::get('/wood-types', IndexWoodTypes::class)->name('admin.wood-types.index');
Route::get('/product-types', IndexProductTypes::class)->name('admin.product-types.index');

Route::get('/products', IndexProducts::class)->name('admin.products.index');
Route::get('/add-products', AddProductsComponent::class)->name('admin.add-products.index');

Route::get('/areas', IndexAreas::class)->name('admin.areas.index');

Route::get('/purchases', IndexPurchases::class)->name('admin.purchases.index');
Route::get('/purchase/create', CreatePurchase::class)->name('admin.purchases.create');
Route::get('/purchase/{purchase}/detail', ShowPurchase::class)->name('admin.purchases.show-detail');

Route::get('/sales', IndexSales::class)->name('admin.sales.index');
Route::get('/sale/create', CreateSale::class)->name('admin.sales.create');
Route::get('/sale/{sale}/detail', ShowSale::class)->name('admin.sales.show-detail');

Route::get('/sale-orders', SaleOrdersIndex::class)->name('admin.sale-orders.index');
Route::get('/sale-order/create', CreateSaleOrder::class)->name('admin.sale-orders.create');
Route::get('/sale-order/{saleOrder}/detail', ShowSaleOrder::class)->name('admin.sale-orders.show-detail');

Route::get('/purchase-orders', PurchaseOrdersIndex::class)->name('admin.purchase-orders.index');
Route::get('/purchase-order/create', CreatePurchaseOrder::class)->name('admin.purchase-orders.create');
Route::get('/purchase-order/{purchaseOrder}/detail', ShowPurchaseOrder::class)->name('admin.purchase-orders.show-detail');

Route::get('/tenderings', IndexTenderings::class)->name('admin.tenderings.index');
Route::get('/tendering/create', CreateTendering::class)->name('admin.tenderings.create');
Route::get('/tendering/{tendering}/detail', ShowTendering::class)->name('admin.tenderings.show-detail');
Route::get('/tendering/{tendering}/hash/{hash:hash}/detail', ShowOfferTendering::class)->name('admin.tenderings.show-offer-detail');
Route::get('/tendering/{tendering}/finished', ShowFinishedTendering::class)->name('admin.tenderings.show-finished-tendering');


Route::get('/ranking/{tendering}', PruebaRanking::class)->name('admin.ranking.index');

Route::get('/parameters', IndexParameters::class)->name('admin.parameters.index');

Route::get('/trunk-lots', IndexTrunkLots::class)->name('admin.trunk-lots.index');
Route::get('/task-statuses', IndexTaskStatuses::class)->name('admin.task-statuses.index');
Route::get('/phases', IndexPhases::class)->name('admin.phases.index');
Route::get('/types-of-tasks', IndexTypesOfTasks::class)->name('admin.types-of-tasks.index');
Route::get('/following-products', IndexFollowingProducts::class)->name('admin.following-products.index');
Route::get('/previous-products', IndexPreviousProducts::class)->name('admin.previous-products.index');

// Tareas
Route::get('/tasks', IndexTasks::class)->name('admin.tasks.index');
Route::get('/tasks/{task_type}/manage', ManageTasks::class)->name('admin.tasks.manage');
Route::get('/tasks/{task_type}/register/{task}', RegisterTask::class)->name('admin.tasks.register');
Route::get('/tasks/show/{task}', ShowTask::class)->name('admin.tasks.show');


Route::get('/lots', LotsIndex::class)->name('admin.lots.index');
Route::get('/lots/{lot}/sublots', SublotsIndex::class)->name('admin.sublots.index');
Route::get('/sublots/areas', SublotsAreas::class)->name('admin.sublots-areas.index');
Route::get('/sublots/products', SublotsProducts::class)->name('admin.sublots-products.index');


// Test
Route::get('/tests', Test::class)->name('admin.tests.index');
Route::get('/tests-orders', TestOrders::class)->name('admin.tests-orders.index');
