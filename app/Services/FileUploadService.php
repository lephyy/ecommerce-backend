<?php
namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

    class FileUploadService
    {
    public function upload($file, $folder = 'storage')
    {
        if (!$file) {
            return null;
        }

        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();

        $path = Storage::disk(config('filesystems.default'))
            ->putFileAs($folder, $file, $filename);

        return $path;
    }

    public function delete($path)
    {
        if ($path && Storage::exists($path)) {
            Storage::delete($path);
        }
    }
}
?>
