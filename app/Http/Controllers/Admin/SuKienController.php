<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSuKienRequest;
use App\Http\Requests\Admin\UpdateSuKienRequest;
use App\Models\SuKien;
use App\Models\LoaiSuKien;
use App\Models\LichSuDiem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Traits\HasImageUpload;

class SuKienController extends Controller
{
    use HasImageUpload;
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
        $data['trang_thai'] = 'sap_to_chuc';

        $data['anh_su_kien'] = $this->handleImageUpload($request, null);

        $suKien = SuKien::create($data);

        // Xử lý upload danh sách ảnh phụ (Gallery)
        $this->_uploadGallery($suKien, $request->file('gallery_files'));

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
        $this->_uploadGallery($suKien, $request->file('gallery_files'));

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
        $bo_qua_id = $request->input('bo_qua_id'); // Để dùng khi edit, bỏ qua sự kiện hiện tại

        if (empty($dia_diem) || empty($bat_dau) || empty($ket_thuc)) {
            return response()->json(['trung' => false]);
        }

        $query = SuKien::where('dia_diem', $dia_diem)
            ->where(function ($q) use ($bat_dau, $ket_thuc) {
                // Điều kiện trùng: Bắt đầu sự kiện mới nằm trong khoảng sự kiện cũ, hoặc kết thúc nằm trong khoảng, hoặc bao trùm khoảng của sự kiện cũ
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
                'thong_bao' => 'Đã có sự kiện "' . $suKienTrung->ten_su_kien . '" diễn ra từ ' . date('H:i d/m', strtotime($suKienTrung->thoi_gian_bat_dau)) . ' đến ' . date('H:i d/m', strtotime($suKienTrung->thoi_gian_ket_thuc)) . ' tại địa điểm này!'
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
}
