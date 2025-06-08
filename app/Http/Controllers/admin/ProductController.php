<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Attribute;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand', 'images', 'variants']);

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        $products = $query->paginate(20)->appends($request->all());
        return view('admin.products.index', compact('products'));
    }
    public function show($id)
    {
        $product = Product::with([
            'category',
            'brand',
            'images',
            'variants.attributeValues.attribute'
        ])->findOrFail($id);

        return view('admin.products.detail', compact('product'));
    }
    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();
        $productImages = [];
        $attributes = Attribute::with('values')->get();
        return view('admin.products.create', compact('categories', 'brands', 'attributes', 'productImages'));
    }
    public function edit($id)
    {
        $product = Product::with(['images', 'variants.attributeValues', 'variants.image'])->findOrFail($id);
        $categories = Category::all();
        $brands = Brand::all();
        $attributes = Attribute::with('values')->get();
        $productImages = $product->images; // Để chọn ảnh cho variant

        return view('admin.products.edit', compact('product', 'categories', 'brands', 'attributes', 'productImages'));
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->images()->delete();
        foreach ($product->variants as $variant) {
            $variant->attributeValues()->detach();
            $variant->delete();
        }
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully!');
    }
    public function store(Request $request)
    {
        // Validate sản phẩm cha
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'regular_price' => 'required|numeric|min:0',
            'short_description' => 'required|string|max:255',
            'description' => 'nullable|string',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $slug = Str::slug($validated['name']);
        $product = Product::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'category_id' => $validated['category_id'],
            'brand_id' => $validated['brand_id'],
            'regular_price' => $validated['regular_price'],
            'short_description' => $validated['short_description'],
            'description' => $request->description,
        ]);

        // Lưu ảnh gallery
        $galleryImageIds = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $fileName = uniqid() . '_' . time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images/products'), $fileName);
                $img = $product->images()->create([
                    'image_url' => 'images/products/' . $fileName,
                    'order' => 0
                ]);
                $galleryImageIds[] = $img->id;
            }
        }

        // Lưu variants
        if ($request->has('variants')) {
            foreach ($request->variants as $variant) {
                $variantImageId = null;
                if (isset($variant['image']) && $variant['image']) {
                    $file = $variant['image'];
                    $fileName = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('images/products'), $fileName);
                    $img = $product->images()->create([
                        'image_url' => 'images/products/' . $fileName,
                        'order' => 0
                    ]);
                    $variantImageId = $img->id;
                }
                $variantModel = $product->variants()->create([
                    'price_modifier' => $variant['price_modifier'] ?? 0,
                    'stock_quantity' => $variant['stock_quantity'] ?? 0,
                    'image_id' => $variantImageId,
                ]);
                // Lưu thuộc tính cho variant
                if (isset($variant['attribute_values'])) {
                    foreach ($variant['attribute_values'] as $attribute_id => $value_id) {
                        if ($value_id) {
                            $variantModel->attributeValues()->attach($value_id);
                        }
                    }
                }
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully!');
    }
    public function update(Request $request, $id)
    {
        $product = Product::with(['variants.attributeValues'])->findOrFail($id);
        if ($request->has('status')) {
            $product->status = $request->status;
            $product->save();
            return back()->with('success', 'Status updated!');
        }
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'regular_price' => 'required|numeric|min:0',
            'short_description' => 'required|string|max:255',
            'description' => 'nullable|string',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Cập nhật sản phẩm cha
        $product->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'category_id' => $validated['category_id'],
            'brand_id' => $validated['brand_id'],
            'regular_price' => $validated['regular_price'],
            'short_description' => $validated['short_description'],
            'description' => $request->description,
        ]);

        // Thêm ảnh mới vào gallery nếu có
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $fileName = uniqid() . '_' . time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images/products'), $fileName);
                $product->images()->create([
                    'image_url' => 'images/products/' . $fileName,
                    'order' => 0
                ]);
            }
        }

        // Xóa toàn bộ variants cũ và liên kết thuộc tính (cách đơn giản nhất)
        foreach ($product->variants as $variant) {
            $variant->attributeValues()->detach();
            $variant->delete();
        }

        // Lưu variants mới
        if ($request->has('variants')) {
            foreach ($request->variants as $variant) {
                $variantModel = $product->variants()->create([
                    'price_modifier' => $variant['price_modifier'] ?? 0,
                    'stock_quantity' => $variant['stock_quantity'] ?? 0,
                    'image_id' => $variant['image_id'] ?? null,
                ]);
                // Lưu thuộc tính cho variant
                if (isset($variant['attribute_values'])) {
                    foreach ($variant['attribute_values'] as $attribute_id => $value_id) {
                        if ($value_id) {
                            $variantModel->attributeValues()->attach($value_id);
                        }
                    }
                }
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully!');
    }
}
