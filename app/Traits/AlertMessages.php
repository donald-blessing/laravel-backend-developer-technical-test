<?php

namespace App\Traits;

/**
 * Trait AlertMessages
 * @package App\Traits
 */
trait AlertMessages
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
    protected function setAlertMessage(string $message, string $type)
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
    protected function getAlertMessages()
    {
        return [
            'error'     =>  $this->errorMessages,
            'info'      =>  $this->infoMessages,
            'success'   =>  $this->successMessages,
            'warning'   =>  $this->warningMessages,
        ];
    }

    /**
     * Flushing alert messages to Laravel's session
     */
    protected function showAlertMessages()
    {
        if ($this->errorMessages) {
            alert()->error('Error', implode('<br/>', $this->errorMessages));
        }
        if ($this->infoMessages) {
            alert()->info('Info', implode('<br/>', $this->infoMessages));
        }
        if ($this->successMessages) {
            alert()->success('Success', implode('<br/>', $this->successMessages));
        }
        if ($this->warningMessages) {
            alert()->warning('Warning', implode('<br/>', $this->warningMessages));
        }
    }

    /**
     * Display alert message
     *
     * @param string $message
     * @param string $type
     *
     * @return void
     */
    protected function displayAlertMessage($message, $type)
    {
        $this->setAlertMessages($message, $type);
        $this->showAlertMessages();
    }
}
