<?php
namespace Tinyga\ImageOptimizer;

use Tinyga\ImageOptimizer\Image\ImageContent;

class ImageOptimizerPostResultHandler
{
    const PARAM_RESULT = 'result';
    const PARAM_RESULT_MD5 = 'result_md5';

    const PARAM_TASK_ID = 'task_id';
    const PARAM_ERROR_CODE = 'error_code';
    const PARAM_ERROR_MESSAGE = 'error_message';

    /**
     * @param string|null $task_id
     * @return bool
     */
    public function isTaskResultInRequest($task_id = null)
    {
        if(
            empty($_POST[self::PARAM_TASK_ID]) ||
            !is_string($_POST[self::PARAM_TASK_ID])
        ){
            return false;
        }

        if($task_id === null){
            return true;
        }

        return $_POST[self::PARAM_TASK_ID] === $task_id;
    }

    /**
     * @param null|string $task_id (if specific task expected)
     * @return OptimizationResult
     * @throws OptimizationException
     */
    public function fetchResultFromRequest($task_id = null)
    {
        if($this->isTaskResultInRequest($task_id)){
            throw new OptimizationException(
                OptimizationException::ERR_PROTOCOL_ERROR,
                "Task result not found in POST data"
            );
        }

        $task_id = $_POST[self::PARAM_TASK_ID];
        if(!empty($_POST[self::PARAM_ERROR_CODE])){
            throw new OptimizationException(
                $_POST[self::PARAM_ERROR_CODE],
                isset($_POST[self::PARAM_ERROR_MESSAGE]) ? $_POST[self::PARAM_ERROR_MESSAGE] : '',
                $task_id
            );
        }

        if(!isset($_FILES[self::PARAM_RESULT])){
            throw new OptimizationException(
                OptimizationException::ERR_PROTOCOL_ERROR,
                "Missing optimized image in result",
                $task_id
            );
        }

        if(!isset($_POST[self::PARAM_RESULT_MD5])){
            throw new OptimizationException(
                OptimizationException::ERR_PROTOCOL_ERROR,
                "Missing optimized image checksum result",
                $task_id
            );
        }

        $file = $_FILES[self::PARAM_RESULT];


        if($file['error'] !== UPLOAD_ERR_OK){
            file_exists($file['tmp_name']) && @unlink($file['tmp_name']);
            throw new OptimizationException(
                OptimizationException::ERR_LOCAL_SERVER_ERROR,
                "Upload failed with error {$file['error']} - {$this->uploadErrorCodeToMessage($file['error'])}",
                $task_id
            );
        }


        if(!preg_match('~^image/\w+$~', $file['type'])){
            file_exists($file['tmp_name']) && @unlink($file['tmp_name']);
            throw new OptimizationException(
                OptimizationException::ERR_PROTOCOL_ERROR,
                "Missing image expected as a result, {$file['type']} given",
                $task_id
            );
        }

        $content = @file_get_contents($file['tmp_name']);
        file_exists($file['tmp_name']) && @unlink($file['tmp_name']);
        if($content === false){
            throw new OptimizationException(
                OptimizationException::ERR_LOCAL_SERVER_ERROR,
                "Failed to read uploaded file content from '{$file['tmp_name']}'",
                $task_id
            );
        }

        if(md5($content) !== $_POST[self::PARAM_RESULT_MD5]){
            throw new OptimizationException(
                OptimizationException::ERR_PROTOCOL_ERROR,
                "Checksum send and delivered result do not match",
                $task_id
            );
        }

        $image = new ImageContent($content, $file['name']);
        return new OptimizationResult($task_id, $image);
    }

    /**
     * Successfully delivered result expected confirmation
     * @see sendConfirmDeliveryJSON
     *
     * @param string $task_id
     * @return array
     */
    public function getConfirmDeliveryJSON($task_id)
    {
        return [self::PARAM_TASK_ID => $task_id];
    }

    /**
     * @param string $task_id
     * @param bool $exit
     */
    public function sendConfirmDeliveryJSON($task_id,  $exit = true)
    {
        header("Content-Type: application/json");
        echo json_encode($this->getConfirmDeliveryJSON($task_id));
        if($exit){
            exit();
        }
    }

    /**
     * @param int $code
     * @return string
     */
    private function uploadErrorCodeToMessage($code)
    {
        switch ($code) {
            case UPLOAD_ERR_INI_SIZE:
                $message = "The uploaded file exceeds the upload_max_filesize directive in php.ini";
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $message = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
                break;
            case UPLOAD_ERR_PARTIAL:
                $message = "The uploaded file was only partially uploaded";
                break;
            case UPLOAD_ERR_NO_FILE:
                $message = "No file was uploaded";
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $message = "Missing a temporary folder";
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $message = "Failed to write file to disk";
                break;
            case UPLOAD_ERR_EXTENSION:
                $message = "File upload stopped by extension";
                break;

            default:
                $message = "Unknown upload error";
                break;
        }
        return $message;
    }
}
