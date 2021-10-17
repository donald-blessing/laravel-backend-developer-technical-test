<?php

namespace App\Traits;

/**
 * Trait FlashMessages
 * @package App\Traits
 */
trait FlashMessages
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
     * Set flash message
     *
     * @param string $message //Message to display
     * @param string $type //Message type (error, success, info, warning)
     *
     */
    protected function setFlashMessage($message, $type)
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
            foreach ($message as $key => $value) {
                array_push($this->$model, $value);
            }
        } else {
            array_push($this->$model, $message);
        }
    }

    /**
     * @return array
     */
    protected function getFlashMessages()
    {
        return [
            'error'     =>  $this->errorMessages,
            'info'      =>  $this->infoMessages,
            'success'   =>  $this->successMessages,
            'warning'   =>  $this->warningMessages,
        ];
    }

    /**
     * Flushing flash messages to Laravel's session
     */
    protected function showFlashMessages()
    {
        session()->flash('error', implode('<br/>', $this->errorMessages));
        session()->flash('info', implode('<br/>', $this->infoMessages));
        session()->flash('success', implode('<br/>', $this->successMessages));
        session()->flash('warning', implode('<br/>', $this->warningMessages));
    }

    /**
     * Display flash message
     *
     * @param string $message //Message to display
     * @param string $type //Message type (error, success, info, warning)
     *
     * @return void
     */
    protected function displayFlashMessage($message, $type)
    {
        $this->setFlashMessage($message, $type);
        $this->showFlashMessages();
    }
}
