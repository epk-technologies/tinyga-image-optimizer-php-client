<?php
namespace Tinyga\ImageOptimizer;

use Tinyga\ImageOptimizer\Image\ImageInterface;
use Tinyga\ImageOptimizer\OptimizationRequest\Operations;
use Tinyga\ImageOptimizer\OptimizationRequest\Operations\Operation;
use Tinyga\ImageOptimizer\OptimizationRequest\OutputParameters;

class OptimizationRequest
{
    /**
     * Image data source
     *
     * @var ImageInterface
     */
    protected $image;

    /**
     * When URL is defined, optimization will become asynchronous.
     * API will return only task ID to identify task later.
     *
     * @see ImageOptimizerPostResultHandler to see how to handle result later
     *
     * @var string|null
     */
    protected $post_result_to_url;

    /**
     * Do not process anything, just for API integration testing.
     * No credit will be used in test mode.
     *
     * @var bool
     */
    protected $test = false;

    /**
     * Definition of required output (format, quality, metadata handling etd.)
     *
     * @var OutputParameters
     */
    protected $output_parameters;

    /**
     * Additional processing operations like resizing, rotation, gamma adjustment etc.
     * @see Operations
     *
     * @var Operation[]
     */
    protected $operations = [];

    /**
     * @param ImageInterface $image
     * @param OutputParameters|null $output_parameters
     * @param Operation[] $operations
     */
    function __construct(ImageInterface $image, OutputParameters $output_parameters = null, array $operations = [])
    {
        $this->image = $image;
        if(!$output_parameters){
            $output_parameters = new OutputParameters();
        }
        $this->setOutputParameters($output_parameters);
        $this->setOperations($operations);
    }

    /**
     * @return OutputParameters
     */
    public function getOutputParameters()
    {
        return $this->output_parameters;
    }

    /**
     * @param OutputParameters $output_parameters
     */
    public function setOutputParameters(OutputParameters $output_parameters)
    {
        $this->output_parameters = $output_parameters;
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
        if(
            $post_result_to_url !== null &&
            !filter_var($post_result_to_url, FILTER_VALIDATE_URL)
        ){
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
        $this->operations[$operation->getOperationType()] = $operation;
    }

    /**
     * @param string $operation_type
     * @return Operation|null
     */
    public function getOperation($operation_type)
    {
        return isset($this->operations[$operation_type])
            ? $this->operations[$operation_type]
            : null;
    }

    /**
     * @param string $operation_type
     * @return bool
     */
    public function hasOperation($operation_type)
    {
        return isset($this->operations[$operation_type]);
    }

    /**
     * @param string $operation_type
     * @return bool
     */
    public function removeOperation($operation_type)
    {
        if(isset($this->operations[$operation_type])){
            unset($this->operations[$operation_type]);
            return true;
        }
        return false;
    }

    public function validate()
    {
        $errors = [];
        foreach($this->operations as $op_type => $operation){
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
