<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
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
        $filtersNormalized = false;
        $from = $request->input('from');
        $to = $request->input('to');

        if ($from && $to && $from > $to) {
            [$from, $to] = [$to, $from];
            $filtersNormalized = true;
        }

        $query = ActivityLog::query()
            ->adminOnly()
            ->orderBy('created_at', 'desc');

        // Tìm kiếm theo user name / ID / mô tả
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Lọc theo action
        if ($request->filled('action')) {
            $query->ofAction($request->action);
        }

        // Lọc theo khoảng thời gian
        if ($from || $to) {
            $query->betweenDates($from, $to);
        }

        $logs = $query->paginate(10)->appends([
            'search' => $request->search,
            'action' => $request->action,
            'from' => $from,
            'to' => $to,
        ]);

        // Danh sách actions cho dropdown filter
        $actions = [
            'login'  => 'Đăng nhập',
            'logout' => 'Đăng xuất',
            'create' => 'Tạo mới',
            'update' => 'Cập nhật',
            'delete' => 'Xóa',
        ];

        return view('admin.activity_logs.index', compact('logs', 'actions', 'from', 'to', 'filtersNormalized'));
    }
}
