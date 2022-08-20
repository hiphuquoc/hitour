<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\Auth\LoginController;

use App\Http\Controllers\AdminTourLocationController;
use App\Http\Controllers\AdminTourDepartureController;
use App\Http\Controllers\AdminTourPartnerController;
use App\Http\Controllers\AdminTourPartnerContactController;
use App\Http\Controllers\AdminTourController;
use App\Http\Controllers\AdminTourBookingController;
use App\Http\Controllers\AdminTourOptionController;

use App\Http\Controllers\AdminShipLocationController;
use App\Http\Controllers\AdminShipDepartureController;
use App\Http\Controllers\AdminShipPartnerController;
use App\Http\Controllers\AdminShipPartnerContactController;
use App\Http\Controllers\AdminShipController;
use App\Http\Controllers\AdminShipPriceController;

use App\Http\Controllers\AdminStaffController;

use App\Http\Controllers\AdminCostController;
use App\Http\Controllers\AdminImageController;

use App\Http\Controllers\AdminFormController;
use App\Http\Controllers\AdminSliderController;
use App\Http\Controllers\AdminGalleryController;

use App\Http\Controllers\MainHomeController;
use App\Http\Controllers\RoutingController;
use App\Http\Controllers\ShipController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('admin')->group(function(){
    Route::get('/', [LoginController::class, 'showLoginForm'])->name('admin.showLoginForm');
    Route::post('/login', [LoginController::class, 'login'])->name('admin.login');
    Route::get('/logout', [LoginController::class, 'logout'])->name('admin.logout');

    Route::middleware(['auth'])->group(function () {
        /* ===== TOUR LOCATION ===== */
        Route::prefix('tourLocation')->group(function(){
            Route::get('/', [AdminTourLocationController::class, 'list'])->name('admin.tourLocation.list');
            Route::post('/create', [AdminTourLocationController::class, 'create'])->name('admin.tourLocation.create');
            Route::get('/view', [AdminTourLocationController::class, 'view'])->name('admin.tourLocation.view');
            Route::post('/update', [AdminTourLocationController::class, 'update'])->name('admin.tourLocation.update');
            /* Delete AJAX */
            Route::get('/delete', [AdminTourLocationController::class, 'delete'])->name('admin.tourLocation.delete');
        });
        /* ===== TOUR DEPARTURE ===== */
        Route::prefix('tourDeparture')->group(function(){
            Route::get('/', [AdminTourDepartureController::class, 'list'])->name('admin.tourDeparture.list');
            Route::post('/create', [AdminTourDepartureController::class, 'create'])->name('admin.tourDeparture.create');
            Route::get('/view', [AdminTourDepartureController::class, 'view'])->name('admin.tourDeparture.view');
            Route::post('/update', [AdminTourDepartureController::class, 'update'])->name('admin.tourDeparture.update');
            /* Delete AJAX */
            Route::get('/delete', [AdminTourDepartureController::class, 'delete'])->name('admin.tourDeparture.delete');
        });
        /* ===== TOUR PARTNER ===== */
        Route::prefix('tourPartner')->group(function(){
            Route::get('/', [AdminTourPartnerController::class, 'list'])->name('admin.tourPartner.list');
            Route::post('/create', [AdminTourPartnerController::class, 'create'])->name('admin.tourPartner.create');
            Route::get('/view', [AdminTourPartnerController::class, 'view'])->name('admin.tourPartner.view');
            Route::post('/update', [AdminTourPartnerController::class, 'update'])->name('admin.tourPartner.update');
            /* Delete AJAX */
            Route::get('/delete', [AdminTourPartnerController::class, 'delete'])->name('admin.tourPartner.delete');
            Route::post('/createContact', [AdminTourPartnerContactController::class, 'create'])->name('admin.tourPartner.createContact');
            Route::post('/updateContact', [AdminTourPartnerContactController::class, 'update'])->name('admin.tourPartner.updateContact');
            Route::post('/loadContact', [AdminTourPartnerContactController::class, 'loadContact'])->name('admin.tourPartner.loadContact');
            Route::post('/loadFormContact', [AdminTourPartnerContactController::class, 'loadFormContact'])->name('admin.tourPartner.loadFormContact');
            Route::post('/deleteContact', [AdminTourPartnerContactController::class, 'delete'])->name('admin.tourPartner.deleteContact');
        });
        /* ===== TOUR ===== */
        Route::prefix('tour')->group(function(){
            Route::get('/', [AdminTourController::class, 'list'])->name('admin.tour.list');
            Route::post('/create', [AdminTourController::class, 'create'])->name('admin.tour.create');
            Route::get('/view', [AdminTourController::class, 'view'])->name('admin.tour.view');
            Route::post('/update', [AdminTourController::class, 'update'])->name('admin.tour.update');
            /* Delete AJAX */
            Route::get('/delete', [AdminTourController::class, 'delete'])->name('admin.tour.delete');
            Route::post('/loadOptionPrice', [AdminTourOptionController::class, 'loadOptionPrice'])->name('admin.tourOption.loadOptionPrice');
            Route::post('/loadFormOption', [AdminTourOptionController::class, 'loadFormOption'])->name('admin.tourOption.loadFormOption');
            Route::post('/createOption', [AdminTourOptionController::class, 'create'])->name('admin.tourOption.createOption');
            Route::post('/updateOption', [AdminTourOptionController::class, 'update'])->name('admin.tourOption.updateOption');
            Route::post('/deleteOption', [AdminTourOptionController::class, 'delete'])->name('admin.tourOption.deleteOption');
        });
        /* ===== TOUR BOOKING ===== */
        Route::prefix('tour_booking')->group(function(){
            Route::get('/', [AdminTourBookingController::class, 'list'])->name('admin.tourBooking.list');
            Route::get('/viewInsert', [AdminTourBookingController::class, 'viewInsert'])->name('admin.tourBooking.viewInsert');
            Route::post('/create', [AdminTourBookingController::class, 'create'])->name('admin.tourBooking.create');
            Route::get('/{id}/viewEdit', [AdminTourBookingController::class, 'viewEdit'])->name('admin.tourBooking.viewEdit');
            Route::post('/update', [AdminTourBookingController::class, 'update'])->name('admin.tourBooking.update');
            Route::get('/{id}/viewExport', [AdminTourBookingController::class, 'viewExport'])->name('admin.tourBooking.viewExport');
            /* Delete AJAX */
            Route::get('/loadOptionTourList', [AdminTourBookingController::class, 'loadOptionTourList'])->name('admin.tourBooking.loadOptionTourList');
            Route::get('/loadFormPriceQuantity', [AdminTourBookingController::class, 'loadFormPriceQuantity'])->name('admin.tourBooking.loadFormPriceQuantity');
            // Route::get('/delete', [AdminTourController::class, 'delete'])->name('admin.tour.delete');
            // Route::post('/loadOptionPrice', [AdminTourOptionController::class, 'loadOptionPrice'])->name('admin.tourOption.loadOptionPrice');
            // Route::post('/loadFormOption', [AdminTourOptionController::class, 'loadFormOption'])->name('admin.tourOption.loadFormOption');
            // Route::post('/createOption', [AdminTourOptionController::class, 'create'])->name('admin.tourOption.createOption');
            // Route::post('/updateOption', [AdminTourOptionController::class, 'update'])->name('admin.tourOption.updateOption');
            // Route::post('/deleteOption', [AdminTourOptionController::class, 'delete'])->name('admin.tourOption.deleteOption');
        });
        /* ===== SHIP LOCATION ===== */
        Route::prefix('shipLocation')->group(function(){
            Route::get('/', [AdminShipLocationController::class, 'list'])->name('admin.shipLocation.list');
            Route::post('/create', [AdminShipLocationController::class, 'create'])->name('admin.shipLocation.create');
            Route::get('/view', [AdminShipLocationController::class, 'view'])->name('admin.shipLocation.view');
            Route::post('/update', [AdminShipLocationController::class, 'update'])->name('admin.shipLocation.update');
            /* Delete AJAX */
            Route::get('/delete', [AdminShipLocationController::class, 'delete'])->name('admin.shipLocation.delete');
        });
        /* ===== SHIP DEPARTURE ===== */
        Route::prefix('shipDeparture')->group(function(){
            Route::get('/', [AdminShipDepartureController::class, 'list'])->name('admin.shipDeparture.list');
            Route::post('/create', [AdminShipDepartureController::class, 'create'])->name('admin.shipDeparture.create');
            Route::get('/view', [AdminShipDepartureController::class, 'view'])->name('admin.shipDeparture.view');
            Route::post('/update', [AdminShipDepartureController::class, 'update'])->name('admin.shipDeparture.update');
            /* Delete AJAX */
            Route::get('/delete', [AdminShipDepartureController::class, 'delete'])->name('admin.shipDeparture.delete');
        });
        /* ===== SHIP PARTNER ===== */
        Route::prefix('shipPartner')->group(function(){
            Route::get('/', [AdminShipPartnerController::class, 'list'])->name('admin.shipPartner.list');
            Route::get('/view', [AdminShipPartnerController::class, 'view'])->name('admin.shipPartner.view');
            Route::post('/create', [AdminShipPartnerController::class, 'create'])->name('admin.shipPartner.create');
            Route::post('/update', [AdminShipPartnerController::class, 'update'])->name('admin.shipPartner.update');
            /* Delete AJAX */
            Route::get('/delete', [AdminShipPartnerController::class, 'delete'])->name('admin.shipPartner.delete');
            Route::post('/createContact', [AdminShipPartnerContactController::class, 'create'])->name('admin.shipPartner.createContact');
            Route::post('/updateContact', [AdminShipPartnerContactController::class, 'update'])->name('admin.shipPartner.updateContact');
            Route::post('/loadContact', [AdminShipPartnerContactController::class, 'loadContact'])->name('admin.shipPartner.loadContact');
            Route::post('/loadFormContact', [AdminShipPartnerContactController::class, 'loadFormContact'])->name('admin.shipPartner.loadFormContact');
            Route::post('/deleteContact', [AdminShipPartnerContactController::class, 'delete'])->name('admin.shipPartner.deleteContact');
        });
        /* ===== SHIP INFO ===== */
        Route::prefix('ship')->group(function(){
            Route::get('/', [AdminShipController::class, 'list'])->name('admin.ship.list');
            Route::post('/create', [AdminShipController::class, 'create'])->name('admin.ship.create');
            Route::get('/view', [AdminShipController::class, 'view'])->name('admin.ship.view');
            Route::post('/update', [AdminShipController::class, 'update'])->name('admin.ship.update');
            /* Delete AJAX */
            Route::get('/delete', [AdminShipController::class, 'delete'])->name('admin.ship.delete');
            Route::post('/loadFormModal', [AdminShipPriceController::class, 'loadFormModal'])->name('admin.shipPrice.loadFormModal');
            Route::post('/loadList', [AdminShipPriceController::class, 'loadList'])->name('admin.shipPrice.loadList');
            Route::post('/createPrice', [AdminShipPriceController::class, 'createPrice'])->name('admin.shipPrice.createPrice');
            Route::post('/updatePrice', [AdminShipPriceController::class, 'updatePrice'])->name('admin.shipPrice.updatePrice');
            Route::post('/deletePrice', [AdminShipPriceController::class, 'deletePrice'])->name('admin.shipPrice.deletePrice');
        });
        /* ===== STAFF ===== */
        Route::prefix('staff')->group(function(){
            Route::get('/', [AdminStaffController::class, 'list'])->name('admin.staff.list');
            Route::get('/viewInsert', [AdminStaffController::class, 'viewInsert'])->name('admin.staff.viewInsert');
            Route::post('/create', [AdminStaffController::class, 'create'])->name('admin.staff.create');
            Route::get('/{id}/viewEdit', [AdminStaffController::class, 'viewEdit'])->name('admin.staff.viewEdit');
            Route::post('/update', [AdminStaffController::class, 'update'])->name('admin.staff.update');
            /* Delete AJAX */
            Route::get('/delete', [AdminStaffController::class, 'delete'])->name('admin.staff.delete');
        });
        /* ===== COST ===== */
        Route::prefix('cost')->group(function(){
            /* AJAX */
            Route::post('/loadCostMoreLess', [AdminCostController::class, 'loadCostMoreLess'])->name('admin.cost.loadCostMoreLess');
            Route::post('/loadFormCostMoreLess', [AdminCostController::class, 'loadFormCostMoreLess'])->name('admin.cost.loadFormCostMoreLess');
            Route::post('/create', [AdminCostController::class, 'create'])->name('admin.cost.create');
            Route::post('/update', [AdminCostController::class, 'update'])->name('admin.cost.update');
            Route::post('/delete', [AdminCostController::class, 'delete'])->name('admin.cost.delete');
        });
        /* ===== IMAGE ===== */
        Route::prefix('image')->group(function(){
            Route::get('/', [AdminImageController::class, 'list'])->name('admin.image.list');
            Route::post('/uploadImages', [AdminImageController::class, 'uploadImages'])->name('admin.image.uploadImages');
            Route::post('/loadImage', [AdminImageController::class, 'loadImage'])->name('admin.image.loadImage');
            Route::post('/loadModal', [AdminImageController::class, 'loadModal'])->name('admin.image.loadModal');
            Route::post('/changeName', [AdminImageController::class, 'changeName'])->name('admin.image.changeName');
            Route::post('/changeImage', [AdminImageController::class, 'changeImage'])->name('admin.image.changeImage');
            Route::post('/removeImage', [AdminImageController::class, 'removeImage'])->name('admin.image.removeImage');
        });
        /* ===== AJAX ===== */
        Route::post('/loadProvinceByRegion', [AdminFormController::class, 'loadProvinceByRegion'])->name('admin.form.loadProvinceByRegion');
        Route::post('/loadDistrictByProvince', [AdminFormController::class, 'loadDistrictByProvince'])->name('admin.form.loadDistrictByProvince');
        Route::get('/removeSlider', [AdminSliderController::class, 'removeSlider'])->name('admin.slider.removeSlider');
        Route::get('/removeGallery', [AdminGalleryController::class, 'removeGallery'])->name('admin.gallery.removeGallery');
    });
});

Route::get('/', [MainHomeController::class, 'home'])->name('main.home');

Route::post('/loadTocContent', [ShipController::class, 'loadTocContent'])->name('main.ship.loadTocContent');


Route::get("/{slug}/{slug2?}/{slug3?}/{slug4?}/{slug5?}", [RoutingController::class, 'routing'])->name('routing');