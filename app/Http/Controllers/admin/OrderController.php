<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Str;

class OrderController
{
    // (1) index: danh sách đơn hàng
    public function index(Request $request)
    {
        $query = Order::query();

        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where('order_code', 'like', "%{$q}%")
                  ->orWhere('customer_name', 'like', "%{$q}%");
        }

        $orders = $query->orderBy('ordered_at', 'desc')
                        ->paginate(20)
                        ->appends($request->only(['status','q']));
        return view('admin.orders.index', compact('orders'));
    }

    

    // (2) show: xem chi tiết
    public function show($id)
    {
        $order = Order::with(['paymentMethod', 'shippingMethod', 'user', 'items.product'])
                      ->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    // (3) edit: hiển thị form sửa
    public function edit($id)
    {
        $order = Order::findOrFail($id);
        return view('admin.orders.edit', compact('order'));
    }

    // (4) update: lưu thay đổi chung
    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $request->validate([
            'customer_name'      => 'required|string|max:255',
            'customer_email'     => 'required|email|max:255',
            'customer_phone'     => 'required|string|max:20',
            'shipping_address'   => 'required|string',
            'subtotal_amount'    => 'required|numeric|min:0',
            'shipping_fee'       => 'required|numeric|min:0',
            'discount_amount'    => 'nullable|numeric|min:0',
            'tax_amount'         => 'nullable|numeric|min:0',
            'total_amount'       => 'required|numeric|min:0',
            'payment_method_id'  => 'required|exists:payment_methods,id',
            'payment_status'     => 'required|in:pending,paid,failed,refunded',
            'shipping_method_id' => 'required|exists:shipping_methods,id',
            'order_status'       => 'required|in:pending_confirmation,processing,shipped,delivered,cancelled,returned',
            'customer_note'      => 'nullable|string',
            'admin_note'         => 'nullable|string',
            'ordered_at'         => 'nullable|date',
            'delivered_at'       => 'nullable|date|after_or_equal:ordered_at',
            'cancelled_at'       => 'nullable|date',
            'cancellation_reason'=> 'nullable|string',
        ]);

        $data = $request->only([
            'user_id',
            'customer_name',
            'customer_email',
            'customer_phone',
            'shipping_address',
            'subtotal_amount',
            'shipping_fee',
            'discount_amount',
            'tax_amount',
            'total_amount',
            'payment_method_id',
            'payment_status',
            'shipping_method_id',
            'order_status',
            'customer_note',
            'admin_note',
            'ordered_at',
            'delivered_at',
            'cancelled_at',
            'cancellation_reason',
        ]);

        $order->update($data);

        return redirect()->route('admin.orders.index')
                         ->with('success', 'Cập nhật đơn hàng thành công.');
    }

    // (5) destroy: xóa đơn hàng
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();
        return redirect()->route('admin.orders.index')
                         ->with('success', 'Xóa đơn hàng thành công.');
    }

    /**
     * (6) updateStatus: Xử lý POST cập nhật riêng order_status
     *
     * - Nếu chuyển sang 'delivered' thì set delivered_at = now()
     * - Nếu chuyển sang 'cancelled' thì set cancelled_at = now() và có thể lấy cancellation_reason
     */
    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $request->validate([
            'order_status'        => 'required|in:pending_confirmation,processing,shipped,delivered,cancelled,returned',
            'admin_note'          => 'nullable|string',
            'cancellation_reason' => 'nullable|string|max:500',
        ]);

        $oldStatus = $order->order_status;
        $newStatus = $request->input('order_status');

        // Kiểm tra trạng thái chuyển tiếp hợp lệ
        $transitions = Order::getAllowedStatusTransitions();
        if (!in_array($newStatus, $transitions[$oldStatus] ?? [])) {
            return redirect()->route('admin.orders.tracking', $order->id)
                ->with('error', 'Không thể chuyển trạng thái từ ' . $oldStatus . ' sang ' . $newStatus . '.');
        }

        $order->order_status = $newStatus;

        // Cập nhật các mốc thời gian tương ứng
        if ($oldStatus !== $newStatus) {
            if ($newStatus === 'processing' && !$order->processing_at) {
                $order->processing_at = now();
            }
            if ($newStatus === 'shipped' && !$order->shipped_at) {
                $order->shipped_at = now();
            }
            if ($newStatus === 'delivered' && !$order->delivered_at) {
                $order->delivered_at = now();
            }
            if ($newStatus === 'cancelled' && !$order->cancelled_at) {
                $order->cancelled_at = now();
                if ($request->filled('cancellation_reason')) {
                    $order->cancellation_reason = $request->input('cancellation_reason');
                }
            }
            if ($newStatus === 'returned' && !$order->returned_at) {
                $order->returned_at = now();
            }
        }

        if ($request->filled('admin_note')) {
            $order->admin_note = $request->input('admin_note');
        }

        $order->save();

        return redirect()
            ->route('admin.orders.tracking', $order->id)
            ->with('success', 'Cập nhật trạng thái thành công.');
    }
    // (6.1) tracking: hiển thị form cập nhật trạng thái riêng
    public function tracking($id)
    {
        $order = \App\Models\Order::findOrFail($id);
        return view('admin.orders.tracking', compact('order'));
    }
    // (7) processCancel: xử lý yêu cầu hủy từ khách
    public function processCancel(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        if ($order->order_status !== 'pending_cancellation') {
            return redirect()->back()->with('error', 'Đơn hàng không chờ hủy.');
        }

        $action = $request->input('action');
        $adminNote = $request->input('admin_note_cancel');

        if ($action === 'approve') {
            $order->order_status = 'cancelled';
            $order->cancelled_at = now();
            $order->admin_note = $adminNote;
            $order->save();

            return redirect()->route('admin.orders.index')
                             ->with('success', 'Đã duyệt hủy đơn #' . $order->order_code);
        }

        if ($action === 'reject') {
            // Trả về trạng thái trước khi pending_cancellation (nếu có)
            $order->order_status = $order->previous_status ?? 'processing';
            $order->admin_note = $adminNote;
            $order->save();

            return redirect()->route('admin.orders.index')
                             ->with('success', 'Đã từ chối yêu cầu hủy đơn #' . $order->order_code);
        }

        return redirect()->back()->with('error', 'Thao tác không hợp lệ.');
    }

    // (8) printInvoice: hiển thị view HTML/Hóa đơn
    public function printInvoice($id)
    {
        $order = Order::with(['items.product', 'paymentMethod', 'shippingMethod', 'user'])
                      ->findOrFail($id);
        return view('admin.orders.print_invoice', compact('order'));
    }

    // (9) printShipping: hiển thị view HTML/Phiếu giao hàng
    public function printShipping($id)
    {
        $order = Order::with(['items.product', 'paymentMethod', 'shippingMethod', 'user'])
                      ->findOrFail($id);
        return view('admin.orders.print_shipping', compact('order'));
    }
}
