<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSuKienRequest;
use App\Http\Requests\Admin\UpdateSuKienRequest;
use App\Models\SuKien;
use App\Models\LoaiSuKien;
use App\Models\LichSuDiem;
use App\Models\User;
use App\Imports\SuKienImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Traits\HasImageUpload;

class SuKienController extends Controller
{
    use HasImageUpload;
    public function __construct() {}
    public function index(Request $request)
    {
        $query = SuKien::with('loaiSuKien');

        if ($request->filled('search')) {
            $query->where('ten_su_kien', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        $suKien = $query->latest()->paginate(10)->withQueryString();
        return view('admin.su_kien.index', compact('suKien'));
    }

    public function create()
    {
        $loai = LoaiSuKien::all();
        $templates = \App\Models\SuKien::where('la_mau_bai_dang', true)->get();
        $mediaKho = \App\Models\ThuVienDaPhuongTien::where('loai_tep', 'hinh_anh')->latest('created_at')->get();
        return view('admin.su_kien.create', compact('loai', 'templates', 'mediaKho'));
    }

    public function store(StoreSuKienRequest $request)
    {
        $data = $request->validated();
        $data['ma_nguoi_tao'] = auth()->id();
        $data['trang_thai']   = 'sap_to_chuc';

        $data['anh_su_kien'] = $this->handleImageUpload($request, null);

        $suKien = SuKien::create($data);

        // Upload gallery images if any
        $this->_uploadGallery($suKien, $request->file('gallery'));

        return redirect()->route('admin.su-kien.index')
            ->with('success', 'Tạo sự kiện thành công!');
    }
    public function show($id)
    {
        $suKien = SuKien::with(['loaiSuKien', 'dangKy.nguoiDung'])->findOrFail($id);
        return view('admin.su_kien.show', compact('suKien'));
    }
    public function edit($id)
    {
        $suKien = SuKien::findOrFail($id);
        $loai   = LoaiSuKien::all();
        $templates = \App\Models\SuKien::where('la_mau_bai_dang', true)->get();
        $mediaKho = \App\Models\ThuVienDaPhuongTien::where('loai_tep', 'hinh_anh')->latest('created_at')->get();
        return view('admin.su_kien.edit', compact('suKien', 'loai', 'templates', 'mediaKho'));
    }

    public function update(UpdateSuKienRequest $request, $id)
    {
        $suKien = SuKien::findOrFail($id);
        $data = $request->validated();
        $data['trang_thai'] = $request->trang_thai;
        $data['mo_ta_chi_tiet'] = $request->mo_ta_chi_tiet;
        $data['dia_diem'] = $request->dia_diem;

        $data['anh_su_kien'] = $this->handleImageUpload($request, $suKien);

        $suKien->update($data);

        // Xử lý upload thêm ảnh vào Gallery
        $this->_uploadGallery($suKien, $request->file('gallery'));

        return redirect()->route('admin.su-kien.index')
            ->with('success', 'Cập nhật sự kiện thành công!');
    }

    private function _uploadGallery(SuKien $suKien, $files)
    {
        if ($files) {
            foreach ($files as $file) {
                if ($file->isValid()) {
                    $path = $file->store('su_kien/gallery', 'public');
                    \App\Models\ThuVienDaPhuongTien::create([
                        'ma_su_kien'       => $suKien->ma_su_kien,
                        'ma_nguoi_tai_len' => auth()->id(),
                        'ten_tep'          => $file->getClientOriginalName(),
                        'duong_dan_tep'    => $path,
                        'loai_tep'         => 'hinh_anh',
                        'kich_thuoc'       => $file->getSize(),
                        'la_cong_khai'     => true
                    ]);
                }
            }
        }
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx,csv|max:5120',
        ]);

        try {
            Excel::import(new SuKienImport(auth()->id()), $request->file('file'));
            return back()->with('success', 'Nhập danh sách sự kiện thành công!');
        } catch (\Throwable $e) {
            return back()->with('error', 'Có lỗi khi nhập file: ' . $e->getMessage());
        }
    }
    public function destroy($id)
    {
        SuKien::findOrFail($id)->delete();
        return back()->with('success', 'Đã xóa sự kiện!');
    }

    public function thongKeDiem()
    {
        $tongDiem = DB::table('lich_su_diem')
            ->join('nguoi_dung', 'lich_su_diem.ma_nguoi_dung', '=', 'nguoi_dung.ma_nguoi_dung')
            ->select('nguoi_dung.ho_ten', 'nguoi_dung.ma_sinh_vien', DB::raw('SUM(lich_su_diem.diem) as tong_diem'))
            ->groupBy('lich_su_diem.ma_nguoi_dung', 'nguoi_dung.ho_ten', 'nguoi_dung.ma_sinh_vien')
            ->orderByDesc('tong_diem')
            ->get();

        return view('admin.thong_ke.diem', compact('tongDiem'));
    }

    public function storeLoaiSuKien(Request $request)
    {
        $request->validate([
            'ten_loai' => 'required|max:200|unique:loai_su_kien,ten_loai',
        ]);

        $loai = LoaiSuKien::create([
            'ten_loai' => $request->ten_loai,
            'mo_ta'    => $request->mo_ta,
        ]);

        return response()->json([
            'success' => true,
            'loai'    => $loai
        ]);
    }

    public function kiemTraTrungLich(Request $request)
    {
        $dia_diem = $request->input('dia_diem');
        $bat_dau  = $request->input('thoi_gian_bat_dau');
        $ket_thuc = $request->input('thoi_gian_ket_thuc');
        $bo_qua_id = $request->input('bo_qua_id'); // Äá»ƒ dÃ¹ng khi edit, bá» qua sá»± kiá»‡n hiá»‡n táº¡i

        if (empty($dia_diem) || empty($bat_dau) || empty($ket_thuc)) {
            return response()->json(['trung' => false]);
        }

        $query = SuKien::where('dia_diem', $dia_diem)
            ->where(function ($q) use ($bat_dau, $ket_thuc) {
                // Äiá»u kiá»‡n trÃ¹ng: Báº¯t Ä‘áº§u sá»± kiá»‡n má»›i náº±m trong khoáº£ng sá»± kiá»‡n cÅ©, hoáº·c káº¿t thÃºc náº±m trong khoáº£ng, hoáº·c bao trÃ¹m khoáº£ng cá»§a sá»± kiá»‡n cÅ©
                $q->whereBetween('thoi_gian_bat_dau', [$bat_dau, $ket_thuc])
                    ->orWhereBetween('thoi_gian_ket_thuc', [$bat_dau, $ket_thuc])
                    ->orWhere(function ($q2) use ($bat_dau, $ket_thuc) {
                        $q2->where('thoi_gian_bat_dau', '<=', $bat_dau)
                            ->where('thoi_gian_ket_thuc', '>=', $ket_thuc);
                    });
            })
            ->whereIn('trang_thai', ['sap_to_chuc', 'dang_dien_ra']);

        if ($bo_qua_id) {
            $query->where('ma_su_kien', '!=', $bo_qua_id);
        }

        $suKienTrung = $query->first();

        if ($suKienTrung) {
            return response()->json([
                'trung' => true,
                'thong_bao' => 'ÄÃ£ cÃ³ sá»± kiá»‡n "' . $suKienTrung->ten_su_kien . '" diá»…n ra tá»« ' . date('H:i d/m', strtotime($suKienTrung->thoi_gian_bat_dau)) . ' Ä‘áº¿n ' . date('H:i d/m', strtotime($suKienTrung->thoi_gian_ket_thuc)) . ' táº¡i Ä‘á»‹a Ä‘iá»ƒm nÃ y!'
            ]);
        }

        return response()->json(['trung' => false]);
    }

    public function xoaHinhAnh($id)
    {
        $media = \App\Models\ThuVienDaPhuongTien::find($id);
        if (!$media) return response()->json(['success' => false, 'message' => 'Không tìm thấy ảnh.'], 404);

        // Logic xóa file đã được xử lý tự động trong model event
        $media->forceDelete();

        return response()->json(['success' => true]);
    }

    /**
     * Kiểm tra xung đột sự kiện (cùng thời gian và địa điểm)
     */
    public function checkCollision(Request $request)
    {
        $request->validate([
            'thoi_gian_bat_dau' => 'required|date',
            'thoi_gian_ket_thuc' => 'required|date|after:thoi_gian_bat_dau',
            'dia_diem' => 'required|string'
        ]);

        $batDau = $request->input('thoi_gian_bat_dau');
        $ketThuc = $request->input('thoi_gian_ket_thuc');
        $diaDiem = $request->input('dia_diem');

        // Tìm các sự kiện trùng lịch và địa điểm
        $conflicts = SuKien::where('dia_diem', 'like', '%' . $diaDiem . '%')
            ->where(function ($query) use ($batDau, $ketThuc) {
                // Kiểm tra xung đột thời gian
                $query->whereBetween('thoi_gian_bat_dau', [$batDau, $ketThuc])
                    ->orWhereBetween('thoi_gian_ket_thuc', [$batDau, $ketThuc])
                    ->orWhere(function ($q) use ($batDau, $ketThuc) {
                        $q->where('thoi_gian_bat_dau', '<=', $batDau)
                            ->where('thoi_gian_ket_thuc', '>=', $ketThuc);
                    });
            })
            ->where('trang_thai', '!=', 'da_huy')
            ->select('ma_su_kien', 'ten_su_kien', 'thoi_gian_bat_dau', 'thoi_gian_ket_thuc', 'dia_diem')
            ->get();

        return response()->json([
            'has_collision' => count($conflicts) > 0,
            'conflicts' => $conflicts,
            'message' => count($conflicts) > 0
                ? 'Có ' . count($conflicts) . ' sự kiện khác cùng thời gian và địa điểm'
                : 'Không có sự kiện nào trùng lịch'
        ]);
    }
}
