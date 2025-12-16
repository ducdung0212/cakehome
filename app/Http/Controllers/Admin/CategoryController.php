<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Str;
use Throwable;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('admin.pages.categories.index', compact('categories'));
    }



    public function add(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
            'images' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ], [
            'name.unique' => 'Tên danh mục này đã tồn tại. Vui lòng chọn tên khác.',
            'images.image' => 'File tải lên phải là hình ảnh.',
            'images.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif, webp.',
            'images.max' => 'Kích thước hình ảnh không được vượt quá 2MB.'
        ]);
        $imagePath = null;
        if ($request->hasFile("images")) {
            $imagePath = $request->file("images");
            $fileName = now()->timestamp . '_' . uniqid() . '.' . $imagePath->getClientOriginalExtension();
            $imagePath = $imagePath->storeAs('Categories', $fileName, 'public');
        }
        Category::create([
            'name' => $request['name'],
            'slug' => Str::slug($request['name']),
            'description' => $request['description'],
            'images' => $imagePath,
        ]);
        return redirect()->route('admin.categories.index')->with('success', 'Danh mục đã được thêm thành công');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.pages.categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:categories,name,' . $id,
                'description' => 'nullable|string',
                'images' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
            ], [
                'name.unique' => 'Tên danh mục này đã tồn tại. Vui lòng chọn tên khác.',
                'images.image' => 'File tải lên phải là hình ảnh.',
                'images.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif, webp.',
                'images.max' => 'Kích thước hình ảnh không được vượt quá 2MB.'
            ]);

            $category = Category::findOrFail($id);

            $imagePath = $category->images;

            // Xử lý upload hình ảnh mới
            if ($request->hasFile('images')) {
                $image = $request->file('images');
                if ($image->isValid()) {
                    // Xóa ảnh cũ nếu có
                    if ($category->images) {
                        Storage::disk('public')->delete($category->images);
                    }

                    $fileName = now()->timestamp . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $imagePath = $image->storeAs('Categories', $fileName, 'public');
                }
            }

            $category->update([
                'name' => $request->name,
                'slug' => \Str::slug($request->name),
                'description' => $request->description,
                'images' => $imagePath,
            ]);

            return redirect()->route('admin.categories.index')->with('success', 'Cập nhật danh mục thành công!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Đã xảy ra lỗi khi cập nhật: ' . $th->getMessage())->withInput();
        }
    }

    public function delete(Request $request)
    {
        try {
            $category = Category::findOrFail($request->category_id);

            if ($category->images) {
                Storage::disk('public')->delete($category->images);
            }

            $category->delete();

            return redirect()->route('admin.categories.index')
                ->with('success', 'Xóa danh mục thành công!');
        } catch (\Throwable $th) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Đã xảy ra lỗi khi xóa: ' . $th->getMessage());
        }
    }
}
