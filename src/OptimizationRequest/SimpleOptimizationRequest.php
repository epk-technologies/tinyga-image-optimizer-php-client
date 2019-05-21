<?php

namespace Tinyga\ImageOptimizerClient\OptimizationRequest;


use Tinyga\ImageOptimizerClient\ImageOptimizerClientException;

class SimpleOptimizationRequest extends AbstractOptimizationRequest
{
    /**
     * @var string
     */
    protected $image_file_name;

    /**
     * @var string
     */
    protected $image_content;


    /**
     * @param string|null $image_content
     * @param string|null $file_name
     * @param int|null $quality
     * @param array|null $keep_metadata
     * @param string|null $post_result_to_url
     * @param string|null $test_mode
     *
     * @throws ImageOptimizerClientException
     */
    public function __construct(
        $image_content = null,
        $file_name = null,
        $quality = null,
        array $keep_metadata = null,
        $post_result_to_url = null,
        $test_mode = null
    ) {
        parent::__construct($quality, $keep_metadata, $post_result_to_url, $test_mode);
        $image_content !== null && $this->setImageContent($image_content);
        $file_name !== null && $this->setImageFileName($file_name);
    }


    /**
     * @return string
     */
    public function getImageFileName()
    {
        return $this->image_file_name;
    }

    /**
     * @param string $image_file_name
     */
    public function setImageFileName($image_file_name)
    {
        $this->image_file_name = (string)$image_file_name;
    }

    /**
     * @return string
     */
    public function getImageContent()
    {
        return $this->image_content;
    }

    /**
     * @param string $image_content
     */
    public function setImageContent($image_content)
    {
        $this->image_content = (string)$image_content;
    }
}