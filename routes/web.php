<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\DoctorManageController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\MedicineController as AdminMedicineController;
use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\Doctor\DashboardController as DoctorDashboard;
use App\Http\Controllers\Doctor\ConsultationController as DoctorConsultationController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\HealthCheckController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AiChatController;
use Illuminate\Support\Facades\Route;

// Static Asset Delivery Fallbacks for Vercel
Route::get('/css/app.css', function () {
    $path = public_path('css/app.css');
    if (file_exists($path)) {
        return response(file_get_contents($path), 200)
            ->header('Content-Type', 'text/css; charset=utf-8');
    }
    return response('', 404);
});

Route::get('/js/app.js', function () {
    $path = public_path('js/app.js');
    if (file_exists($path)) {
        return response(file_get_contents($path), 200)
            ->header('Content-Type', 'application/javascript; charset=utf-8');
    }
    return response('', 404);
});

// ─── Public Routes ─────────────────────────────────────────────────────────
Route::get('/', [LandingController::class, 'index'])->name('home');

// AI Chatbot Route (Groq Llama 3.3 70B)
Route::post('/ai-chat/message', [AiChatController::class, 'message'])->name('ai.chat');

// Doctors (public browsing)
Route::get('/dokter', [DoctorController::class, 'index'])->name('doctors.index');
Route::get('/dokter/{doctor}', [DoctorController::class, 'show'])->name('doctors.show');

// Medicines (public browsing)
Route::get('/apotek', [MedicineController::class, 'index'])->name('medicines.index');
Route::get('/apotek/{medicine}', [MedicineController::class, 'show'])->name('medicines.show');

// Health Check (public tools, save history if authenticated)
Route::prefix('cek-kesehatan')->name('health-check.')->group(function () {
    Route::get('/', [HealthCheckController::class, 'index'])->name('index');
    Route::get('/bmi', [HealthCheckController::class, 'bmi'])->name('bmi');
    Route::post('/bmi', [HealthCheckController::class, 'storeBmi'])->name('bmi.store');
    Route::get('/gejala', [HealthCheckController::class, 'symptomChecker'])->name('symptom');
    Route::post('/gejala', [HealthCheckController::class, 'storeSymptom'])->name('symptom.store');

    // 8 Additional KoLine Tools
    Route::get('/stres', [HealthCheckController::class, 'stresTest'])->name('stres');
    Route::get('/jantung', [HealthCheckController::class, 'jantungTest'])->name('jantung');
    Route::get('/diabetes', [HealthCheckController::class, 'diabetesTest'])->name('diabetes');
    Route::get('/depresi', [HealthCheckController::class, 'depresiTest'])->name('depresi');
    Route::get('/kecemasan', [HealthCheckController::class, 'kecemasanTest'])->name('kecemasan');
    Route::get('/menstruasi', [HealthCheckController::class, 'menstruasiTracker'])->name('menstruasi');
    Route::get('/pengingat-obat', [HealthCheckController::class, 'pengingatObat'])->name('pengingat-obat');
    Route::get('/kehamilan', [HealthCheckController::class, 'kehamilanCalculator'])->name('kehamilan');
    Route::get('/donasi', [HealthCheckController::class, 'donasiMedis'])->name('donasi');
});

// ─── Authenticated Routes ──────────────────────────────────────────────────
Route::middleware(['auth'])->group(function () {

    // Dashboard Dispatcher
    Route::get('/dashboard', function () {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        if (auth()->user()->isDoctor()) {
            return redirect()->route('doctor.dashboard');
        }
        return redirect()->route('home');
    })->name('dashboard');

    // Profile
    Route::get('/profil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profil', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profil', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Booking dokter (auth required)
    Route::post('/dokter/{doctor}/booking', [DoctorController::class, 'book'])->name('doctors.book');

    // Consultations
    Route::prefix('konsultasi')->name('consultations.')->group(function () {
        Route::get('/', [ConsultationController::class, 'index'])->name('index');
        Route::get('/{consultation}', [ConsultationController::class, 'show'])->name('show');
        Route::post('/{consultation}/pesan', [ConsultationController::class, 'sendMessage'])->name('message');
        Route::get('/{consultation}/pesan', [ConsultationController::class, 'messages'])->name('messages');
        Route::get('/{consultation}/pesan-baru', [ConsultationController::class, 'newMessages'])->name('newMessages');
        Route::match(['get', 'post'], '/{consultation}/konfirmasi', [ConsultationController::class, 'confirm'])->name('confirm');
        Route::match(['get', 'post'], '/{consultation}/selesai', [ConsultationController::class, 'complete'])->name('complete');
        Route::match(['get', 'post'], '/{consultation}/batal', [ConsultationController::class, 'cancel'])->name('cancel');
    });

    // Cart & Orders
    Route::prefix('keranjang')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('/tambah/{medicine}', [CartController::class, 'add'])->name('add');
        Route::delete('/hapus/{medicine}', [CartController::class, 'remove'])->name('remove');
        Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout');
        Route::post('/pesan', [CartController::class, 'placeOrder'])->name('order');
    });

    Route::get('/pesanan', [CartController::class, 'orders'])->name('orders.index');
    Route::get('/pesanan/{order}', [CartController::class, 'showOrder'])->name('orders.show');
    Route::match(['get', 'post'], '/pesanan/{order}/bayar', [CartController::class, 'payOrder'])->name('orders.pay');

    // ─── Doctor Routes ─────────────────────────────────────────────────────
    Route::middleware('role:doctor')->prefix('dashboard/dokter')->name('doctor.')->group(function () {
        Route::get('/', [DoctorDashboard::class, 'index'])->name('dashboard');
        Route::get('/poll', [DoctorDashboard::class, 'poll'])->name('consultations.poll');
        Route::get('/konsultasi', [DoctorConsultationController::class, 'index'])->name('consultations');
    });

    // ─── Admin Routes ──────────────────────────────────────────────────────
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminDashboard::class, 'index'])->name('dashboard');

        // User management
        Route::resource('users', AdminUserController::class);

        // Doctor management
        Route::resource('dokter', DoctorManageController::class);

        // Medicine management
        Route::resource('apotek', AdminMedicineController::class);
    });
});

require __DIR__ . '/auth.php';
