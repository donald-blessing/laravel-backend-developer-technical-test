<?php

namespace App\Traits;

/**
 * Trait ToastMessages
 * @package App\Traits
 */
trait ToastMessages
{
    /**
     * @var array
     */
    protected $errorMessages = [];

    /**
     * @var array
     */
    protected $infoMessages = [];

    /**
     * @var array
     */
    protected $successMessages = [];

    /**
     * @var array
     */
    protected $warningMessages = [];

    /**
     * @param string $message
     * @param string $type
     */
    protected function setToastMessage(string $message, string $type)
    {
        $model = 'infoMessages';

        switch ($type) {
            case 'info': {
                    $model = 'infoMessages';
                }
                break;
            case 'error': {
                    $model = 'errorMessages';
                }
                break;
            case 'success': {
                    $model = 'successMessages';
                }
                break;
            case 'warning': {
                    $model = 'warningMessages';
                }
                break;
        }

        if (is_array($message)) {
            foreach ($message as $value) {
                array_push($this->$model, $value);
            }
        } else {
            array_push($this->$model, $message);
        }
    }

    /**
     * @return array
     */
    protected function getToastMessages()
    {
        return [
            'error'     =>  $this->errorMessages,
            'info'      =>  $this->infoMessages,
            'success'   =>  $this->successMessages,
            'warning'   =>  $this->warningMessages,
        ];
    }

    /**
     * Flushing toast messages to Laravel's session
     */
    protected function showToastMessages()
    {
        if ($this->errorMessages) {
            toast(implode('<br/>', $this->errorMessages), 'error');
        }
        if ($this->infoMessages) {
            toast(implode('<br/>', $this->infoMessages), 'info');
        }
        if ($this->successMessages) {
            toast(implode('<br/>', $this->successMessages), 'success');
        }
        if ($this->warningMessages) {
            toast(implode('<br/>', $this->warningMessages), 'warning');
        }
    }
}
