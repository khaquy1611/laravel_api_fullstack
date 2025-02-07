<?php 

namespace App\Pipelines;
use Illuminate\Support\Str;
use App\Pipelines\AbtractPipeline;

class GenerateFileNamePipeline extends AbtractPipeline {
    public function handle($image, \Closure $next) {
        if (!isset($image->fileName)) {
            $originalName = $image->originalFile->getClientOriginalName();
            $extension = $image->originalFile->getClientOriginalExtension();
            $image->fileName = Str::uuid() . '.' . $extension;
            $image->originalName = $originalName;
        }
        return $next($image);
    }
}