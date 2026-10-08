<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ServiceController as FrontendServiceController;
use App\Http\Controllers\Frontend\AdoptionController;
use App\Http\Controllers\Frontend\VaccinationController;
use App\Http\Controllers\Frontend\AppointmentController;
use App\Http\Controllers\Frontend\DiagnosticBookingController;
use App\Http\Controllers\Frontend\NutritionController;
use App\Http\Controllers\SeoController;
use App\Http\Controllers\Admin\{
    AuthController,
    DashboardController,
    BlogController as AdminBlogController,
    ContactController as AdminContactController,
    SliderController,
    GalleryController,
    ServiceController as AdminServiceController,
    CityController,
    VaccinationTypeController,
    BookingController,
    TestTypeController,
    TestBookingController,
    PageController as AdminPageController,
    ServiceCitySeoController,
    SettingController,
    PetAdoptionController,
    AdoptionBookingController,
    FaqController,
    TestimonialController,
    AnimalTypeController,
    FounderController,
    PetTypeController,
    PetTypeForNutritionController,
    NutritionProductController,
    AppointmentBookingController,
    AppointmentTypeController
};
use App\Models\AppointmentType;

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

// Redirect login to admin login since this is an admin-only app
Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [HomeController::class, 'contactStore'])->middleware('throttle:5,1')->name('contact.store');
Route::get('/blogs', [HomeController::class, 'blogs'])->name('blogs');
Route::get('/services', [HomeController::class, 'services'])->name('services');
Route::get('/blog/{slug}', [HomeController::class, 'blogDetail'])->name('blog.detail');
// Vaccination booking — AJAX-based with OTP and Razorpay support
Route::get('/vaccination-booking',             [VaccinationController::class, 'index'])->name('vaccination');
Route::post('/vaccination-booking',            [VaccinationController::class, 'store'])->middleware('throttle:5,1')->name('vaccination.store');
Route::post('/vaccination-booking/send-otp',   [VaccinationController::class, 'sendOtp'])->middleware('throttle:3,1')->name('vaccination.send-otp');
Route::post('/vaccination-booking/verify-otp', [VaccinationController::class, 'verifyOtp'])->name('vaccination.verify-otp');
Route::post('/vaccination-booking/verify-payment', [VaccinationController::class, 'verifyPayment'])->name('vaccination.verify-payment');

// Appointment booking — AJAX-based with OTP and Razorpay support
Route::get('/appointment-booking',                          [AppointmentController::class, 'index'])->name('appointment');
Route::get('/appointment-booking/{appointmentBooking}',     [AppointmentController::class, 'show'])->name('appointment.show');
Route::post('/appointment-booking',                         [AppointmentController::class, 'store'])->middleware('throttle:5,1')->name('appointment.store');
Route::post('/appointment-booking/send-otp',                [AppointmentController::class, 'sendOtp'])->middleware('throttle:3,1')->name('appointment.send-otp');
Route::post('/appointment-booking/verify-otp',              [AppointmentController::class, 'verifyOtp'])->name('appointment.verify-otp');
Route::post('/appointment-booking/verify-payment',          [AppointmentController::class, 'verifyPayment'])->name('appointment.verify-payment');

// Diagnostic test booking
Route::get('/diagnostic-booking',                          [DiagnosticBookingController::class, 'index'])->name('diagnostic.booking');
Route::get('/diagnostic-booking/available-tests',          [DiagnosticBookingController::class, 'availableTests'])->name('diagnostic.booking.tests');
Route::get('/diagnostic-booking/available-pet-types',      [DiagnosticBookingController::class, 'availablePetTypes'])->name('diagnostic.booking.pet-types');
Route::post('/diagnostic-booking',                         [DiagnosticBookingController::class, 'store'])->middleware('throttle:5,1')->name('diagnostic.booking.store');
Route::post('/diagnostic-booking/send-otp',                [DiagnosticBookingController::class, 'sendOtp'])->middleware('throttle:3,1')->name('diagnostic.send-otp');
Route::post('/diagnostic-booking/verify-otp',              [DiagnosticBookingController::class, 'verifyOtp'])->name('diagnostic.verify-otp');
Route::post('/diagnostic-booking/verify-payment',          [DiagnosticBookingController::class, 'verifyPayment'])->name('diagnostic.verify-payment');

Route::get('/cities/{slug}', [HomeController::class, 'cityDetail'])->name('city.detail');
Route::get('/page/{slug}', [HomeController::class, 'page'])->name('page');

// Service detail
Route::get('/services/{slug}', [FrontendServiceController::class, 'show'])->name('service.show');

// Service-City SEO landing pages (e.g., /pet-grooming-services-in-mohali)
Route::get('/{serviceSlug}-services-in-{citySlug}', [FrontendServiceController::class, 'showServiceCity'])
    ->name('services.city')
    ->where('serviceSlug', '[a-z0-9\-]+')
    ->where('citySlug', '[a-z0-9\-]+');

Route::get('/seo/{customSlug}', [FrontendServiceController::class, 'showCustomSeo'])
    ->name('services.custom');
    
// FAQs page
Route::get('/faqs', [HomeController::class, 'faqs'])->name('faqs');
Route::patch('/admin/faqs/{faq}/toggle-status', [FaqController::class, 'toggleStatus'])
    ->name('admin.faqs.toggle-status');
    
// Pet adoption
Route::get('/adoption',               [AdoptionController::class, 'index'])->name('adoption.index');
Route::get('/adoption/{slug}',        [AdoptionController::class, 'show'])->name('adoption.show');
Route::post('/adoption/send-otp',     [AdoptionController::class, 'sendOtp'])->middleware('throttle:3,1')->name('adoption.send-otp');
Route::post('/adoption/verify-otp',   [AdoptionController::class, 'verifyOtp'])->name('adoption.verify-otp');
Route::post('/adoption/book',         [AdoptionController::class, 'book'])->middleware('throttle:5,1')->name('adoption.book');
Route::post('/adoption/verify-payment', [AdoptionController::class, 'verifyPayment'])->name('adoption.verify-payment');

// Pet nutrition
Route::get('/pet-nutrition', [NutritionController::class, 'index'])->name('pet.nutrition');

/*
|--------------------------------------------------------------------------
| SEO Routes
|--------------------------------------------------------------------------
*/
Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [SeoController::class, 'robots'])->name('robots');
Route::get('/llm.txt', [SeoController::class, 'llmTxt'])->name('llm.txt');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        // Admin authentication routes
        Route::get('login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('login', [AuthController::class, 'login']);

        Route::get('forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
        Route::post('forgot-password', [AuthController::class, 'sendResetOtp'])->name('password.email');
        Route::get('verify-otp', [AuthController::class, 'showVerifyOtp'])->name('password.verify-otp');
        Route::post('verify-otp', [AuthController::class, 'verifyOtp'])->name('password.verify-otp.post');
        Route::get('reset-password', [AuthController::class, 'showResetForm'])->name('password.reset-form');
        Route::post('reset-password', [AuthController::class, 'resetPasswordOtp'])->name('password.update');
    });

    Route::middleware('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');

        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('pending', [DashboardController::class, 'allPending'])->name('pending');

        Route::resource('blogs', AdminBlogController::class);
        Route::patch('blogs/{blog}/toggle-slider', [AdminBlogController::class, 'toggleSlider'])->name('blogs.toggle-slider');
        Route::resource('sliders', SliderController::class);
        Route::resource('galleries', GalleryController::class);
        Route::patch('galleries/{gallery}/toggle-home', [GalleryController::class, 'toggleHome'])->name('galleries.toggle-home');
        Route::resource('services', AdminServiceController::class);
        Route::resource('cities', CityController::class);
        Route::resource('vaccination-types', VaccinationTypeController::class);
        Route::resource('pet-types', PetTypeController::class);
        Route::resource('pet-types-for-nutrition', PetTypeForNutritionController::class)
            ->parameters(['pet-types-for-nutrition' => 'petType']);
        Route::resource('nutrition-products', NutritionProductController::class);
        Route::resource('test-types', TestTypeController::class);

        Route::get('bookings', [BookingController::class, 'index'])->name('bookings.index');
        Route::get('bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
        Route::patch('bookings/{booking}/status', [BookingController::class, 'updateStatus'])->name('bookings.update-status');

        Route::get('test-bookings', [TestBookingController::class, 'index'])->name('test-bookings.index');
        Route::get('test-bookings/{booking}', [TestBookingController::class, 'show'])->name('test-bookings.show');
        Route::patch('test-bookings/{booking}/status', [TestBookingController::class, 'updateStatus'])->name('test-bookings.update-status');

        Route::resource('pages', AdminPageController::class);

        // Service-City SEO Pages Management
        Route::prefix('service-city-seo')->name('service-city-seo.')->group(function () {
            Route::get('/', [ServiceCitySeoController::class, 'index'])->name('index');
            Route::get('/create', [ServiceCitySeoController::class, 'create'])->name('create');
            Route::post('/', [ServiceCitySeoController::class, 'store'])->name('store');
            Route::get('/{serviceCitySeo}', [ServiceCitySeoController::class, 'show'])->name('show');
            Route::get('/{serviceCitySeo}/edit', [ServiceCitySeoController::class, 'edit'])->name('edit');
            Route::put('/{serviceCitySeo}', [ServiceCitySeoController::class, 'update'])->name('update');
            Route::delete('/{serviceCitySeo}', [ServiceCitySeoController::class, 'destroy'])->name('destroy');
            Route::patch('/{serviceCitySeo}/toggle-active', [ServiceCitySeoController::class, 'toggleActive'])->name('toggle-active');
            Route::post('/bulk-generate', [ServiceCitySeoController::class, 'bulkGenerate'])->name('bulk-generate');
            Route::get('/stats/export', [ServiceCitySeoController::class, 'exportStats'])->name('stats.export');
        });

        Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
        Route::put('settings', [SettingController::class, 'update'])->name('settings.update');
        Route::post('settings/test-razorpay', [SettingController::class, 'testRazorpay'])->name('settings.test-razorpay');
        Route::post('settings/test-mail', [SettingController::class, 'testMail'])->name('settings.test-mail');
        Route::get('integrations', [SettingController::class, 'integrations'])->name('integrations.index');
        Route::put('integrations', [SettingController::class, 'updateIntegrations'])->name('integrations.update');
        Route::get('account', [SettingController::class, 'account'])->name('account');
        Route::put('account/email', [SettingController::class, 'updateEmail'])->name('account.update-email');
        Route::put('account/password', [SettingController::class, 'updatePassword'])->name('account.update-password');
        Route::put('account/smtp', [SettingController::class, 'updateSmtp'])->name('account.update-smtp');
        Route::put('account/notifications', [SettingController::class, 'updateNotifications'])->name('account.update-notifications');

        // Pet Adoptions
        Route::resource('pet-adoptions', PetAdoptionController::class);
        Route::resource('appointment-types', AppointmentTypeController::class);
        // Appointment Bookings
        Route::get('appointment-bookings',                                [AppointmentBookingController::class, 'index'])->name('appointment-bookings.index');
        Route::get('appointment-bookings/{appointmentBooking}',           [AppointmentBookingController::class, 'show'])->name('appointment-bookings.show');
        Route::patch('appointment-bookings/{appointmentBooking}/status',  [AppointmentBookingController::class, 'updateStatus'])->name('appointment-bookings.update-status');

        // Adoption Bookings
        Route::get('adoption-bookings',                          [AdoptionBookingController::class, 'index'])->name('adoption-bookings.index');
        Route::get('adoption-bookings/{adoptionBooking}',        [AdoptionBookingController::class, 'show'])->name('adoption-bookings.show');
        Route::patch('adoption-bookings/{adoptionBooking}/status', [AdoptionBookingController::class, 'updateStatus'])->name('adoption-bookings.update-status');

        // Contact Messages
        Route::get('contacts', [AdminContactController::class, 'index'])->name('contacts.index');
        Route::get('contacts/{contact}', [AdminContactController::class, 'show'])->name('contacts.show');
        Route::delete('contacts/{contact}', [AdminContactController::class, 'destroy'])->name('contacts.destroy');
        Route::patch('contacts/{contact}/mark-read', [AdminContactController::class, 'markRead'])->name('contacts.mark-read');
        Route::post('contacts/mark-all-read', [AdminContactController::class, 'markAllRead'])->name('contacts.mark-all-read');

        // FAQs, Testimonials and Animal Types
        Route::resource('faqs', FaqController::class);
        Route::resource('testimonials', TestimonialController::class);
        Route::resource('animal-types', AnimalTypeController::class);

        // Founders (About Us)
        Route::resource('founders', FounderController::class);
    });
});
