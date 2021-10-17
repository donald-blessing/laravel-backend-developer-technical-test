<?php

namespace App\Traits;

/**
 * Trait HTMLMessages
 * @package App\Traits
 */
trait HTMLMessages
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
    protected function setHTMLMessage(string $message, string $type)
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
    protected function getHTMLMessages()
    {
        return [
            'error'     =>  $this->errorMessages,
            'info'      =>  $this->infoMessages,
            'success'   =>  $this->successMessages,
            'warning'   =>  $this->warningMessages,
        ];
    }

    /**
     * Flushing html messages to Laravel's session
     */
    protected function showHTMLMessages()
    {
        if ($this->errorMessages) {
            alert()->html('Error', implode('<br/>', $this->errorMessages), 'error');
        }
        if ($this->infoMessages) {
            alert()->html('Info', implode('<br/>', $this->infoMessages), 'info');
        }
        if ($this->successMessages) {
            alert()->html('Success', implode('<br/>', $this->successMessages), 'success');
        }
        if ($this->warningMessages) {
            alert()->html('Warning', implode('<br/>', $this->warningMessages), 'warning');
        }
    }
}
