<?php 

namespace App\Pipelines;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Pipelines\AbtractPipeline;

class StorageImagePipeline extends AbtractPipeline {
    public function handle($image, \Closure $next) {
        $disk = $this->options['disk'] ?? config('upload.image.disk');
        $path = trim($this->options['path'] . '/' . $image->fileName, '/');
        Storage::disk($disk)->put($path, (string)$image->encode());
        $image->path = $path;
        return $next($image);
    }
}