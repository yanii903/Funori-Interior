<?php

namespace App\Http\Controllers\Admin;

use App\Models\AttributeValue;
use App\Models\Attribute;
use Illuminate\Http\Request;

class AttributeController
{
    public function index(Request $request)
    {
        $query = AttributeValue::with('attribute');
        if ($request->filled('name')) {
            $query->whereHas('attribute', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->name . '%');
            })->orWhere('value', 'like', '%' . $request->name . '%');
        }
        $attributes = $query->paginate(20)->appends($request->all());
        return view('admin.attributes.index', compact('attributes'));
    }
    public function create()
    {
        $attributes = Attribute::all();
        return view('admin.attributes.create', compact('attributes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'attribute_id' => 'required|exists:attributes,id',
            'value' => 'required|string|max:255',
        ]);

        AttributeValue::create([
            'attribute_id' => $request->attribute_id,
            'value' => $request->value,
        ]);

        return redirect()->route('admin.attributes.index')->with('success', 'Attribute value created successfully.');
    }

    public function edit($id)
    {
        $attributeValue = AttributeValue::findOrFail($id);
        $attributes = Attribute::all();
        return view('admin.attributes.edit', compact('attributeValue', 'attributes'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'attribute_id' => 'required|exists:attributes,id',
            'value' => 'required|string|max:255',
        ]);

        $attributeValue = AttributeValue::findOrFail($id);
        $attributeValue->update([
            'attribute_id' => $request->attribute_id,
            'value' => $request->value,
        ]);

        return redirect()->route('admin.attributes.index')->with('success', 'Attribute value updated successfully.');
    }

    public function destroy($id)
    {
        $attributeValue = AttributeValue::findOrFail($id);
        $attributeValue->delete();

        return redirect()->route('admin.attributes.index')->with('success', 'Attribute value deleted successfully.');
    }
}
