<?php

namespace Tinyga\ImageOptimizerClient;

use Tinyga\ImageOptimizerClient\Image\ImageWithFileName;

class ImageOptimizerResult
{
    /**
     * @var string
     */
    protected $task_id;

    /**
     * @var ImageWithFileName
     */
    protected $exported_image;

    /**
     * @param string $task_id
     * @param ImageWithFileName $exported_image
     */
    public function __construct($task_id = null, $exported_image = null)
    {
        $task_id !== null && $this->setTaskId($task_id);
        $exported_image !== null && $this->setExportedImage($exported_image);
    }

    /**
     * @return string
     */
    public function getTaskId()
    {
        return $this->task_id;
    }

    /**
     * @param string $task_id
     */
    public function setTaskId($task_id)
    {
        $this->task_id = $task_id;
    }

    /**
     * @return ImageWithFileName
     */
    public function getExportedImage()
    {
        return $this->exported_image;
    }

    /**
     * @param ImageWithFileName $exported_image
     */
    public function setExportedImage($exported_image)
    {
        $this->exported_image = $exported_image;
    }
}
