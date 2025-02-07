<?php 

namespace App\Pipelines;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Pipelines\AbtractPipeline;

class ImagePipelineManager {
    protected $defaultPipeline = [
        'generate_filename' => \App\Pipelines\GenerateFileNamePipeline::class,
        'storage' =>  \App\Pipelines\StorageImagePipeline::class,
    ];
    
    public function process($image, string $configKey = 'default', array $overrideOptions = []) {
        $pipelineConfig = config("upload.image.pipelines.{$configKey}");
        $pipes = array_reverse(array_keys($pipelineConfig));
        $pipeline = array_reduce(
            $pipes,
            function($stack, $pipe) use ($pipelineConfig, $overrideOptions) {
                if (!($pinelineConfig[$pipe]['enabled'] ?? true)) {
                    return $stack;
                }
                $pipelineClass = $this->defaultPipeline[$pipe] ?? null;
                if (!$pipelineClass) return $stack;
                return function ($passable) use ($stack, $pipelineClass, $pipelineConfig, $pipe, $overrideOptions) {
                    $options = array_merge(
                        $pipelineConfig[$pipe] ?? [],
                        $overrideOptions[$pipe] ?? []
                    );
                $pipeline = new $pipelineClass($options);
                return $pipeline->handle($passable, function($result) use($stack) {
                        return $stack($result);             
                });
                };
               
            },
            function ($passable) {
                return $passable;
            }
        );
       
        return $pipeline($image);
    }
}