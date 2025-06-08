<?php

namespace App\Http\Controllers\Admin;

use App\Models\ShippingMethod;
use Illuminate\Http\Request;

class ShippingMethodController
{
    public function index()
    {
        $methods = ShippingMethod::all();
        return view('admin.shipping_method.index', compact('methods'));
     
    }
    public function create()
    {
        return view('admin.shipping_method.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'cost' => 'required|numeric|min:0',
        ]);

        $method = ShippingMethod::create($request->all());

        return redirect()->back()->with('success', 'Đã thêm phương thức giao hàng');
    }

    public function destroy($id)
    {
        $method = ShippingMethod::findOrFail($id);
        $method->delete();

        return redirect()->back()->with('success', 'Đã xóa phương thức giao hàng');
    }
    public function deactivate($id)
    {
        $method = ShippingMethod::findOrFail($id);
        $method->is_active = false;
        $method->save();

        return redirect()->back()->with('success', 'Đã ẩn phương thức giao hàng');
    }

    public function activate($id)
    {
        $method = ShippingMethod::findOrFail($id);
        $method->is_active = true;
        $method->save();

        return redirect()->back()->with('success', 'Đã kích hoạt phương thức giao hàng');
    }


}
