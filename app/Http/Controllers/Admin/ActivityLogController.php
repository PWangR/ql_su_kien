<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * Controller quản lý Log hoạt động — Admin Panel
 */
class ActivityLogController extends Controller
{
    /**
     * Hiển thị danh sách log (phân trang, tìm kiếm, lọc)
     */
    public function index(Request $request)
    {
        $query = ActivityLog::query()->orderBy('created_at', 'desc');

        // Tìm kiếm theo user name / ID / mô tả
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Lọc theo action
        if ($request->filled('action')) {
            $query->ofAction($request->action);
        }

        // Lọc theo khoảng thời gian
        if ($request->filled('from') || $request->filled('to')) {
            $query->betweenDates($request->from, $request->to);
        }

        $logs = $query->paginate(10)->appends($request->query());

        // Danh sách actions cho dropdown filter
        $actions = [
            'login'  => 'Đăng nhập',
            'logout' => 'Đăng xuất',
            'create' => 'Tạo mới',
            'update' => 'Cập nhật',
            'delete' => 'Xóa',
        ];

        return view('admin.activity_logs.index', compact('logs', 'actions'));
    }
}
