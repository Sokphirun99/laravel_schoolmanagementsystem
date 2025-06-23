<?php

namespace App\Http\Controllers\Voyager;

use TCG\Voyager\Http\Controllers\VoyagerBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Events\BreadDataUpdated;

class NoticeController extends VoyagerBaseController
{
    // Override the update method for Notices
    public function update(Request $request, $id)
    {
        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Validate permission
        $this->authorize('edit', app($dataType->model_name));

        // Get fields with images and file types
        $rows = $dataType->editRows;
        foreach ($rows as $key => $row) {
            if (in_array($row->type, ['image', 'file'])) {
                $file = $request->file($row->field);
                if ($file) {
                    $options = json_decode($row->details);
                    if (isset($options->resize) && isset($options->resize->width) && isset($options->resize->height)) {
                        $resize_width = $options->resize->width;
                        $resize_height = $options->resize->height;
                    } else {
                        $resize_width = 1800;
                        $resize_height = null;
                    }
                    
                    // Delete old image
                    $data = DB::table($dataType->name)->where('id', $id)->first();
                    if (!empty($data->{$row->field})) {
                        if (Storage::disk(config('voyager.storage.disk'))->exists($data->{$row->field})) {
                            Storage::disk(config('voyager.storage.disk'))->delete($data->{$row->field});
                        }
                    }
                    
                    // Upload new image
                    $filename = Str::random(20);
                    $path = $slug.'/'.date('F').date('Y').'/';
                    $file_name = $filename.'.'.$file->getClientOriginalExtension();
                    
                    $image = Image::make($file)->resize($resize_width, $resize_height, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })->encode($file->getClientOriginalExtension(), 75);
                    
                    Storage::disk(config('voyager.storage.disk'))->put($path.$file_name, (string) $image, 'public');
                    
                    $request->merge([
                        $row->field => $path.$file_name,
                    ]);
                }
            }
        }

        // Set custom fields values
        if ($request->has('target_audience')) {
            // Log or modify the target audience if needed
            \Log::info('Notice audience updated: ' . $request->target_audience);
        }

        // Call the parent update method (don't want to duplicate update logic)
        $response = parent::update($request, $id);

        // After successful update, perform any additional tasks
        $model = app($dataType->model_name)->find($id);

        // Example: If this is a notice for students, maybe we want to send notifications
        if ($model->target_audience === 'students' && $model->status) {
            // Send notifications to students (in a real implementation)
            // NotificationService::notify('students', 'New notice available', $model);
        }

        return $response;
    }
}
