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
use App\Http\Controllers\AdminShipPortController;
use App\Http\Controllers\AdminServiceController;
use App\Http\Controllers\AdminServiceLocationController;
use App\Http\Controllers\AdminAirPortController;
use App\Http\Controllers\AdminAirDepartureController;
use App\Http\Controllers\AdminAirLocationController;
use App\Http\Controllers\AdminAirPartnerController;
use App\Http\Controllers\AdminAirPartnerContactController;
use App\Http\Controllers\AdminAirController;
use App\Http\Controllers\AdminTourContinentController;
use App\Http\Controllers\AdminTourCountryController;
use App\Http\Controllers\AdminTourInfoForeignController;
use App\Http\Controllers\AdminTourOptionForeignController;
use App\Http\Controllers\AdminToolSeoController;
use App\Http\Controllers\AdminSettingController;
use App\Http\Controllers\AdminCheckSeoController;
use App\Http\Controllers\AdminStaffController;
use App\Http\Controllers\AdminCostController;
use App\Http\Controllers\AdminImageController;
use App\Http\Controllers\AdminFormController;
use App\Http\Controllers\AdminSliderController;
use App\Http\Controllers\AdminGalleryController;
use App\Http\Controllers\AdminShipBookingController;
use App\Http\Controllers\AdminGuideController;
use App\Http\Controllers\AdminCarrentalLocationController;
use App\Http\Controllers\AdminCategoryController;
use App\Http\Controllers\AdminBlogController;
use App\Http\Controllers\AdminPageController;
use App\Http\Controllers\AdminRedirectController;
use App\Http\Controllers\AdminCacheController;

use App\Http\Controllers\RunTestController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\RoutingController;
use App\Http\Controllers\AjaxController;
use App\Http\Controllers\ShipBookingController;
use App\Http\Controllers\TourBookingController;

use Illuminate\Support\Facades\Redirect;

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
        /* ===== GUIDE ===== */
        Route::prefix('guide')->group(function(){
            Route::get('/', [AdminGuideController::class, 'list'])->name('admin.guide.list');
            Route::post('/create', [AdminGuideController::class, 'create'])->name('admin.guide.create');
            Route::get('/view', [AdminGuideController::class, 'view'])->name('admin.guide.view');
            Route::post('/update', [AdminGuideController::class, 'update'])->name('admin.guide.update');
            /* Delete AJAX */
            Route::get('/delete', [AdminGuideController::class, 'delete'])->name('admin.guide.delete');
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
        /* ===== SHIP PORT ===== */
        Route::prefix('shipPort')->group(function(){
            Route::get('/', [AdminShipPortController::class, 'list'])->name('admin.shipPort.list');
            Route::post('/create', [AdminShipPortController::class, 'create'])->name('admin.shipPort.create');
            Route::get('/view', [AdminShipPortController::class, 'view'])->name('admin.shipPort.view');
            Route::post('/update', [AdminShipPortController::class, 'update'])->name('admin.shipPort.update');
            /* Delete AJAX */
            Route::get('/delete', [AdminShipPortController::class, 'delete'])->name('admin.shipPort.delete');
            Route::get('/loadSelectBoxShipPort', [AdminShipPortController::class, 'loadSelectBoxShipPort'])->name('admin.shipPort.loadSelectBoxShipPort');
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
        /* ===== SHIP BOOKING ===== */
        Route::prefix('ship_booking')->group(function(){
            Route::get('/', [AdminShipBookingController::class, 'list'])->name('admin.shipBooking.list');
            // Route::post('/create', [AdminTourBookingController::class, 'create'])->name('admin.tourBooking.create');
            Route::get('/view', [AdminShipBookingController::class, 'view'])->name('admin.shipBooking.view');
            Route::post('/update', [AdminShipBookingController::class, 'update'])->name('admin.shipBooking.update');
            Route::get('/{id}/viewExport', [AdminShipBookingController::class, 'viewExport'])->name('admin.shipBooking.viewExport');
            Route::post('/delete', [AdminShipBookingController::class, 'delete'])->name('admin.shipBooking.delete');
            // /* Delete AJAX */
            // Route::get('/loadDeparture', [AdminShipBookingController::class, 'loadDeparture'])->name('admin.shipBooking.loadDeparture');
            // Route::get('/loadFormPriceQuantity', [AdminTourBookingController::class, 'loadFormPriceQuantity'])->name('admin.tourBooking.loadFormPriceQuantity');
        });
        /* ===== SERVICE LOCATION ===== */
        Route::prefix('serviceLocation')->group(function(){
            Route::get('/', [AdminServiceLocationController::class, 'list'])->name('admin.serviceLocation.list');
            Route::post('/create', [AdminServiceLocationController::class, 'create'])->name('admin.serviceLocation.create');
            Route::get('/view', [AdminServiceLocationController::class, 'view'])->name('admin.serviceLocation.view');
            Route::post('/update', [AdminServiceLocationController::class, 'update'])->name('admin.serviceLocation.update');
            /* Delete AJAX */
            Route::get('/delete', [AdminServiceLocationController::class, 'delete'])->name('admin.serviceLocation.delete');
        });
        /* ===== SERVICE ===== */
        Route::prefix('service')->group(function(){
            Route::get('/', [AdminServiceController::class, 'list'])->name('admin.service.list');
            Route::post('/create', [AdminServiceController::class, 'create'])->name('admin.service.create');
            Route::get('/view', [AdminServiceController::class, 'view'])->name('admin.service.view');
            Route::post('/update', [AdminServiceController::class, 'update'])->name('admin.service.update');
            /* Delete AJAX */
            Route::get('/delete', [AdminServiceController::class, 'delete'])->name('admin.service.delete');
            // Route::post('/loadOptionPrice', [AdminTourOptionController::class, 'loadOptionPrice'])->name('admin.tourOption.loadOptionPrice');
            // Route::post('/loadFormOption', [AdminTourOptionController::class, 'loadFormOption'])->name('admin.tourOption.loadFormOption');
            // Route::post('/createOption', [AdminTourOptionController::class, 'create'])->name('admin.tourOption.createOption');
            // Route::post('/updateOption', [AdminTourOptionController::class, 'update'])->name('admin.tourOption.updateOption');
            // Route::post('/deleteOption', [AdminTourOptionController::class, 'delete'])->name('admin.tourOption.deleteOption');
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

            // Route::get('/toolRename', [AdminImageController::class, 'toolRename'])->name('admin.image.toolRename');
        });
        /* ===== CARRENTAL LOCATION ===== */
        Route::prefix('carrentalLocation')->group(function(){
            Route::get('/', [AdminCarrentalLocationController::class, 'list'])->name('admin.carrentalLocation.list');
            Route::post('/create', [AdminCarrentalLocationController::class, 'create'])->name('admin.carrentalLocation.create');
            Route::get('/view', [AdminCarrentalLocationController::class, 'view'])->name('admin.carrentalLocation.view');
            Route::post('/update', [AdminCarrentalLocationController::class, 'update'])->name('admin.carrentalLocation.update');
            /* Delete AJAX */
            Route::get('/delete', [AdminCarrentalLocationController::class, 'delete'])->name('admin.carrentalLocation.delete');
        });
        /* ===== AIR PORT ===== */
        Route::prefix('airPort')->group(function(){
            Route::get('/', [AdminAirPortController::class, 'list'])->name('admin.airPort.list');
            Route::post('/create', [AdminAirPortController::class, 'create'])->name('admin.airPort.create');
            Route::get('/view', [AdminAirPortController::class, 'view'])->name('admin.airPort.view');
            Route::post('/update', [AdminAirPortController::class, 'update'])->name('admin.airPort.update');
            /* Delete AJAX */
            Route::get('/delete', [AdminAirPortController::class, 'delete'])->name('admin.airPort.delete');
            Route::get('/loadSelectBoxAirPort', [AdminAirPortController::class, 'loadSelectBoxAirPort'])->name('admin.airPort.loadSelectBoxAirPort');
        });
        /* ===== AIR DEPARTURE ===== */
        Route::prefix('airDeparture')->group(function(){
            Route::get('/', [AdminAirDepartureController::class, 'list'])->name('admin.airDeparture.list');
            Route::post('/create', [AdminAirDepartureController::class, 'create'])->name('admin.airDeparture.create');
            Route::get('/view', [AdminAirDepartureController::class, 'view'])->name('admin.airDeparture.view');
            Route::post('/update', [AdminAirDepartureController::class, 'update'])->name('admin.airDeparture.update');
            /* Delete AJAX */
            Route::get('/delete', [AdminAirDepartureController::class, 'delete'])->name('admin.airDeparture.delete');
        });
        /* ===== AIR LOCATION ===== */
        Route::prefix('airLocation')->group(function(){
            Route::get('/', [AdminAirLocationController::class, 'list'])->name('admin.airLocation.list');
            Route::post('/create', [AdminAirLocationController::class, 'create'])->name('admin.airLocation.create');
            Route::get('/view', [AdminAirLocationController::class, 'view'])->name('admin.airLocation.view');
            Route::post('/update', [AdminAirLocationController::class, 'update'])->name('admin.airLocation.update');
            /* Delete AJAX */
            Route::get('/delete', [AdminAirLocationController::class, 'delete'])->name('admin.airLocation.delete');
        });
        /* ===== AIR PARTNER ===== */
        Route::prefix('airPartner')->group(function(){
            Route::get('/', [AdminAirPartnerController::class, 'list'])->name('admin.airPartner.list');
            Route::get('/view', [AdminAirPartnerController::class, 'view'])->name('admin.airPartner.view');
            Route::post('/create', [AdminAirPartnerController::class, 'create'])->name('admin.airPartner.create');
            Route::post('/update', [AdminAirPartnerController::class, 'update'])->name('admin.airPartner.update');
            /* Delete AJAX */
            Route::get('/delete', [AdminAirPartnerController::class, 'delete'])->name('admin.airPartner.delete');
            Route::post('/createContact', [AdminAirPartnerContactController::class, 'create'])->name('admin.airPartner.createContact');
            Route::post('/updateContact', [AdminAirPartnerContactController::class, 'update'])->name('admin.airPartner.updateContact');
            Route::post('/loadContact', [AdminAirPartnerContactController::class, 'loadContact'])->name('admin.airPartner.loadContact');
            Route::post('/loadFormContact', [AdminAirPartnerContactController::class, 'loadFormContact'])->name('admin.airPartner.loadFormContact');
            Route::post('/deleteContact', [AdminAirPartnerContactController::class, 'delete'])->name('admin.airPartner.deleteContact');
        });
        /* ===== AIR INFO ===== */
        Route::prefix('air')->group(function(){
            Route::get('/', [AdminAirController::class, 'list'])->name('admin.air.list');
            Route::post('/create', [AdminAirController::class, 'create'])->name('admin.air.create');
            Route::get('/view', [AdminAirController::class, 'view'])->name('admin.air.view');
            Route::post('/update', [AdminAirController::class, 'update'])->name('admin.air.update');
            /* Delete AJAX */
            Route::get('/delete', [AdminAirController::class, 'delete'])->name('admin.air.delete');
            // Route::post('/loadFormModal', [AdminShipPriceController::class, 'loadFormModal'])->name('admin.shipPrice.loadFormModal');
            // Route::post('/loadList', [AdminShipPriceController::class, 'loadList'])->name('admin.shipPrice.loadList');
            // Route::post('/createPrice', [AdminShipPriceController::class, 'createPrice'])->name('admin.shipPrice.createPrice');
            // Route::post('/updatePrice', [AdminShipPriceController::class, 'updatePrice'])->name('admin.shipPrice.updatePrice');
            // Route::post('/deletePrice', [AdminShipPriceController::class, 'deletePrice'])->name('admin.shipPrice.deletePrice');
        });
        /* ===== TOUR CONTINENT ===== */
        Route::prefix('tourContinent')->group(function(){
            Route::get('/', [AdminTourContinentController::class, 'list'])->name('admin.tourContinent.list');
            Route::post('/create', [AdminTourContinentController::class, 'create'])->name('admin.tourContinent.create');
            Route::get('/view', [AdminTourContinentController::class, 'view'])->name('admin.tourContinent.view');
            Route::post('/update', [AdminTourContinentController::class, 'update'])->name('admin.tourContinent.update');
            /* Delete AJAX */
            Route::get('/delete', [AdminTourContinentController::class, 'delete'])->name('admin.tourContinent.delete');
        });
        /* ===== TOUR CONTINENT ===== */
        Route::prefix('tourCountry')->group(function(){
            Route::get('/', [AdminTourCountryController::class, 'list'])->name('admin.tourCountry.list');
            Route::post('/create', [AdminTourCountryController::class, 'create'])->name('admin.tourCountry.create');
            Route::get('/view', [AdminTourCountryController::class, 'view'])->name('admin.tourCountry.view');
            Route::post('/update', [AdminTourCountryController::class, 'update'])->name('admin.tourCountry.update');
            /* Delete AJAX */
            Route::get('/delete', [AdminTourCountryController::class, 'delete'])->name('admin.tourCountry.delete');
        });
        /* ===== TOUR ===== */
        Route::prefix('tourInfoForeign')->group(function(){
            Route::get('/', [AdminTourInfoForeignController::class, 'list'])->name('admin.tourInfoForeign.list');
            Route::post('/create', [AdminTourInfoForeignController::class, 'create'])->name('admin.tourInfoForeign.create');
            Route::get('/view', [AdminTourInfoForeignController::class, 'view'])->name('admin.tourInfoForeign.view');
            Route::post('/update', [AdminTourInfoForeignController::class, 'update'])->name('admin.tourInfoForeign.update');
            /* Delete AJAX */
            Route::get('/delete', [AdminTourInfoForeignController::class, 'delete'])->name('admin.tourInfoForeign.delete');
            Route::post('/loadOptionPrice', [AdminTourOptionForeignController::class, 'loadOptionPrice'])->name('admin.tourOptionForeign.loadOptionPrice');
            Route::post('/loadFormOption', [AdminTourOptionForeignController::class, 'loadFormOption'])->name('admin.tourOptionForeign.loadFormOption');
            Route::post('/createOption', [AdminTourOptionForeignController::class, 'create'])->name('admin.tourOptionForeign.createOption');
            Route::post('/updateOption', [AdminTourOptionForeignController::class, 'update'])->name('admin.tourOptionForeign.updateOption');
            Route::post('/deleteOption', [AdminTourOptionForeignController::class, 'delete'])->name('admin.tourOptionForeign.deleteOption');
        });
        /* ===== TOOL SEO ===== */
        Route::prefix('toolSeo')->group(function(){
            Route::get('/listBlogger', [AdminToolSeoController::class, 'listBlogger'])->name('admin.toolSeo.listBlogger');
            Route::get('/viewBlogger', [AdminToolSeoController::class, 'viewBlogger'])->name('admin.toolSeo.viewBlogger');
            Route::post('/addBlogger', [AdminToolSeoController::class, 'addBlogger'])->name('admin.toolSeo.addBlogger');
            Route::get('/deleteBlogger', [AdminToolSeoController::class, 'deleteBlogger'])->name('admin.toolSeo.deleteBlogger');
            Route::get('/changeAutoPost', [AdminToolSeoController::class, 'changeAutoPost'])->name('admin.toolSeo.changeAutoPost');
            Route::get('/listAutoPost', [AdminToolSeoController::class, 'listAutoPost'])->name('admin.toolSeo.listAutoPost');
            Route::get('/loadRowAutoPost', [AdminToolSeoController::class, 'loadRowAutoPost'])->name('admin.toolSeo.loadRowAutoPost');
            Route::get('/loadFormContentspin', [AdminToolSeoController::class, 'loadFormContentspin'])->name('admin.toolSeo.loadFormContentspin');
            Route::get('/createContentspin', [AdminToolSeoController::class, 'createContentspin'])->name('admin.toolSeo.createContentspin');
            Route::get('/loadFormKeyword', [AdminToolSeoController::class, 'loadFormKeyword'])->name('admin.toolSeo.loadFormKeyword');
            Route::get('/createKeyword', [AdminToolSeoController::class, 'createKeyword'])->name('admin.toolSeo.createKeyword');
            Route::get('/deleteKeyword', [AdminToolSeoController::class, 'deleteKeyword'])->name('admin.toolSeo.deleteKeyword');
            Route::get('/listCheckSeo', [AdminCheckSeoController::class, 'listCheckSeo'])->name('admin.toolSeo.listCheckSeo');
            Route::get('/loadDetailCheckSeo', [AdminCheckSeoController::class, 'loadDetailCheckSeo'])->name('admin.toolSeo.loadDetailCheckSeo');
        });
        /* ===== CATEGORY ===== */
        Route::prefix('category')->group(function(){
            Route::get('/', [AdminCategoryController::class, 'list'])->name('admin.category.list');
            Route::post('/create', [AdminCategoryController::class, 'create'])->name('admin.category.create');
            Route::get('/view', [AdminCategoryController::class, 'view'])->name('admin.category.view');
            Route::post('/update', [AdminCategoryController::class, 'update'])->name('admin.category.update');
            /* Delete AJAX */
            Route::get('/delete', [AdminCategoryController::class, 'delete'])->name('admin.category.delete');
        });
        /* ===== BLOG ===== */
        Route::prefix('blog')->group(function(){
            Route::get('/', [AdminBlogController::class, 'list'])->name('admin.blog.list');
            Route::post('/create', [AdminBlogController::class, 'create'])->name('admin.blog.create');
            Route::get('/view', [AdminBlogController::class, 'view'])->name('admin.blog.view');
            Route::post('/update', [AdminBlogController::class, 'update'])->name('admin.blog.update');
            /* Delete AJAX */
            Route::get('/delete', [AdminBlogController::class, 'delete'])->name('admin.blog.delete');
        });
        /* ===== BLOG ===== */
        Route::prefix('page')->group(function(){
            Route::get('/', [AdminPageController::class, 'list'])->name('admin.page.list');
            Route::post('/create', [AdminPageController::class, 'create'])->name('admin.page.create');
            Route::get('/view', [AdminPageController::class, 'view'])->name('admin.page.view');
            Route::post('/update', [AdminPageController::class, 'update'])->name('admin.page.update');
            /* Delete AJAX */
            Route::get('/delete', [AdminPageController::class, 'delete'])->name('admin.page.delete');
        });
        /* ===== REDIRECT ===== */
        Route::prefix('redirect')->group(function(){
            Route::get('/list', [AdminRedirectController::class, 'list'])->name('admin.redirect.list');
            Route::get('/create', [AdminRedirectController::class, 'create'])->name('admin.redirect.create');
            Route::get('/delete', [AdminRedirectController::class, 'delete'])->name('admin.redirect.delete');
        });
        /* ===== AJAX ===== */
        Route::post('/loadProvinceByRegion', [AdminFormController::class, 'loadProvinceByRegion'])->name('admin.form.loadProvinceByRegion');
        Route::post('/loadDistrictByProvince', [AdminFormController::class, 'loadDistrictByProvince'])->name('admin.form.loadDistrictByProvince');
        Route::post('/removeSlider', [AdminSliderController::class, 'removeSlider'])->name('admin.slider.removeSlider');
        Route::get('/removeGallery', [AdminGalleryController::class, 'removeGallery'])->name('admin.gallery.removeGallery');
        Route::get('/settingView', [AdminSettingController::class, 'settingView'])->name('admin.setting.settingView');
        /* ===== CACHE ===== */
        Route::get('/clearCacheHtml', [AdminCacheController::class, 'clear'])->name('admin.cache.clearCache');
    });
});

/* redirect */
foreach(\App\Models\Redirect::all() as $redirect){
    
    Route::get($redirect->url_old, function() use($redirect){ 
        return Redirect::to($redirect->url_new, 301); 
    });
}
/* cập nhật hàng loạt iamge loading trong content */
// Route::get('/changeImageInContentWithLoading', [HomeController::class, 'changeImageInContentWithLoading'])->name('main.changeImageInContentWithLoading');
// Route::get('/changeImageInContentWithLoadingTourInfo', [HomeController::class, 'changeImageInContentWithLoadingTourInfo'])->name('main.changeImageInContentWithLoadingTourInfo');

/* chạy test */
// Route::get('/runTest', [RunTestController::class, 'run'])->name('main.test.run');

Route::get('/', [HomeController::class, 'home'])->name('main.home');
Route::get('/error', [\App\Http\Controllers\ErrorController::class, 'handle'])->name('error.handle');
Route::get('/checkOnpageAll', [HomeController::class, 'checkOnpageAll'])->name('main.checkOnpageAll');
/* ===== SITEMAP ===== */
Route::get('sitemap.xml', [SitemapController::class, 'main'])->name('sitemap.main');
Route::get('sitemap/{type}.xml', [SitemapController::class, 'child'])->name('sitemap.child');
/* ===== SHIP BOOKING ===== */
Route::prefix('shipBooking')->group(function(){
    Route::get('/form', [ShipBookingController::class, 'form'])->name('main.shipBooking.form');
    Route::post('/create', [ShipBookingController::class, 'create'])->name('main.shipBooking.create');
    Route::get('/loadShipLocation', [ShipBookingController::class, 'loadShipLocation'])->name('main.shipBooking.loadShipLocation');
    Route::get('/loadDeparture', [ShipBookingController::class, 'loadDeparture'])->name('main.shipBooking.loadDeparture');
    Route::get('/loadBookingSummary', [ShipBookingController::class, 'loadBookingSummary'])->name('main.shipBooking.loadBookingSummary');
    Route::get('/confirm', [ShipBookingController::class, 'confirm'])->name('main.shipBooking.confirm');
});
/* ===== TOUR BOOKING ===== */
Route::prefix('tourBooking')->group(function(){
    Route::get('/form', [TourBookingController::class, 'form'])->name('main.tourBooking.form');
    Route::post('/create', [TourBookingController::class, 'create'])->name('main.tourBooking.create');
    Route::get('/loadTour', [TourBookingController::class, 'loadTour'])->name('main.tourBooking.loadTour');
    Route::get('/loadOptionTour', [TourBookingController::class, 'loadOptionTour'])->name('main.tourBooking.loadOptionTour');
    Route::get('/loadFormQuantityByOption', [TourBookingController::class, 'loadFormQuantityByOption'])->name('main.tourBooking.loadFormQuantityByOption');
    Route::get('/loadBookingSummary', [TourBookingController::class, 'loadBookingSummary'])->name('main.tourBooking.loadBookingSummary');
    Route::get('/confirm', [TourBookingController::class, 'confirm'])->name('main.tourBooking.confirm');
});
/* ===== TOC CONTENT ===== */
Route::get('/buildTocContentSidebar', [AjaxController::class, 'buildTocContentSidebar'])->name('main.buildTocContentSidebar');
Route::get('/buildTocContentMain', [AjaxController::class, 'buildTocContentMain'])->name('main.buildTocContentMain');
/* ===== ROUTING ALL ===== */
Route::get("/{slug}/{slug2?}/{slug3?}/{slug4?}/{slug5?}/{slug6?}/{slug7?}/{slug8?}/{slug9?}/{slug10?}", [RoutingController::class, 'routing'])->name('routing');