<?php

declare(strict_types=1);

use App\Models\Product;
use App\Orchid\Screens\Examples\ExampleCardsScreen;
use App\Orchid\Screens\Examples\ExampleChartsScreen;
use App\Orchid\Screens\Examples\ExampleFieldsAdvancedScreen;
use App\Orchid\Screens\Examples\ExampleFieldsScreen;
use App\Orchid\Screens\Examples\ExampleLayoutsScreen;
use App\Orchid\Screens\Examples\ExampleScreen;
use App\Orchid\Screens\Examples\ExampleTextEditorsScreen;
use App\Orchid\Screens\PlatformScreen;
use App\Orchid\Screens\Role\RoleEditScreen;
use App\Orchid\Screens\Role\RoleListScreen;
use App\Orchid\Screens\User\UserEditScreen;
use App\Orchid\Screens\User\UserListScreen;
use App\Orchid\Screens\User\UserProfileScreen;
use App\Orchid\Screens\PresentationEditScreen;
use App\Orchid\Screens\PresentationListScreen;
use App\Orchid\Screens\PresentationScreen;
use App\Orchid\Screens\StockScreen;
use App\Orchid\Screens\StockEditScreen;
use App\Orchid\Screens\StockListScreen;
use App\Orchid\Screens\ProductScreen;
use App\Orchid\Screens\ProductEditScreen;
use App\Orchid\Screens\ProductListScreen;
use App\Orchid\Screens\OrderScreen;
use App\Orchid\Screens\OrderListScreen;
use Illuminate\Support\Facades\Route;
use Tabuna\Breadcrumbs\Trail;

/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the need "dashboard" middleware group. Now create something great!
|
*/

// Main
Route::screen('/main', PlatformScreen::class)
    ->name('platform.main');

// Platform > Profile
Route::screen('profile', UserProfileScreen::class)
    ->name('platform.profile')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Profile'), route('platform.profile')));

// Platform > System > Users > User
Route::screen('users/{user}/edit', UserEditScreen::class)
    ->name('platform.systems.users.edit')
    ->breadcrumbs(fn (Trail $trail, $user) => $trail
        ->parent('platform.systems.users')
        ->push($user->name, route('platform.systems.users.edit', $user)));

// Platform > System > Users > Create
Route::screen('users/create', UserEditScreen::class)
    ->name('platform.systems.users.create')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.systems.users')
        ->push(__('Create'), route('platform.systems.users.create')));

// Platform > System > Users
Route::screen('users', UserListScreen::class)
    ->name('platform.systems.users')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Users'), route('platform.systems.users')));

// Platform > System > Roles > Role
Route::screen('roles/{role}/edit', RoleEditScreen::class)
    ->name('platform.systems.roles.edit')
    ->breadcrumbs(fn (Trail $trail, $role) => $trail
        ->parent('platform.systems.roles')
        ->push($role->name, route('platform.systems.roles.edit', $role)));

// Platform > System > Roles > Create
Route::screen('roles/create', RoleEditScreen::class)
    ->name('platform.systems.roles.create')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.systems.roles')
        ->push(__('Create'), route('platform.systems.roles.create')));

// Platform > System > Roles
Route::screen('roles', RoleListScreen::class)
    ->name('platform.systems.roles')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Roles'), route('platform.systems.roles')));

// Example...
Route::screen('example', ExampleScreen::class)
    ->name('platform.example')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push('Example screen'));

Route::screen('example-fields', ExampleFieldsScreen::class)->name('platform.example.fields');
Route::screen('example-layouts', ExampleLayoutsScreen::class)->name('platform.example.layouts');
Route::screen('example-charts', ExampleChartsScreen::class)->name('platform.example.charts');
Route::screen('example-editors', ExampleTextEditorsScreen::class)->name('platform.example.editors');
Route::screen('example-cards', ExampleCardsScreen::class)->name('platform.example.cards');
Route::screen('example-advanced', ExampleFieldsAdvancedScreen::class)->name('platform.example.advanced');

//Route::screen('idea', Idea::class, 'platform.screens.idea');
//presentation
Route::screen('presentation', PresentationScreen::class)->name('platform.presentation')
->breadcrumbs(function (Trail $trail){
    return $trail
        ->parent('platform.index')
        ->push('Presentaciones de producto');
});

Route::screen('presentation/{presentation?}', PresentationEditScreen::class)
    ->name('platform.presentation.edit');


Route::screen('presentations', PresentationListScreen::class)
    ->name('platform.presentation.list');


//stock
Route::screen('stock', StockScreen::class)->name('platform.stock')
->breadcrumbs(function (Trail $trail){
    return $trail
        ->parent('platform.index')
        ->push('Inventario');
});

Route::screen('stock/{stock?}', StockEditScreen::class)
    ->name('platform.stock.edit');

Route::screen('stocks', StockListScreen::class)
    ->name('platform.stock.list');

//Product

Route::screen('product', ProductScreen::class)->name('platform.product')
->breadcrumbs(function (Trail $trail){
    return $trail
        ->parent('platform.index')
        ->push('productos');
});

Route::screen('product/{product?}', ProductEditScreen::class)
    ->name('platform.product.edit');


Route::screen('products', ProductListScreen::class)
    ->name('platform.product.list');

//  Order

Route::screen('order', OrderScreen::class)->name('platform.order')
->breadcrumbs(function (Trail $trail){
    return $trail
        ->parent('platform.index')
        ->push('ordenes');
});

Route::screen('orders', OrderListScreen::class)
    ->name('platform.order.list');
