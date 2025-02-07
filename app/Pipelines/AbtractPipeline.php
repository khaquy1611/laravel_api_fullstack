<?php 
namespace App\Pipelines;
use App\Pipelines\Interfaces\PipelinesInterface;

abstract class AbtractPipeline implements PipelinesInterface {
    protected $options;
    public function __construct(
        array $options = [] 
    ) {
        $this->options = $options;
    }
}