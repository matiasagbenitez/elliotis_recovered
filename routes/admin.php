<?php

use App\Http\Livewire\Tests\Test;
use App\Http\Livewire\API\IndexApi;
use App\Http\Livewire\Audits\Lotes;
use App\Http\Livewire\Tests\M2Test;
use App\Http\Livewire\Audits\Tareas;
use App\Http\Livewire\Audits\Ventas;
use App\Http\Livewire\Audits\Compras;
use App\Http\Livewire\Lots\LotsIndex;
use App\Http\Livewire\Sales\ShowSale;
use App\Http\Livewire\Tasks\ShowTask;
use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Audits\Clientes;
use App\Http\Livewire\Audits\Sublotes;
use App\Http\Livewire\Audits\Usuarios;
use App\Http\Livewire\Areas\IndexAreas;
use App\Http\Livewire\Audits\Productos;
use App\Http\Livewire\Sales\CreateSale;
use App\Http\Livewire\Sales\IndexSales;
use App\Http\Livewire\Tasks\IndexTasks;
use App\Http\Livewire\Tests\TestOrders;
use App\Http\Livewire\Lots\SublotsAreas;
use App\Http\Livewire\Lots\SublotsIndex;
use App\Http\Livewire\Tasks\ManageTasks;
use App\Http\Livewire\Audits\AuditsIndex;
use App\Http\Livewire\Audits\Proveedores;
use App\Http\Livewire\Clients\EditClient;
use App\Http\Livewire\Phases\IndexPhases;
use App\Http\Livewire\Tasks\RegisterTask;
use App\Http\Livewire\Weather\WeatherApi;
use App\Http\Controllers\LotPDFController;
use App\Http\Livewire\Audits\Licitaciones;
use App\Http\Livewire\Dashboard\Dashboard;
use App\Http\Controllers\SalePDFController;
use App\Http\Livewire\Clients\CreateClient;
use App\Http\Livewire\Clients\IndexClients;
use App\Http\Livewire\Lots\SublotsProducts;
use App\Http\Livewire\Products\ShowProduct;
use App\Http\Livewire\Sales\ShowSaleClient;
use App\Http\Livewire\Unities\IndexUnities;
use App\Http\Livewire\Audits\OrdenesDeVenta;
use App\Http\Livewire\Ranking\PruebaRanking;
use App\Http\Livewire\Audits\OrdenesDeCompra;
use App\Http\Livewire\IvaTypes\IndexIvaTypes;
use App\Http\Livewire\Measures\IndexMeasures;
use App\Http\Livewire\Products\CreateProduct;
use App\Http\Livewire\Products\IndexProducts;
use App\Http\Livewire\Purchases\ShowPurchase;
use App\Http\Livewire\Suppliers\EditSupplier;
use App\Http\Controllers\ProductPDFController;
use App\Http\Controllers\PurchasePDFController;
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
use App\Http\Controllers\LotDetailPDFController;
use App\Http\Controllers\ProduccionEmpaquetadoraPDFController;
use App\Http\Controllers\SaleDetailPDFController;
use App\Http\Controllers\TaskDetailPDFController;
use App\Http\Livewire\Audits\ProductosAnteriores;
use App\Http\Livewire\Audits\ProductosSiguientes;
use App\Http\Livewire\Localities\IndexLocalities;
use App\Http\Livewire\Parameters\IndexParameters;
use App\Http\Livewire\SaleOrders\CreateSaleOrder;
use App\Http\Livewire\SaleOrders\SaleOrdersIndex;
use App\Http\Livewire\Stadistics\IndexStadistics;
use App\Http\Livewire\Tenderings\CreateTendering;
use App\Http\Livewire\Tenderings\IndexTenderings;
use App\Http\Controllers\SublotsAreaPDFController;
use App\Http\Controllers\TasksReportPDFController;
use App\Http\Livewire\Tenderings\ShowOfferTendering;
use App\Http\Controllers\PurchaseDetailPDFController;
use App\Http\Controllers\SublotsProductPDFController;
use App\Http\Livewire\M2Calculator\M2CalculatorIndex;
use App\Http\Livewire\ProductNames\IndexProductNames;
use App\Http\Livewire\Products\CreatePreviousProduct;
use App\Http\Livewire\ProductTypes\IndexProductTypes;
use App\Http\Livewire\TaskStatuses\IndexTaskStatuses;
use App\Http\Livewire\TypesOfTasks\IndexTypesOfTasks;
use App\Http\Controllers\SaleOrderDetailPDFController;
use App\Http\Controllers\SaleDetailClientPDFController;
use App\Http\Livewire\IvaConditions\IndexIvaConditions;
use App\Http\Livewire\Notifications\IndexNotifications;
use App\Http\Livewire\Products\CreateFollowingProducts;
use App\Http\Livewire\PurchaseOrders\ShowPurchaseOrder;
use App\Http\Livewire\Tenderings\ShowFinishedTendering;
use App\Http\Livewire\PurchaseOrders\CreatePurchaseOrder;
use App\Http\Livewire\PurchaseOrders\PurchaseOrdersIndex;
use App\Http\Livewire\SaleOrders\ShowNecessaryProduction;
use App\Http\Controllers\PurchaseOrderDetailPDFController;
use App\Http\Livewire\SublotsTracking\SublotTrackingIndex;
use App\Http\Controllers\ProduccionLineaCortePDFController;
use App\Http\Livewire\PreviousProducts\IndexPreviousProducts;
use App\Http\Livewire\ProductionReport\IndexProductionReport;
use App\Http\Controllers\ProduccionMachimbradoraPDFController;
use App\Http\Livewire\FollowingProducts\IndexFollowingProducts;
use App\Http\Livewire\PucharseParameters\IndexPucharseParameters;
use App\Http\Livewire\NecesaryProduction\IndexNecessaryProduction;
use App\Http\Livewire\Stadistics\ProduccionEmpaquetadora;

// PAÍSES, PROVINCIAS Y LOCALIDADES
Route::get('/countries', IndexCountries::class)->name('admin.countries.index')->middleware('can:admin.countries.index');
Route::get('/provinces', IndexProvinces::class)->name('admin.provinces.index')->middleware('can:admin.provinces.index');
Route::get('/localities', IndexLocalities::class)->name('admin.localities.index')->middleware('can:admin.localities.index');

// IVA Y PARÁMETROS DE COMPRA
Route::get('/iva-conditions', IndexIvaConditions::class)->name('admin.iva-conditions.index')->middleware('can:admin.iva-conditions.index');
Route::get('/iva-types', IndexIvaTypes::class)->name('admin.iva-types.index')->middleware('can:admin.iva-types.index');
Route::get('/pucharse-parameters', IndexPucharseParameters::class)->name('admin.pucharse-parameters.index')->middleware('can:admin.pucharse-parameters.index');

// CLIENTES
Route::get('/clients', IndexClients::class)->name('admin.clients.index')->middleware('can:admin.clients.index');
Route::get('/clients/create', CreateClient::class)->name('admin.clients.create')->middleware('can:admin.clients.create');
Route::get('/clients/{client}/edit', EditClient::class)->name('admin.clients.edit')->middleware('can:admin.clients.edit');

// PROVEEDORES
Route::get('/suppliers', IndexSuppliers::class)->name('admin.suppliers.index')->middleware('can:admin.suppliers.index');
Route::get('/suppliers/create', CreateSupplier::class)->name('admin.suppliers.create')->middleware('can:admin.suppliers.create');
Route::get('/suppliers/{supplier}/edit', EditSupplier::class)->name('admin.suppliers.edit')->middleware('can:admin.suppliers.edit');

// PARÁMETROS PARA CREAR PRODUCTOS
Route::get('/measures', IndexMeasures::class)->name('admin.measures.index')->middleware('can:admin.measures.index');
Route::get('/unities', IndexUnities::class)->name('admin.unities.index')->middleware('can:admin.unities.index');
Route::get('/product-names', IndexProductNames::class)->name('admin.product-names.index')->middleware('can:admin.product-names.index');
Route::get('/wood-types', IndexWoodTypes::class)->name('admin.wood-types.index')->middleware('can:admin.wood-types.index');
Route::get('/product-types', IndexProductTypes::class)->name('admin.product-types.index')->middleware('can:admin.product-types.index');

// PRODUCTOS
Route::get('/products', IndexProducts::class)->name('admin.products.index')->middleware('can:admin.products.index');
Route::get('/products/create', CreateProduct::class)->name('admin.products.create')->middleware('can:admin.products.create');
Route::get('/products/{product}/show', ShowProduct::class)->name('admin.products.show')->middleware('can:admin.products.show');
Route::get('/products/{product}/previous-product', CreatePreviousProduct::class)->name('admin.products.create-previous-product')->middleware('can:admin.products.create-previous-product');
Route::get('/products/{product}/following-products', CreateFollowingProducts::class)->name('admin.products.create-following-products')->middleware('can:admin.products.create-following-products');

// COMPRAS
Route::get('/purchases', IndexPurchases::class)->name('admin.purchases.index')->middleware('can:admin.purchases.index');
Route::get('/purchase/create', CreatePurchase::class)->name('admin.purchases.create')->middleware('can:admin.purchases.create');
Route::get('/purchase/{purchase}/detail', ShowPurchase::class)->name('admin.purchases.show-detail')->middleware('can:admin.purchases.show-detail');

// VENTAS
Route::get('/sales', IndexSales::class)->name('admin.sales.index')->middleware('can:admin.sales.index');
Route::get('/sale/create', CreateSale::class)->name('admin.sales.create')->middleware('can:admin.sales.create');
Route::get('/sale/{sale}/detail', ShowSale::class)->name('admin.sales.show-detail')->middleware('can:admin.sales.show-detail');
Route::get('/sale/{sale}/detail-client', ShowSaleClient::class)->name('admin.sales.show-detail-client')->middleware('can:admin.sales.show-detail-client');

// ORDENES DE VENTA
Route::get('/sale-orders', SaleOrdersIndex::class)->name('admin.sale-orders.index')->middleware('can:admin.sale-orders.index');
Route::get('/sale-order/create', CreateSaleOrder::class)->name('admin.sale-orders.create')->middleware('can:admin.sale-orders.create');
Route::get('/sale-order/{saleOrder}/detail', ShowSaleOrder::class)->name('admin.sale-orders.show-detail')->middleware('can:admin.sale-orders.show-detail');
Route::get('/sale-order/{saleOrder}/detail/necessary-production', ShowNecessaryProduction::class)->name('admin.sale-orders.show-necessary-production')->middleware('can:admin.sale-orders.show-necessary-production');

// ORDENES DE COMPRA
Route::get('/purchase-orders', PurchaseOrdersIndex::class)->name('admin.purchase-orders.index')->middleware('can:admin.purchase-orders.index');
Route::get('/purchase-order/create', CreatePurchaseOrder::class)->name('admin.purchase-orders.create')->middleware('can:admin.purchase-orders.create');
Route::get('/purchase-order/{purchaseOrder}/detail', ShowPurchaseOrder::class)->name('admin.purchase-orders.show-detail')->middleware('can:admin.purchase-orders.show-detail');

// LICITACIONES DE ROLLOS
Route::get('/tenderings', IndexTenderings::class)->name('admin.tenderings.index')->middleware('can:admin.tenderings.index');
Route::get('/tendering/create/{notification?}', CreateTendering::class)->name('admin.tenderings.create')->middleware('can:admin.tenderings.create');
Route::get('/tendering/{tendering}/detail', ShowTendering::class)->name('admin.tenderings.show-detail')->middleware('can:admin.tenderings.show-detail');
Route::get('/tendering/{tendering}/hash/{hash:hash}/detail', ShowOfferTendering::class)->name('admin.tenderings.show-offer-detail')->middleware('can:admin.tenderings.show-offer-detail');
Route::get('/tendering/{tendering}/finished', ShowFinishedTendering::class)->name('admin.tenderings.show-finished-tendering')->middleware('can:admin.tenderings.show-finished-tendering');

// PARÁMETROS GENERALES DEL SISTEMA
Route::get('/parameters', IndexParameters::class)->middleware('can:admin.parameters.index')->name('admin.parameters.index')->middleware('can:admin.parameters.index');

// PARÁMETROS PARA CREAR TAREAS
Route::get('/trunk-lots', IndexTrunkLots::class)->name('admin.trunk-lots.index')->middleware('can:admin.trunk-lots.index');
Route::get('/task-statuses', IndexTaskStatuses::class)->name('admin.task-statuses.index')->middleware('can:admin.task-statuses.index');
Route::get('/phases', IndexPhases::class)->name('admin.phases.index')->middleware('can:admin.phases.index');
Route::get('/areas', IndexAreas::class)->name('admin.areas.index')->middleware('can:admin.areas.index');
Route::get('/types-of-tasks', IndexTypesOfTasks::class)->name('admin.types-of-tasks.index')->middleware('can:admin.types-of-tasks.index');
Route::get('/following-products', IndexFollowingProducts::class)->name('admin.following-products.index')->middleware('can:admin.following-products.index');
Route::get('/previous-products', IndexPreviousProducts::class)->name('admin.previous-products.index')->middleware('can:admin.previous-products.index');

// TAREAS
Route::get('/tasks', IndexTasks::class)->name('admin.tasks.index')->middleware('can:admin.tasks.index');
Route::get('/tasks/{task_type}/manage', ManageTasks::class)->name('admin.tasks.manage')->middleware('can:admin.tasks.manage');
Route::get('/tasks/{task_type}/register/{task}', RegisterTask::class)->name('admin.tasks.register')->middleware('can:admin.tasks.register');
Route::get('/tasks/show/{task}', ShowTask::class)->name('admin.tasks.show')->middleware('can:admin.tasks.show');
Route::get('/tasks/report', IndexProductionReport::class)->name('admin.tasks.report')->middleware('can:admin.tasks.report');

// LOTES Y SUBLOTES
Route::get('/lots', LotsIndex::class)->name('admin.lots.index')->middleware('can:admin.lots.index');
Route::get('/lots/{lot}/sublots', SublotsIndex::class)->name('admin.sublots.index')->middleware('can:admin.sublots.index');
Route::get('/sublots/areas', SublotsAreas::class)->name('admin.sublots-areas.index')->middleware('can:admin.sublots-areas.index');
Route::get('/sublots/products', SublotsProducts::class)->name('admin.sublots-products.index')->middleware('can:admin.sublots-products.index');

// CALCULADORA DE M2
Route::get('/calculator', M2CalculatorIndex::class)->name('admin.calculator.index')->middleware('can:admin.calculator.index');

// NOTIFICACIONES
Route::get('/notifications', IndexNotifications::class)->name('admin.notifications.index')->middleware('can:admin.notifications.index');

// RUTAS PARA PRUEBAS
Route::get('/sublots-tracking', SublotTrackingIndex::class)->name('admin.sublots-tracking.index');

// PRODUCCIÓN NECESARIA
Route::get('/necessary-production', IndexNecessaryProduction::class)->name('admin.necessary-production.index')->middleware('can:admin.necessary-production.index');

// AUDITORÍA
Route::get('/audits/clients', Clientes::class)->name('admin.auditory.clients')->middleware('can:admin.auditory.index');
Route::get('/audits/purchases', Compras::class)->name('admin.auditory.purchases')->middleware('can:admin.auditory.index');
Route::get('/audits/tenderings', Licitaciones::class)->name('admin.auditory.tenderings')->middleware('can:admin.auditory.index');
Route::get('/audits/lots', Lotes::class)->name('admin.auditory.lots')->middleware('can:admin.auditory.index');
Route::get('/audits/purchase-orders', OrdenesDeCompra::class)->name('admin.auditory.purchase-orders')->middleware('can:admin.auditory.index');
Route::get('/audits/sale-orders', OrdenesDeVenta::class)->name('admin.auditory.sale-orders')->middleware('can:admin.auditory.index');
Route::get('/audits/products', Productos::class)->name('admin.auditory.products')->middleware('can:admin.auditory.index');
Route::get('/audits/previous-products', ProductosAnteriores::class)->name('admin.auditory.previous-products')->middleware('can:admin.auditory.index');
Route::get('/audits/following-products', ProductosSiguientes::class)->name('admin.auditory.following-products')->middleware('can:admin.auditory.index');
Route::get('/audits/suppliers', Proveedores::class)->name('admin.auditory.suppliers')->middleware('can:admin.auditory.index');
Route::get('/audits/sublots', Sublotes::class)->name('admin.auditory.sublots')->middleware('can:admin.auditory.index');
Route::get('/audits/tasks', Tareas::class)->name('admin.auditory.tasks')->middleware('can:admin.auditory.index');
Route::get('/audits/users', Usuarios::class)->name('admin.auditory.users')->middleware('can:admin.auditory.index');
Route::get('/audits/sales', Ventas::class)->name('admin.auditory.sales')->middleware('can:admin.auditory.index');

// API clima
Route::get('/weather-api', WeatherApi::class)->name('admin.api.index')->middleware('can:admin.api.index');

// RUTAS PDF
Route::get('/products/pdf', [ProductPDFController::class, 'pdf'])->name('admin.products.pdf');

Route::get('/purchases/pdf', [PurchasePDFController::class, 'pdf'])->name('admin.purchases.pdf');
Route::get('/purchase/{purchase}/detail/pdf', [PurchaseDetailPDFController::class, 'pdf'])->name('admin.purchase-detail.pdf');
Route::get('/purchase-order/{purchase_order}/detail/pdf', [PurchaseOrderDetailPDFController::class, 'pdf'])->name('admin.purchase-order-detail.pdf');

Route::get('/sales/pdf', [SalePDFController::class, 'pdf'])->name('admin.sales.pdf');
Route::get('/sale/{sale}/detail/pdf', [SaleDetailPDFController::class, 'pdf'])->name('admin.sale-detail.pdf');
Route::get('/sale/{sale}/detail-client/pdf', [SaleDetailClientPDFController::class, 'pdf'])->name('admin.sale-detail-client.pdf');
Route::get('/sale-order/{sale_order}/detail/pdf', [SaleOrderDetailPDFController::class, 'pdf'])->name('admin.sale-order-detail.pdf');

Route::get('/lots/pdf', [LotPDFController::class, 'pdf'])->name('admin.lots.pdf');
Route::get('/lots/{lot}/sublots/pdf', [LotDetailPDFController::class, 'pdf'])->name('admin.lot-detail.pdf');

Route::get('/sublots/areas/pdf', [SublotsAreaPDFController::class, 'pdf'])->name('admin.sublots-areas.pdf');
Route::get('/sublots/products/pdf', [SublotsProductPDFController::class, 'pdf'])->name('admin.sublots-products.pdf');

Route::get('/task/{task}/detail/pdf', [TaskDetailPDFController::class, 'pdf'])->name('admin.task-detail.pdf');
Route::get('/tasks/report/pdf', [TasksReportPDFController::class, 'pdf'])->name('admin.tasks-report.pdf');


// MÓDULO DE ESTADÍSTICAS
Route::get('/stadistics', IndexStadistics::class)->name('admin.stadistics.index')->middleware('can:admin.stadistics.index');
Route::get('/stadistics/1/pdf', [ProduccionLineaCortePDFController::class, 'pdf'])->name('admin.produccion-linea-corte.pdf');
Route::get('/stadistics/2/pdf', [ProduccionMachimbradoraPDFController::class, 'pdf'])->name('admin.produccion-machimbradora.pdf');
Route::get('/stadistics/3/pdf', [ProduccionEmpaquetadoraPDFController::class, 'pdf'])->name('admin.produccion-empaquetadora.pdf');
