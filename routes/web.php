<?php

use App\Http\Controllers\Admin\TermConditionController;
use App\Http\Controllers\AreaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SalesAssociateController;
use App\Http\Controllers\TailorController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductGroupController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CatalogueController;
use App\Http\Controllers\GroupTypeController;
use App\Http\Controllers\SellingUnitController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ProjectReceivedPaymentController;
use App\Exports\ProjectsExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Project;
use App\Services\ProjectQueryService;
use App\Exports\ProductsExport;
use App\Models\Product;
use App\Exports\ProductsSampleExport;
use App\Http\Controllers\LabourController;
use App\Http\Controllers\InteriorController;
use App\Http\Controllers\StatusController;

/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD (ALL ROLES)
    |--------------------------------------------------------------------------
    */

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | ADMIN ONLY
    |--------------------------------------------------------------------------
    */

    Route::middleware(['role:admin'])->group(function () {

        Route::resource('users', UserController::class);

        Route::prefix('admin')->name('admin.')->group(function () {
            Route::resource('roles', RoleController::class);
            Route::resource('permissions', PermissionController::class);
        });
    });

    /*
    |--------------------------------------------------------------------------
    | ADMIN + SALES ASSOCIATES
    |--------------------------------------------------------------------------
    */

    Route::middleware(['role:admin|sales_associates|staff'])->group(function () {

        // Route::resource('sales_associates', SalesAssociateController::class);
        // Route::resource('tailors', TailorController::class);
//         Route::get('projects/start', [ProjectController::class, 'start'])->name('projects.start');
// Route::resource('projects', ProjectController::class)
//     ->except(['index', 'show']);
//         Route::get('/projects/{project}/step/{step}',
//     [ProjectController::class, 'create']
// )->name('projects.step');

        // routes/web.php
        Route::post(
            '/projects/{project}/approve',
            [ProjectController::class, 'approve']
        )->name('projects.approve')
            ->middleware(['auth', 'permission:approve project']);



        // Route::resource('stores', StoreController::class);
        Route::get('products/{product}/view', [ProductController::class, 'view'])
            ->name('products.view');
        Route::resource('products', ProductController::class);
        Route::resource('product-groups', ProductGroupController::class);
        Route::resource('brands', BrandController::class);
        Route::resource('catalogues', CatalogueController::class);
        Route::resource('group-types', GroupTypeController::class);
        Route::resource('selling-units', SellingUnitController::class);

        // Project Steps
        Route::post('projects/store-step1', [ProjectController::class, 'storeStep1'])->name('projects.store.step1');
        Route::post('projects/store-step2', [ProjectController::class, 'storeStep2'])->name('projects.store.step2');
        // Route::post('projects/store-step3', [ProjectController::class, 'storeStep3'])->name('projects.store.step3');

        Route::get('projects/customer/{id}', [ProjectController::class, 'getCustomer'])->name('projects.customer');
        // Update customer details via AJAX
        Route::put('projects/customer/{id}', [ProjectController::class, 'updateCustomer'])->name('projects.customer.update');

    });



    //measurment
    Route::middleware(['permission:manage measurement'])->group(function () {

        Route::get('/projects/measurement', [ProjectController::class, 'measurement'])
            ->name('projects.measurement');

        Route::get('/projects/{id}/measurement', [ProjectController::class, 'measurement'])
            ->name('projects.measurement');

        Route::post('/areas', [AreaController::class, 'store'])
            ->name('areas.store');
        Route::post('/areas/update', [ProjectController::class, 'updateArea'])->name('areas.update');
        Route::post('/areas/delete', [ProjectController::class, 'deleteArea'])
            ->name('areas.delete');
        Route::post('/measurements/delete', [ProjectController::class, 'deleteMeasurement'])
            ->name('measurements.delete');


    });

    //materila
    Route::middleware(['permission:manage materials'])->group(function () {
        Route::get('/projects/{projectId}/step3', [ProjectController::class, 'step3'])
            ->name('projects.step3');
    });
    //quation
    Route::middleware(['permission:manage quotation'])->group(function () {
        Route::get('/projects/{projectId}/quotation', [ProjectController::class, 'step4'])
            ->name('projects.step4');
    });
    //payment
    Route::middleware(['permission:manage payments'])->group(function () {
        Route::get(
            '/projects/{project}/received-payments',
            [ProjectReceivedPaymentController::class, 'index']
        )
            ->name('projects.received.payments');
    });


    /*
        |--------------------------------------------------------------------------
        |project PERMISSION ROUTES (ALL ROLES)
        |--------------------------------------------------------------------------
        */
    //  Route::get('projects', [ProjectController::class, 'index'])
//         ->name('projects.index')
//         ->middleware('permission:view order');
//  Route::get('projects', [ProjectController::class, 'create'])
//         ->name('projects.create ')
//         ->middleware('permission:create order');



    /*
    |--------------------------------------------------------------------------
    | PROJECT PERMISSION ROUTES (ALL ROLES)
    |--------------------------------------------------------------------------
    */

    Route::get('projects', [ProjectController::class, 'index'])
        ->name('projects.index')
        ->middleware('permission:view project');

    Route::get('projects/start', [ProjectController::class, 'start'])
        ->name('projects.start')
        ->middleware('permission:start project');

    Route::get('projects/create', [ProjectController::class, 'create'])
        ->name('projects.create')
        ->middleware('permission:add project');

    Route::post('projects', [ProjectController::class, 'store'])
        ->name('projects.store')
        ->middleware('permission:add project');

    Route::get('projects/{project}/edit', [ProjectController::class, 'edit'])
        ->name('projects.edit')
        ->middleware('permission:edit project');

    Route::put('projects/{project}', [ProjectController::class, 'update'])
        ->name('projects.update')
        ->middleware('permission:edit project');

    Route::delete('projects/{project}', [ProjectController::class, 'destroy'])
        ->name('projects.destroy')
        ->middleware('permission:delete project');


    /*
    |--------------------------------------------------------------------------
    |Customer PERMISSION ROUTES (ALL ROLES)
    |--------------------------------------------------------------------------
    */


    Route::get('customers', [CustomerController::class, 'index'])
        ->name('customers.index')
        ->middleware('permission:view customer');

    Route::get('customers/create', [CustomerController::class, 'create'])
        ->name('customers.create')
        ->middleware('permission:add customer');

    Route::post('customers', [CustomerController::class, 'store'])
        ->name('customers.store')
        ->middleware('permission:add customer');

    Route::get('customers/{customer}/edit', [CustomerController::class, 'edit'])
        ->name('customers.edit')
        ->middleware('permission:edit customer');

    Route::put('customers/{customer}', [CustomerController::class, 'update'])
        ->name('customers.update')
        ->middleware('permission:edit customer');

    Route::delete('customers/{customer}', [CustomerController::class, 'destroy'])
        ->name('customers.destroy')
        ->middleware('permission:delete customer');



    /*
    |--------------------------------------------------------------------------
    |Task PERMISSION ROUTES (ALL ROLES)
    |--------------------------------------------------------------------------
    */

    Route::get('tasks', [TaskController::class, 'index'])
        ->name('tasks.index')
        ->middleware('permission:view task');

    Route::get('tasks/create', [TaskController::class, 'create'])
        ->name('tasks.create')
        ->middleware('permission:add task');

    Route::post('tasks', [TaskController::class, 'store'])
        ->name('tasks.store')
        ->middleware('permission:add task');

    Route::get('tasks/{task}/edit', [TaskController::class, 'edit'])
        ->name('tasks.edit')
        ->middleware('permission:edit task');

    Route::put('tasks/{task}', [TaskController::class, 'update'])
        ->name('tasks.update')
        ->middleware('permission:edit task');

    Route::delete('tasks/{task}', [TaskController::class, 'destroy'])
        ->name('tasks.destroy')
        ->middleware('permission:delete task');




    /*
    |--------------------------------------------------------------------------
    |sales_associates PERMISSION ROUTES (ALL ROLES)
    |--------------------------------------------------------------------------
    */
    Route::get('sales_associates', [SalesAssociateController::class, 'index'])
        ->name('sales_associates.index')
        ->middleware('permission:view sales_associates');

    Route::get('sales_associates/create', [SalesAssociateController::class, 'create'])
        ->name('sales_associates.create')
        ->middleware('permission:add sales_associates');

    Route::post('sales_associates', [SalesAssociateController::class, 'store'])
        ->name('sales_associates.store')
        ->middleware('permission:add sales_associates');

    Route::get('sales_associates/{sales_associate}/edit', [SalesAssociateController::class, 'edit'])
        ->name('sales_associates.edit')
        ->middleware('permission:edit sales_associates');

    Route::put('sales_associates/{sales_associate}', [SalesAssociateController::class, 'update'])
        ->name('sales_associates.update')
        ->middleware('permission:edit sales_associates');

    Route::delete('sales_associates/{sales_associate}', [SalesAssociateController::class, 'destroy'])
        ->name('sales_associates.destroy')
        ->middleware('permission:delete sales_associates');

    /*
    |--------------------------------------------------------------------------
    |tailor PERMISSION ROUTES (ALL ROLES)
    |--------------------------------------------------------------------------
    */

    Route::get('tailors', [TailorController::class, 'index'])
        ->name('tailors.index')
        ->middleware('permission:view tailors');

    Route::get('tailors/create', [TailorController::class, 'create'])
        ->name('tailors.create')
        ->middleware('permission:add tailors');

    Route::post('tailors', [TailorController::class, 'store'])
        ->name('tailors.store')
        ->middleware('permission:add tailors');

    Route::get('tailors/{tailor}/edit', [TailorController::class, 'edit'])
        ->name('tailors.edit')
        ->middleware('permission:edit tailors');

    Route::put('tailors/{tailor}', [TailorController::class, 'update'])
        ->name('tailors.update')
        ->middleware('permission:edit tailors');

    Route::delete('tailors/{tailor}', [TailorController::class, 'destroy'])
        ->name('tailors.destroy')
        ->middleware('permission:delete tailors');


    /*
|--------------------------------------------------------------------------
|store PERMISSION ROUTES (ALL ROLES)
|--------------------------------------------------------------------------
*/

    Route::get('stores', [StoreController::class, 'index'])
        ->name('stores.index')
        ->middleware('permission:view stores');

    Route::get('stores/create', [StoreController::class, 'create'])
        ->name('stores.create')
        ->middleware('permission:add stores');

    Route::post('stores', [StoreController::class, 'store'])
        ->name('stores.store')
        ->middleware('permission:add stores');

    Route::get('stores/{store}/edit', [StoreController::class, 'edit'])
        ->name('stores.edit')
        ->middleware('permission:edit stores');

    Route::put('stores/{store}', [StoreController::class, 'update'])
        ->name('stores.update')
        ->middleware('permission:edit stores');

    Route::delete('stores/{store}', [StoreController::class, 'destroy'])
        ->name('stores.destroy')
        ->middleware('permission:delete stores');

});


// Route::get('/projects/measurement', [ProjectController::class, 'measurement'])->name('projects.measurement');

// Route::get('/projects/{id}/measurement',[ProjectController::class, 'measurement'])->name('projects.measurement');

// Route::post('/areas', [AreaController::class, 'store'])->name('areas.store');
// Route::post('/areas/update', [AreaController::class, 'updateArea'])->name('areas.update');

Route::get('products/{id}/details', [ProjectController::class, 'getProductDetails']);
// Route::get('/projects/{projectId}/step3', [ProjectController::class, 'step3'])->name('projects.step3');


Route::post('/projects/step3/store', [ProjectController::class, 'storeStep3'])->name('projects.storeStep3');

// Route::get('/projects/{projectId}/quotation',
//     [ProjectController::class, 'step4']
// )->name('projects.step4');
//update for customer details and project details
Route::post('/projects/{id}/confirm', [ProjectController::class, 'confirm'])->name('projects.confirm');
Route::post('/projects/{project}/status', [ProjectController::class, 'updateStatus'])->name('projects.updateStatus')->middleware('permission:update status');
Route::post('/tasks/{task}/approve', [TaskController::class, 'approve'])
    ->name('tasks.approve');

//payment
// Route::get('/projects/{project}/received-payments',
//     [ProjectReceivedPaymentController::class,'index'])
//     ->name('projects.received.payments');

Route::post(
    '/projects/{project}/received-payments',
    [ProjectReceivedPaymentController::class, 'store']
)
    ->name('projects.received.payments.store');
//total store

Route::post(
    '/projects/{project}/save-quotation-total',
    [ProjectController::class, 'saveQuotationTotal']
)->name('projects.saveQuotationTotal');

Route::put(
    'projects/{project}/received-payments/{payment}',
    [ProjectReceivedPaymentController::class, 'update']
)->name('projects.received.payments.update');

Route::delete(
    'projects/{project}/received-payments/{payment}',
    [ProjectReceivedPaymentController::class, 'destroy']
)->name('projects.received.payments.destroy');
Route::get('/payment-details', [ProjectController::class, 'paymentDetails'])
    ->name('payment.details');

//payment pdf

Route::get(
    'projects/{project}/payments/{payment}/receipt',
    [ProjectController::class, 'receipt']
)->name('projects.payments.receipt');

//quation generate
Route::get(
    '/projects/{project}/quotation-pdf',
    [ProjectController::class, 'quotationPdf']
)->name('projects.quotation.pdf');
//view pdf
// web.php

Route::get(
    '/projects/{project}/quotation/view',
    [ProjectController::class, 'viewPdf']
)->name('projects.quotation.pdf.view');

//update quotation_item
Route::post('/quotation/item/update', [ProjectController::class, 'updateItem'])
    ->name('quotation.item.update');
//export for project

Route::get('projects/export/{type}', [ProjectController::class, 'export'])
    ->name('projects.export');
//export for customer
Route::get('customers-export/excel', [CustomerController::class, 'exportExcel'])
    ->name('customers.export.excel');

Route::get('customers-export/csv', [CustomerController::class, 'exportCsv'])
    ->name('customers.export.csv');

Route::get('customers-export/pdf', [CustomerController::class, 'exportPdf'])
    ->name('customers.export.pdf');

//export for sales
// routes/web.php

Route::get('sales-associates/export/excel', [SalesAssociateController::class, 'exportExcel'])
    ->name('sales_associates.export.excel');

Route::get('sales-associates/export/csv', [SalesAssociateController::class, 'exportCsv'])
    ->name('sales_associates.export.csv');

Route::get('sales-associates/export/pdf', [SalesAssociateController::class, 'exportPdf'])
    ->name('sales_associates.export.pdf');

//tailors export and import

Route::post('tailors-import', [TailorController::class, 'import'])->name('tailors.import');

Route::get('tailors-export/excel', [TailorController::class, 'exportExcel'])->name('tailors.export.excel');
Route::get('tailors-export/csv', [TailorController::class, 'exportCsv'])->name('tailors.export.csv');
Route::get('tailors-export/pdf', [TailorController::class, 'exportPdf'])->name('tailors.export.pdf');
Route::get('tailors-import-sample', [TailorController::class, 'downloadSample'])
    ->name('tailors.import.sample');

//export for store
Route::get('stores/export', [StoreController::class, 'export'])->name('stores.export');
//import for store
Route::get('stores/import-sample', [StoreController::class, 'downloadImportSample'])
    ->name('stores.import.sample');

Route::post('stores/import-required', [StoreController::class, 'importRequired'])
    ->name('stores.import.required');
//export for product




Route::get('products/export/excel', function () {
    return Excel::download(new ProductsExport, 'products.xlsx');
})->name('products.export.excel');

Route::get('products/export/csv', function () {
    return Excel::download(new ProductsExport, 'products.csv');
})->name('products.export.csv');

Route::get('products/export/pdf', [ProductController::class, 'exportPdf'])
    ->name('products.export.pdf');

//import for product
Route::post('products/import', [ProductController::class, 'import'])->name('products.import');

//export sample
Route::get('products/export/sample', function () {
    return Excel::download(new ProductsSampleExport, 'products_sample.xlsx');
})->name('products.export.sample');
Route::get('/get-units-by-id/{id}', [ProductController::class, 'getUnitsById']);
//export for product group
Route::get('product-groups-sample', [ProductGroupController::class, 'downloadSample'])
    ->name('product-groups.sample');
Route::get('product-groups-export', [ProductGroupController::class, 'export'])
    ->name('product-groups.export');

Route::post('product-groups-import', [ProductGroupController::class, 'import'])
    ->name('product-groups.import');

// routes/web.php
Route::get(
    '/projects/{project}/measurements-pdf',
    [ProjectController::class, 'measurementPdf']
)->name('projects.measurements.pdf');


Route::get('/dashboard/payments-filter', [DashboardController::class, 'filterPayments']);
Route::get('/dashboard/projects-chart', [DashboardController::class, 'projectsChart']);
Route::get('/orders', [ProjectController::class, 'orders'])
    ->name('orders.index');

Route::get(
    '/orders/{project}/invoice/generate',
    [ProjectController::class, 'generateInvoice']
)->name('orders.invoice.generate');

// Route::get('/invoices/{invoice}/download',
//     [ProjectController::class,'downloadInvoice']
// )->name('orders.invoice.download');

Route::get(
    '/orders/invoice/{invoice}/download',
    [ProjectController::class, 'downloadInvoice']
)->name('orders.invoice.download');



// Show received payments page for a project
Route::get('projects/{project}/payments', [ProjectController::class, 'receivedPayments'])
    ->name('projects.received-payments');

Route::get('/stores/{store}/branches', [ProductController::class, 'getBranches']);

Route::post('projects/{project}/quotation/version', [ProjectController::class, 'createNewQuotationVersion'])
    ->name('quotation.version.create');
Route::get(
    '/projects/{project}/quotation/revise',
    [ProjectController::class, 'createNewQuotationVersion']
)
    ->name('projects.quotation.revise');
Route::get(
    '/projects/{project}/quotation/revise',
    [ProjectController::class, 'reviseQuotation']
)
    ->name('projects.quotation.revise');


Route::resource('labours', LabourController::class);

// Profile page (only for logged-in users)
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::post('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
});


Route::resource('interiors', InteriorController::class);
Route::post(
    '/projects/{project}/assign-interior',
    [ProjectController::class, 'assignInterior']
)->name('projects.assignInterior');
Route::post(
    '/projects/{project}/assign-interior',
    [ProjectController::class, 'assignInterior']
)->name('projects.assignInterior');
Route::get('/dashboard/daily-project-stats', [DashboardController::class, 'getDailyProjectStats'])->name('dashboard.project.stats');

Route::post('/get-users-by-roles', [TaskController::class, 'getUsersByRoles'])
    ->name('get.users.by.roles');

Route::get('/statuses', [StatusController::class, 'index'])->name('statuses.index');
Route::post('/statuses', [StatusController::class, 'store'])->name('statuses.store');
Route::put('/statuses/{id}', [StatusController::class, 'update'])->name('statuses.update');
Route::delete('/statuses/{id}', [StatusController::class, 'destroy'])->name('statuses.destroy');

Route::post('/get-status-by-role', [StatusController::class, 'getStatusByRole'])
    ->name('get.status.by.role');
Route::post('/quotation/save-terms', [ProjectController::class, 'saveQuotationTerms'])->name('quotation.save.terms');
Route::get('/products/{product}/edit-data', [ProductController::class,'editData']);
Route::post('/get-status-by-roles', [TaskController::class, 'getStatusesByRoles'])
    ->name('get.status.by.roles');
Route::post('/users/by-roles', [TaskController::class, 'getUsersByRoles'])
    ->name('get.users.by.roles');
Route::get(
    '/projects/{project}/quotation-preview',
    [ProjectController::class, 'quotationPreview']
)->name('projects.quotation.preview');
Route::post('/tasks/{task}/update-status', [TaskController::class, 'updateStatus'])
    ->name('tasks.update.status');
Route::post('/tasks/{id}/approve', [TaskController::class, 'approve'])
    ->name('tasks.approve');
Route::post('/measurements/delete-row', [ProjectController::class, 'deleteRow'])->name('measurements.delete-row');
Route::post('/measurements/delete-area', [ProjectController::class, 'deleteArea'])->name('measurements.delete-area');
Route::get( '/projects/{project}/quotation-preview',[ProjectController::class, 'quotationPreview'])->name('projects.quotation.preview');
Route::post('/areas/update', [ProjectController::class, 'updateArea'])->name('areas.update');
Route::get('/orders/invoice/{invoice}/print', [ProjectController::class,'printInvoice'])->name('orders.invoice.print');
Route::prefix('admin')->name('admin.')->group(function () {

Route::get('terms',[TermConditionController::class,'index'])->name('terms.index');

Route::post('terms/store',[TermConditionController::class,'store'])->name('terms.store');

Route::post('terms/update/{id}',[TermConditionController::class,'update'])->name('terms.update');

Route::delete('terms/delete/{id}',[TermConditionController::class,'destroy'])->name('terms.destroy');

});
