<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait HasImageUpload
{
    /**
     * Xử lý upload ảnh chính (avatar/banner) cho model.
     * Tự động xóa ảnh cũ nếu có.
     */
    protected function handleImageUpload($request, $model, $column = 'anh_su_kien', $folder = 'su_kien')
    {
        if ($request->hasFile($column)) {
            // Xóa ảnh cũ nếu không phải link media bên ngoài
            if ($model && $model->$column && !str_starts_with($model->$column, 'media/')) {
                Storage::disk('public')->delete($model->$column);
            }
            return $request->file($column)->store($folder, 'public');
        } 
        
        if ($request->filled('media_duong_dan')) {
            $newPath = $request->media_duong_dan;
            if ($model && $model->$column && $model->$column != $newPath && !str_starts_with($model->$column, 'media/')) {
                Storage::disk('public')->delete($model->$column);
            }
            return $newPath;
        }

        return $model ? $model->$column : null;
    }
}
