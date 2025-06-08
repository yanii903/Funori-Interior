<?php

use App\Http\Controllers\Admin\PaymentMethodController;
use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController; // Đảm bảo dòng này đã được thêm
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\admin\ReviewController;
use App\Http\Controllers\Admin\ShippingMethodController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('admin.index');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return view('admin.index');
    })->name('dashboard');
    // Payment Methods
    Route::get('/payment-methods', [PaymentMethodController::class, 'index'])->name('payment_methods.index');
    Route::get('/payment-methods/create', [PaymentMethodController::class, 'create'])->name('payment_methods.create');
    Route::post('/payment-methods', [PaymentMethodController::class, 'store'])->name('payment_methods.store');
    Route::delete('/payment-methods/{id}', [PaymentMethodController::class, 'destroy'])->name('payment_methods.destroy');
    Route::put('/payment-methods/{id}/toggle', [PaymentMethodController::class, 'toggle'])->name('payment_methods.toggle');
    // Shipping Methods
    Route::get('/shipping-methods', [ShippingMethodController::class, 'index'])->name('shipping_methods.index');
    Route::get('/shipping-methods/create', [ShippingMethodController::class, 'create'])->name('shipping_methods.create');
    Route::post('/shipping-methods', [ShippingMethodController::class, 'store'])->name('shipping_methods.store');
    Route::delete('/shipping-methods/{id}', [ShippingMethodController::class, 'destroy'])->name('shipping_methods.destroy');
    Route::patch('/shipping-methods/{id}/deactivate', [ShippingMethodController::class, 'deactivate'])->name('shipping_methods.deactivate');
    Route::patch('/shipping-methods/{id}/activate', [ShippingMethodController::class, 'activate'])->name('shipping_methods.activate');

    Route::resource('products', ProductController::class);
    Route::resource('attributes', AttributeController::class);
    Route::resource('users', UserController::class);

    Route::resource('categories', CategoryController::class);
    Route::resource('contacts', ContactController::class);
    Route::resource('pages', PageController::class);
    Route::resource('reviews', ReviewController::class);

    // Quản lý user

    Route::get('admin/users/{user}', [UserController::class, 'show'])->name('admin.users.show');

    Route::post('users/{id}/reset-password', [UserController::class, 'resetPassword'])->name('users.resetPassword');

    Route::get('users/{user}/order-history', [UserController::class, 'orderHistory'])->name('users.orderHistory');

    // Quản lý đơn hàng
    // (1) Xem danh sách đơn hàng, hỗ trợ filter theo trạng thái, tìm kiếm
    Route::get('orders', [OrderController::class, 'index'])
        ->name('orders.index');

    // (2) Xem chi tiết một đơn hàng
    Route::get('orders/{order}', [OrderController::class, 'show'])
        ->name('orders.show');

    // (3) Hiển thị form sửa đơn hàng (chỉnh thông tin, cập nhật toàn bộ)
    Route::get('orders/{order}/edit', [OrderController::class, 'edit'])
        ->name('orders.edit');

    // (4) Cập nhật toàn bộ thông tin đơn hàng (store & update các trường order)
    Route::put('orders/{order}', [OrderController::class, 'update'])
        ->name('orders.update');

    // (5) Xóa đơn hàng
    Route::delete('orders/{order}', [OrderController::class, 'destroy'])
        ->name('orders.destroy');

    // (6) Cập nhật trạng thái riêng (VD: processing → shipped → delivered → cancelled → returned)
    Route::post('orders/{order}/update-status', [OrderController::class, 'updateStatus'])
        ->name('orders.updateStatus');

    // (6.1) Trang tracking trạng thái đơn hàng (form cập nhật trạng thái riêng)
    Route::get('orders/{order}/tracking', [OrderController::class, 'tracking'])
        ->name('orders.tracking');

    // (7) Xử lý yêu cầu hủy đơn (khách hàng đã gửi “request cancel”), admin duyệt/không duyệt
    Route::post('orders/{order}/process-cancel', [OrderController::class, 'processCancel'])
        ->name('orders.processCancel');

    // (8) In hóa đơn (HTML hoặc PDF)
    Route::get('orders/{order}/print-invoice', [OrderController::class, 'printInvoice'])
        ->name('orders.printInvoice');

    // (9) In phiếu giao hàng
    Route::get('orders/{order}/print-shipping', [OrderController::class, 'printShipping'])
        ->name('orders.printShipping');
});
