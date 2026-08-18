<?php
function injectTracking(&$data, $isUpdate = false) {
    if (\Illuminate\Support\Facades\Auth::guard('web')->check()) {
        $data[$isUpdate ? 'updated_by' : 'created_by'] = \Illuminate\Support\Facades\Auth::guard('web')->id();
        $data[$isUpdate ? 'updater_type' : 'creator_type'] = 'coordinator';
    } else if (\Illuminate\Support\Facades\Auth::guard('admin')->check()) {
        $data[$isUpdate ? 'updated_by' : 'created_by'] = \Illuminate\Support\Facades\Auth::guard('admin')->id();
        $data[$isUpdate ? 'updater_type' : 'creator_type'] = 'admin';
    }
}
