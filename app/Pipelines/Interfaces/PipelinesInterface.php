<?php 
namespace App\Pipelines\Interfaces;

interface PipelinesInterface {
    public function handle($image, \Closure $next);
}