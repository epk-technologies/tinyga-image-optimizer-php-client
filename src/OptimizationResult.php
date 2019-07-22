<?php
namespace Tinyga\ImageOptimizer;

use Tinyga\ImageOptimizer\Image\ImageInterface;

class OptimizationResult
{
    /**
     * @var string
     */
    protected $task_id;

    /**
     * @var ImageInterface|null
     */
    protected $optimized_image;

    /**
     * @param string $task_id
     * @param ImageInterface|null $optimized_image
     */
    public function __construct($task_id, ImageInterface $optimized_image = null)
    {
        $this->task_id = $task_id;
        $this->optimized_image = $optimized_image;
    }

    /**
     * @return string
     */
    public function getTaskId()
    {
        return $this->task_id;
    }

    /**
     * @return ImageInterface|null
     */
    public function getOptimizedImage()
    {
        return $this->optimized_image;
    }
}
