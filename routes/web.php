<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\Auth\LoginController;

use App\Http\Controllers\AdminTourLocationController;
use App\Http\Controllers\AdminTourDepartureController;
use App\Http\Controllers\AdminTourController;
use App\Http\Controllers\AdminTourBookingController;
use App\Http\Controllers\AdminTourOptionController;
use App\Http\Controllers\AdminStaffController;
use App\Http\Controllers\AdminPartnerController;
use App\Http\Controllers\AdminPartnerContactController;
use App\Http\Controllers\AdminCostController;
use App\Http\Controllers\AdminImageController;

use App\Http\Controllers\AdminFormController;
use App\Http\Controllers\AdminSliderController;
use App\Http\Controllers\AdminGalleryController;

use App\Http\Controllers\MainHomeController;
use App\Http\Controllers\RoutingController;

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
            Route::get('/viewInsert', [AdminTourLocationController::class, 'viewInsert'])->name('admin.tourLocation.viewInsert');
            Route::post('/create', [AdminTourLocationController::class, 'create'])->name('admin.tourLocation.create');
            Route::get('/{id}/viewEdit', [AdminTourLocationController::class, 'viewEdit'])->name('admin.tourLocation.viewEdit');
            Route::post('/update', [AdminTourLocationController::class, 'update'])->name('admin.tourLocation.update');
            /* Delete AJAX */
            Route::get('/delete', [AdminTourLocationController::class, 'delete'])->name('admin.tourLocation.delete');
        });
        /* ===== TOUR DEPARTURE ===== */
        Route::prefix('tourDeparture')->group(function(){
            Route::get('/', [AdminTourDepartureController::class, 'list'])->name('admin.tourDeparture.list');
            Route::get('/viewInsert', [AdminTourDepartureController::class, 'viewInsert'])->name('admin.tourDeparture.viewInsert');
            Route::post('/create', [AdminTourDepartureController::class, 'create'])->name('admin.tourDeparture.create');
            Route::get('/{id}/viewEdit', [AdminTourDepartureController::class, 'viewEdit'])->name('admin.tourDeparture.viewEdit');
            Route::post('/update', [AdminTourDepartureController::class, 'update'])->name('admin.tourDeparture.update');
            /* Delete AJAX */
            Route::get('/delete', [AdminTourDepartureController::class, 'delete'])->name('admin.tourDeparture.delete');
        });
        /* ===== TOUR ===== */
        Route::prefix('tour')->group(function(){
            Route::get('/', [AdminTourController::class, 'list'])->name('admin.tour.list');
            Route::get('/viewInsert', [AdminTourController::class, 'viewInsert'])->name('admin.tour.viewInsert');
            Route::post('/create', [AdminTourController::class, 'create'])->name('admin.tour.create');
            Route::get('/{id}/viewEdit', [AdminTourController::class, 'viewEdit'])->name('admin.tour.viewEdit');
            Route::post('/update', [AdminTourController::class, 'update'])->name('admin.tour.update');
            /* Delete AJAX */
            Route::get('/delete', [AdminTourController::class, 'delete'])->name('admin.tour.delete');
            Route::post('/loadOptionPrice', [AdminTourOptionController::class, 'loadOptionPrice'])->name('admin.tourOption.loadOptionPrice');
            Route::post('/loadFormOption', [AdminTourOptionController::class, 'loadFormOption'])->name('admin.tourOption.loadFormOption');
            Route::post('/createOption', [AdminTourOptionController::class, 'create'])->name('admin.tourOption.createOption');
            Route::post('/updateOption', [AdminTourOptionController::class, 'update'])->name('admin.tourOption.updateOption');
            Route::post('/deleteOption', [AdminTourOptionController::class, 'delete'])->name('admin.tourOption.deleteOption');
        });
        /* ===== PARTNER ===== */
        Route::prefix('partner')->group(function(){
            Route::get('/', [AdminPartnerController::class, 'list'])->name('admin.partner.list');
            Route::get('/viewInsert', [AdminPartnerController::class, 'viewInsert'])->name('admin.partner.viewInsert');
            Route::post('/create', [AdminPartnerController::class, 'create'])->name('admin.partner.create');
            Route::get('/{id}/viewEdit', [AdminPartnerController::class, 'viewEdit'])->name('admin.partner.viewEdit');
            Route::post('/update', [AdminPartnerController::class, 'update'])->name('admin.partner.update');
            /* Delete AJAX */
            Route::get('/delete', [AdminPartnerController::class, 'delete'])->name('admin.partner.delete');
            Route::post('/createContact', [AdminPartnerContactController::class, 'create'])->name('admin.partner.createContact');
            Route::post('/updateContact', [AdminPartnerContactController::class, 'update'])->name('admin.partner.updateContact');
            Route::post('/loadContact', [AdminPartnerContactController::class, 'loadContact'])->name('admin.partner.loadContact');
            Route::post('/loadFormContact', [AdminPartnerContactController::class, 'loadFormContact'])->name('admin.partner.loadFormContact');
            Route::post('/deleteContact', [AdminPartnerContactController::class, 'delete'])->name('admin.partner.deleteContact');
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
Route::get("/{slug}/{slug2?}/{slug3?}/{slug4?}/{slug5?}", [RoutingController::class, 'routing'])->name('routing');