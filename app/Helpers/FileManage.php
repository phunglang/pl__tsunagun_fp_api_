<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class FileManage
{
    private $_driver;
    private $_nameFile;
    private $_dataFile;
    private $_option;
    private $_model;
    private $_mainPath;

    /**
     * FileManage constructor.
     * @param string $nameFile
     * @param null $dataFile
     * @param string $option
     * @param string|null $model
     * @param string $driver
     * @param string $mainPath
     */
    public function __construct(string $nameFile, $dataFile = null, string $model = 'App\Models\File', string $option = 'public', $driver = 's3', string $mainPath = 'uploads/tsunagun_fp')
    {
        $this->_driver = $driver;
        $this->_nameFile = $nameFile;
        $this->_dataFile = $dataFile;
        $this->_option = $option;
        $this->_model = $model;
        $this->_mainPath = $mainPath;
    }

    /**
     * @param array $data
     * @param string $rowUpload
     * @param null $getCreateData
     * @param null $addName
     * @param bool $isVideo
     * @return array
     * @noinspection PhpUndefinedMethodInspection
     */
    public function uploadFileToS3(array $data): array
    {
        $path = Storage::disk($this->_driver)->put($this->_mainPath.'/'.date('m-Y'), $this->_dataFile, $this->_option);
        $data['path'] = $path;
        $file = $this->_model::create($data);
        return [
            $file->_id,
            $data
        ];
    }

    // /**
    //  * @return string
    //  */
    // public function show(): string
    // {
    //     return Storage::disk($this->_driver)->url($this->_nameFile);
    // }
}
