<?php
namespace Tinyga\ImageOptimizer;

use Exception;

class OptimizationException extends Exception
{
    const ERR_CLIENT_ERROR = 'client-error';
    const ERR_NOT_ENOUGH_CREDIT = 'not-enough-credit';
    const ERR_INVALID_REQUEST = 'invalid-request';
    const ERR_SERVER_ERROR = 'server-error';
    const ERR_PROCESSOR_ERROR = 'processor-error';
    const ERR_DELIVERY_ERROR = 'delivery-error';
    const ERR_INVALID_TASK = 'invalid-task';
    const ERR_PROTOCOL_ERROR = 'protocol-error';
    const ERR_LOCAL_SERVER_ERROR = 'local-server-error';

    /**
     * @var string
     */
    protected $error_code = self::ERR_CLIENT_ERROR;

    /**
     * @var string|null
     */
    protected $task_id;

    public function __construct($error_code, $error_message = "", $task_id = null, $code = 0, $previous = null)
    {
        $this->error_code = $error_code;
        $this->task_id = $task_id;
        parent::__construct($error_message, $code, $previous);
    }

    /**
     * @return string|null
     */
    public function getTaskId()
    {
        return $this->task_id;
    }

    /**
     * @return string
     */
    public function getErrorCode()
    {
        return $this->error_code;
    }
}
