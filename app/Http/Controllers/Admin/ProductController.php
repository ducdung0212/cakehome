<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'images']);

        // Tìm kiếm theo tên
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Lọc theo danh mục
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Lọc theo trạng thái
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $products = $query->latest()->paginate(10);
        $categories = Category::all();

        return view('admin.pages.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.pages.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'category_id' => 'required|exists:categories,id',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'sale_price' => 'nullable|numeric|min:0',
                'stock' => 'required|integer|min:0',
                'status' => 'required|in:in_stock,out_of_stock',
                'unit' => 'required|string|max:50',
                'images' => 'nullable|array|max:5',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048'
            ], [
                'name.required' => 'Tên sản phẩm là bắt buộc.',
                'category_id.required' => 'Danh mục là bắt buộc.',
                'category_id.exists' => 'Danh mục không tồn tại.',
                'price.required' => 'Giá sản phẩm là bắt buộc.',
                'stock.required' => 'Số lượng kho là bắt buộc.',
                'unit.required' => 'Đơn vị tính là bắt buộc.',
                'images.*.image' => 'File phải là hình ảnh.',
                'images.*.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif, webp.',
                'images.*.max' => 'Kích thước hình ảnh không được vượt quá 2MB.'
            ]);

            // Tạo product
            $product = Product::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'category_id' => $request->category_id,
                'description' => $request->description,
                'price' => $request->price,
                'sale_price' => $request->sale_price,
                'stock' => $request->stock,
                'status' => $request->status,
                'unit' => $request->unit,
            ]);

            // Xử lý upload hình ảnh
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $fileName =Str::slug($request->name).'_'.now()->timestamp . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $imagePath = $image->storeAs('Products', $fileName, 'public');

                    ProductImage::create([
                        'product_id' => $product->id,
                        'image' => $imagePath
                    ]);
                }
            }

            return redirect()->route('admin.products.index')->with('success', 'Thêm sản phẩm thành công!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Đã xảy ra lỗi: ' . $th->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $product = Product::with('images')->findOrFail($id);
        $categories = Category::all();
        return view('admin.pages.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'category_id' => 'required|exists:categories,id',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'sale_price' => 'nullable|numeric|min:0',
                'stock' => 'required|integer|min:0',
                'status' => 'required|in:in_stock,out_of_stock',
                'unit' => 'required|string|max:50',
                'images' => 'nullable|array|max:5',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048'
            ], [
                'name.required' => 'Tên sản phẩm là bắt buộc.',
                'category_id.required' => 'Danh mục là bắt buộc.',
                'price.required' => 'Giá sản phẩm là bắt buộc.',
                'stock.required' => 'Số lượng kho là bắt buộc.',
                'unit.required' => 'Đơn vị tính là bắt buộc.',
                'images.*.image' => 'File phải là hình ảnh.',
                'images.*.max' => 'Kích thước hình ảnh không được vượt quá 2MB.'
            ]);

            $product = Product::findOrFail($id);

            // Cập nhật thông tin sản phẩm
            $product->update([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'category_id' => $request->category_id,
                'description' => $request->description,
                'price' => $request->price,
                'sale_price' => $request->sale_price,
                'stock' => $request->stock,
                'status' => $request->status,
                'unit' => $request->unit,
            ]);

            // Xử lý upload hình ảnh mới
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $fileName = now()->timestamp . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $imagePath = $image->storeAs('Product', $fileName, 'public');

                    ProductImage::create([
                        'product_id' => $product->id,
                        'image' => $imagePath
                    ]);
                }
            }

            return redirect()->route('admin.products.index')->with('success', 'Cập nhật sản phẩm thành công!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Đã xảy ra lỗi: ' . $th->getMessage())->withInput();
        }
    }

    public function destroy(Request $request)
    {
        try {
            $product = Product::with('images')->findOrFail($request->product_id);

            // Xóa tất cả hình ảnh
            foreach ($product->images as $image) {
                if ($image->image) {
                    Storage::disk('public')->delete($image->image);
                }
                $image->delete();
            }

            $product->delete();

            return redirect()->route('admin.products.index')->with('success', 'Xóa sản phẩm thành công!');
        } catch (\Throwable $th) {
            return redirect()->route('admin.products.index')->with('error', 'Đã xảy ra lỗi khi xóa: ' . $th->getMessage());
        }
    }

    public function deleteImage($id)
    {
        try {
            $image = ProductImage::findOrFail($id);

            if ($image->image) {
                Storage::disk('public')->delete($image->image);
            }

            $image->delete();

            return response()->json([
                'success' => true,
                'message' => 'Xóa hình ảnh thành công!'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi khi xóa hình ảnh'
            ], 500);
        }
    }
}
