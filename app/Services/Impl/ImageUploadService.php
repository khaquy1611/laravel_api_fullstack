<?php

namespace App\Services\Impl;
use Intervention\Image\ImageManager;
use App\Pipelines\ImagePipelineManager;

class ImageUploadService {
    protected $payload;
    private $auth;
    private $config;
    protected $uploadedFiles = [];
    protected $errors = [];
    protected $pipelineManager;
    
    public function __construct(
        ImagePipelineManager $pipelineManager
    )
    {
        /** 
         * @var \Tymon\JWTAuth\JWTGuard
         */
        $this->auth = auth('api');
        $this->config = config('upload.image');
        $this->pipelineManager = $pipelineManager;
    }   

    public function upload($files, $folder = null, $pipelineKey = 'default', array $overrideOptions = [])  {
        $this->uploadedFiles = [];
        $this->errors = [];
        if (!is_array($files)) {
            return $this->handleSingleUpload($files, $folder, $pipelineKey, $overrideOptions);
        } 
        return $this->handleMultipleUpload($files, $folder, $pipelineKey, $overrideOptions);       
    }

    protected function handleSingleUpload($files, $folder, $pipelineKey, $overrideOptions) {
        try {
            $result = $this->handleFileUpload($files, $folder, $pipelineKey, $overrideOptions);
            $this->uploadedFiles = $result;
            return $this->generateResponse();
        }catch(\Exception $e) {
            $this->errors[] = [
                'file' => $files->getClientOriginalName(),
                'error' => $e->getMessage()
            ];
            return $this->generateResponse();
        }
    }

    protected function handleMultipleUpload($files, $folder, $pipelineKey, $overrideOptions) {
        
    }

    protected function handleFileUpload($file, $folder, $pipelineKey, $overrideOptions) {
        $this->validateFile($file);
        $overrideOptions['storage'] = array_merge(
            $overrideOptions['storage'] ?? [],
            ['path' => $this->buildPath($folder)]
        );
        $image = ImageManager::gd()->read($file);
        $image->originalFile = $file;
        
        $processImage = $this->pipelineManager->process($image,$pipelineKey,$overrideOptions);
        return [
            'original_name' => $processImage->originalName,
            'name' => $processImage->fileName,
            'path' => $processImage->path,
        ];
    }
    
    protected function validateFile($file) {
        if (!$file->isValid()) {
            throw new \Exception('File upload không hợp lệ');
        } 
        if ($file->getSize() > ($this->config['max_size'] * 1024)) {
            throw new \Exception("Kích thước file vượt quá {$this->config['max_size']} giới hạn");
        }
        if (!in_array($file->getMimeType() , $this->config['allowed_mime_types'])) {
            throw new \Exception(
            'Kiểu file upload không đúng trong danh sách (
            jpeg,
            png,
            gif,
            jpg');
        }
    }
    
    protected function buildPath($folder = null) {
        return trim($this->config['base_path'] . '/' . $folder, '/');
    }
    
    protected function generateResponse() {
        $response = [
            'success' => count($this->errors) === 0,
            'files' => $this->uploadedFiles,
            'total_uploaded' => count($this->uploadedFiles)
        ];
        if (!empty($this->errors)) {
            $response['errors'] = $this->errors;
        }
        return $response;
    }
}