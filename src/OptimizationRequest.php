<?php
namespace Tinyga\ImageOptimizer;

use Tinyga\ImageOptimizer\Image\ImageInterface;
use Tinyga\ImageOptimizer\OptimizationRequest\Operations\Operation;

class OptimizationRequest
{
    const LOSSLESS_QUALITY = 100;
    const DEFAULT_LOSSY_QUALITY = 95;

    const MIN_QUALITY = 1;
    const MAX_QUALITY = self::LOSSLESS_QUALITY;

    const META_ALL = 'all';

    const META_PROFILE = 'profile';
    const META_DATE = 'date';
    const META_COPYRIGHT = 'copyright';
    const META_GEOTAG = 'geotag';
    const META_ORIENTATION = 'orientation';

    protected static $allowed_keep_metadata = [
        self::META_ALL,
        self::META_PROFILE,
        self::META_DATE,
        self::META_COPYRIGHT,
        self::META_GEOTAG,
        self::META_ORIENTATION,
    ];

    protected static $allowed_output_types = [
        ImageInterface::TYPE_JPEG,
        ImageInterface::TYPE_PNG,
        ImageInterface::TYPE_GIF,
    ];

    /**
     * @var ImageInterface
     */
    protected $image;

    /**
     * @var string|null
     */
    protected $post_result_to_url;

    /**
     * @var bool
     */
    protected $test = false;

    /**
     * @var int
     */
    protected $quality = self::LOSSLESS_QUALITY;

    /**
     * @var array
     */
    protected $keep_metadata = [self::META_ALL];

    /**
     * @var Operation[]
     */
    protected $operations = [];

    /**
     * @var string|null
     */
    protected $output_type;

    function __construct(ImageInterface $image)
    {
        $this->image = $image;
    }

    /**
     * @return ImageInterface
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param ImageInterface $image
     */
    public function setImage(ImageInterface $image)
    {
        $this->image = $image;
    }

    /**
     * @return string|null
     */
    public function getPostResultToUrl()
    {
        return $this->post_result_to_url;
    }

    /**
     * @param string|null $post_result_to_url
     */
    public function setPostResultToUrl($post_result_to_url)
    {
        if($post_result_to_url !== null && !filter_var($post_result_to_url)){
            throw new \InvalidArgumentException("Invalid POST result to URL format");
        }
        $this->post_result_to_url = $post_result_to_url;
    }

    /**
     * @return bool
     */
    public function isTest()
    {
        return $this->test;
    }

    /**
     * @param bool $test
     */
    public function setTest($test)
    {
        $this->test = (bool)$test;
    }

    /**
     * @return int
     */
    public function getQuality()
    {
        return $this->quality;
    }

    /**
     * @param int $quality
     */
    public function setQuality($quality)
    {
        $quality = (int)$quality;
        if($quality < self::MIN_QUALITY || $quality > self::MAX_QUALITY){
            throw new \InvalidArgumentException(sprintf(
                "Invalid quality - must be between %d and %d",
                self::MIN_QUALITY,
                self::MAX_QUALITY
            ));
        }
        $this->quality = $quality;
    }

    /**
     * @return array
     */
    public function getKeepMetadata()
    {
        return $this->keep_metadata;
    }

    /**
     * @param array $keep_metadata
     */
    public function setKeepMetadata(array $keep_metadata)
    {
        foreach($keep_metadata as $meta){
            if(!in_array($meta, self::$allowed_keep_metadata)){
                throw new \InvalidArgumentException("Invalid keep metadata value '{$meta}'");
            }
        }
        $this->keep_metadata = $keep_metadata;
    }

    /**
     * @return string|null
     */
    public function getOutputType()
    {
        return $this->output_type;
    }

    /**
     * @param string|null $output_type
     */
    public function setOutputType($output_type)
    {
        if($output_type !== null && !in_array($output_type, self::$allowed_output_types)){
            throw new \InvalidArgumentException("Invalid output type '{$output_type}'");
        }
        $this->output_type = $output_type;
    }

    /**
     * @return Operation[]
     */
    public function getOperations()
    {
        return $this->operations;
    }

    /**
     * @param Operation[] $operations
     */
    public function setOperations(array $operations)
    {
        $this->operations = [];
        $this->addOperations($operations);
    }

    /**
     * @param Operation[] $operations
     */
    public function addOperations(array $operations)
    {
        foreach($operations as $operation){
            $this->addOperation($operation);
        }
    }

    /**
     * @param Operation $operation
     */
    public function addOperation(Operation $operation)
    {
        $this->operations[$operation->getOperationName()] = $operation;
    }

    /**
     * @param string $operation
     * @return Operation|null
     */
    public function getOperation($operation)
    {
        return isset($this->operations[$operation])
            ? $this->operations[$operation]
            : null;
    }

    /**
     * @param string $operation
     * @return bool
     */
    public function hasOperation($operation)
    {
        return isset($this->operations[$operation]);
    }

    /**
     * @param string $operation
     * @return bool
     */
    public function removeOperation($operation)
    {
        if(isset($this->operations[$operation])){
            unset($this->operations[$operation]);
            return true;
        }
        return false;
    }

    public function validate()
    {
        $errors = [];
        foreach($this->operations as $op_name => $operation){
            try {
                $operation->validate();
            } catch(\Exception $e){
                $errors[] = $e->getMessage();
            }
        }

        if($errors){
            throw new \RuntimeException(implode("\n", $errors));
        }
    }

}