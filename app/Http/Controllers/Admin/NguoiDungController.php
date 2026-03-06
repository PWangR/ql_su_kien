<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VaiTro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class NguoiDungController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('vaiTro')->whereNull('deleted_at');

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('ho_ten', 'like', '%'.$request->search.'%')
                  ->orWhere('email', 'like', '%'.$request->search.'%')
                  ->orWhere('ma_sinh_vien', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->vai_tro) {
            $query->where('ma_vai_tro', $request->vai_tro);
        }

        $nguoiDung = $query->latest()->paginate(15)->withQueryString();
        $vaiTro    = VaiTro::all();

        return view('admin.nguoi_dung.index', compact('nguoiDung', 'vaiTro'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ho_ten'      => 'required|max:100',
            'email'       => 'required|email|unique:nguoi_dung,email',
            'ma_sinh_vien'=> 'required|unique:nguoi_dung,ma_sinh_vien',
            'ma_vai_tro'  => 'required',
            'mat_khau'    => 'required|min:8',
        ]);

        User::create([
            'ho_ten'       => $request->ho_ten,
            'email'        => $request->email,
            'ma_sinh_vien' => $request->ma_sinh_vien,
            'ma_vai_tro'   => $request->ma_vai_tro,
            'mat_khau'     => Hash::make($request->mat_khau),
            'so_dien_thoai'=> $request->so_dien_thoai,
            'trang_thai'   => 'hoat_dong',
        ]);

        return back()->with('success', 'Đã thêm người dùng!');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'ho_ten'    => 'required|max:100',
            'ma_vai_tro'=> 'required',
        ]);

        $data = $request->only(['ho_ten', 'ma_vai_tro', 'so_dien_thoai', 'trang_thai']);
        if ($request->filled('mat_khau')) {
            $data['mat_khau'] = Hash::make($request->mat_khau);
        }

        $user->update($data);
        return back()->with('success', 'Cập nhật thành công!');
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return back()->with('success', 'Đã xóa người dùng!');
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        $user->trang_thai = $user->trang_thai === 'hoat_dong' ? 'bi_khoa' : 'hoat_dong';
        $user->save();
        return back()->with('success', 'Đã cập nhật trạng thái!');
    }
}
