<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\NguoiDungImport;

class NguoiDungController extends Controller
{
    public function index(Request $request)
    {
        $query = User::whereNull('deleted_at');

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('ho_ten', 'like', '%'.$request->search.'%')
                  ->orWhere('email', 'like', '%'.$request->search.'%')
                  ->orWhere('ma_sinh_vien', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->vai_tro) {
            $query->where('vai_tro', $request->vai_tro);
        }

        $nguoiDung = $query->latest()->paginate(15)->withQueryString();

        return view('admin.nguoi_dung.index', compact('nguoiDung'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ho_ten'      => 'required|max:100',
            'email'       => 'required|email|unique:nguoi_dung,email',
            'ma_sinh_vien'=> 'required|digits:8|unique:nguoi_dung,ma_sinh_vien',
            'vai_tro'  => 'required',
            'mat_khau'    => 'required|min:8',
        ]);

        User::create([
            'ho_ten'       => $request->ho_ten,
            'email'        => $request->email,
            'ma_sinh_vien' => $request->ma_sinh_vien,
            'vai_tro'      => $request->vai_tro,
            'mat_khau'     => Hash::make($request->mat_khau),
            'so_dien_thoai'=> $request->so_dien_thoai,
            'trang_thai'   => 'hoat_dong',
        ]);

        return back()->with('success', 'ÄÃ£ thÃªm ngÆ°á»i dÃ¹ng!');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'ho_ten'    => 'required|max:100',
            'vai_tro'   => 'required',
        ]);

        $data = $request->only(['ho_ten', 'vai_tro', 'so_dien_thoai', 'trang_thai']);
        if ($request->filled('mat_khau')) {
            $data['mat_khau'] = Hash::make($request->mat_khau);
        }

        $user->update($data);
        return back()->with('success', 'Cáº­p nháº­t thÃ nh cÃ´ng!');
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx,csv|max:4096',
        ]);

        Excel::import(new NguoiDungImport(), $request->file('file'));

        return back()->with('success', 'Nháº­p danh sÃ¡ch ngÆ°á»i dÃ¹ng thÃ nh cÃ´ng!');
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return back()->with('success', 'ÄÃ£ xÃ³a ngÆ°á»i dÃ¹ng!');
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        $user->trang_thai = $user->trang_thai === 'hoat_dong' ? 'bi_khoa' : 'hoat_dong';
        $user->save();
        return back()->with('success', 'ÄÃ£ cáº­p nháº­t tráº¡ng thÃ¡i!');
    }
}
