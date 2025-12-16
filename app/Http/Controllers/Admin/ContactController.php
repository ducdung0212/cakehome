<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        return view('admin.pages.contacts.index');
    }

    public function show($id)
    {
        return view('admin.pages.contacts.show');
    }

    public function updateStatus(Request $request, $id)
    {
        // Logic cập nhật trạng thái liên hệ
    }

    public function destroy($id)
    {
        // Logic xóa liên hệ
    }
}
