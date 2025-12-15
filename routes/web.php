<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Auth\Recover;
use App\Livewire\Auth\Reset;
use App\Livewire\Dashboard\Home;
use App\Livewire\Dashboard\Tickets\ListTickets;
use App\Livewire\Dashboard\Tickets\CreateTicket;
use App\Livewire\Dashboard\Tickets\ManageTicket;
use App\Livewire\Tickets\ListTickets as ClientListTickets;
use App\Livewire\Tickets\CreateTicket as ClientCreateTicket;
use App\Livewire\Tickets\ManageTicket as ClientManageTicket;
use App\Livewire\Orders\ListOrders as ClientListOrders;
use App\Livewire\Orders\CreateOrder as ClientCreateOrder;
use App\Livewire\Orders\ManageOrder as ClientManageOrder;
use App\Livewire\Dashboard\Orders\ListOrders;
use App\Livewire\Dashboard\Orders\CreateOrder;
use App\Livewire\Dashboard\Orders\ManageOrder;
use App\Livewire\Dashboard\CannedReplies\ListCannedReplies;
use App\Livewire\Dashboard\Admin\Users\ListUsers;
use App\Livewire\Dashboard\Admin\Settings;
use App\Livewire\Dashboard\Admin\UserRoles\ListUserRoles;
use App\Livewire\Dashboard\Admin\Departments\ListDepartments;
use App\Livewire\Dashboard\Admin\Labels\ListLabels;
use App\Livewire\Dashboard\Admin\Priorities\ListPriorities;
use App\Livewire\Dashboard\Admin\Statuses\ListStatuses;
use App\Livewire\Dashboard\Admin\Languages\ListLanguages;
use App\Livewire\Dashboard\Admin\Branches\ListBranches;
use App\Livewire\Dashboard\Admin\Brands\ListBrands;
use App\Livewire\Dashboard\Admin\Stock\ListStocks;

Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return redirect()->route('login');
    });

    Route::get('login', Login::class)->name('login');
    Route::get('register', Register::class)->name('register');
    Route::get('auth/recover', Recover::class)->name('password.request');
    Route::get('auth/reset/{token}', Reset::class)->name('password.reset');

    // Legacy support
    Route::get('auth/login', function() { return redirect()->route('login'); });
    Route::get('auth/register', function() { return redirect()->route('register'); });
});

Route::middleware('auth')->group(function () { 
    Route::get('/dashboard', Home::class)->name('dashboard.home');
    Route::get('/dashboard/home', function() { return redirect()->route('dashboard.home'); });

    // Profile
    Route::get('/profile', \App\Livewire\Profile::class)->name('profile');
    
    // Dashboard Routes
    Route::prefix('dashboard')->name('dashboard.')->group(function() {
        // Tickets
        Route::get('/tickets', ListTickets::class)->name('tickets.list');
        Route::get('/tickets/create', CreateTicket::class)->name('tickets.create');
        Route::get('/tickets/{ticket}', ManageTicket::class)->name('tickets.manage');
        
        // Orders
        Route::get('/orders', ListOrders::class)->name('orders.list');
        Route::get('/orders/create', CreateOrder::class)->name('orders.create');
        Route::get('/orders/{order}', ManageOrder::class)->name('orders.manage');

        // Canned Replies
        Route::get('/canned-replies', ListCannedReplies::class)->name('canned-replies.list');
    });

    // Client/Customer Routes
    Route::get('/tickets', ClientListTickets::class)->name('tickets.list');
    Route::get('/tickets/create', ClientCreateTicket::class)->name('tickets.create');
    Route::get('/tickets/{ticket}', ClientManageTicket::class)->name('tickets.manage');
    
    Route::get('/orders', ClientListOrders::class)->name('orders.list');
    Route::get('/orders/create', ClientCreateOrder::class)->name('orders.create');
    Route::get('/orders/{order}', ClientManageOrder::class)->name('orders.manage');

    // Admin
    Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/users', ListUsers::class)->name('users.list');
        Route::get('/user-roles', ListUserRoles::class)->name('user-roles.list');
        Route::get('/departments', ListDepartments::class)->name('departments.list');
        Route::get('/labels', ListLabels::class)->name('labels.list');
        Route::get('/priorities', ListPriorities::class)->name('priorities.list');
        Route::get('/statuses', ListStatuses::class)->name('statuses.list');
        Route::get('/languages', ListLanguages::class)->name('languages.list');
        Route::get('/branches', ListBranches::class)->name('branches.list');
        Route::get('/brands', ListBrands::class)->name('brands.list');
        Route::get('/stocks', ListStocks::class)->name('stocks.list');
        Route::get('/settings', Settings::class)->name('settings');
    });



    Route::post('/logout', function () {
        Illuminate\Support\Facades\Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect('/');
    })->name('logout');
});

Route::get('locale/{locale}', [App\Http\Controllers\LocaleController::class, 'change'])->name('locale.change');

