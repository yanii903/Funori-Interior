<?php 
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
class PaymentMethodController
{
    public function index()
    {
        $methods = PaymentMethod::all();
        return view('admin.payment_method.index', compact('methods'));
    }
    public function create()
    {
        return view('admin.payment_method.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:50|unique:payment_methods,code',
            'description' => 'nullable|string',
            'instructions' => 'nullable|string',
        ]);

        PaymentMethod::create($request->all());

        return redirect()->back()->with('success', 'Đã thêm phương thức thanh toán!');
    }

    public function destroy($id)
    {
        PaymentMethod::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Đã xóa!');
    }

    public function toggle($id)
    {
        $method = PaymentMethod::findOrFail($id);
        $method->is_active = !$method->is_active;
        $method->save();

        return redirect()->back()->with('success', 'Trạng thái đã được cập nhật!');
    }
}
