<?php
namespace Tinyga\ImageOptimizer;

/**
 * Response expected by API to be returned when received task result
 */
class ImageOptimizerPostResultResponse implements \JsonSerializable
{
    const STATUS_OK = 'OK';
    const STATUS_ERROR = 'ERROR';

    /**
     * @var string
     */
    protected $status = self::STATUS_OK;

    /**
     * @var string|null
     */
    protected $task_id;

    /**
     * @var string|null
     */
    protected $error_code;

    /**
     * @var string|null
     */
    protected $error_message;

    /**
     * @param string|null $task_id
     */
    function __construct($task_id)
    {
        $this->task_id = $task_id;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return string|null
     */
    public function getTaskId()
    {
        return $this->task_id;
    }

    /**
     * @return string|null
     */
    public function getErrorCode()
    {
        return $this->error_code;
    }

    /**
     * @return string|null
     */
    public function getErrorMessage()
    {
        return $this->error_message;
    }

    /**
     * @param string $error_code
     * @param string $error_message
     */
    function setError($error_code, $error_message = "")
    {
        $this->status = self::STATUS_ERROR;
        $this->error_code = (string)$error_code;
        $this->error_message = (string)$error_message;
    }

    public function jsonSerialize()
    {
        return array_filter(get_object_vars($this), function ($value){
            return $value !== null;
        });
    }
}
