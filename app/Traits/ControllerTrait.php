<?php

namespace App\Traits;

use App\Traits\AlertMessages;
use App\Traits\FlashMessages;
use App\Traits\HTMLMessages;
use App\Traits\ToastMessages;

trait ControllerTrait
{
    use AlertMessages;
    use FlashMessages;
    use HTMLMessages;
    use ToastMessages;

    /**
     * @var null
     */
    protected $data = null;

    /**
     * @param $title
     * @param $subTitle
     */
    public function setPageTitle($title, $subTitle = null)
    {
        view()->share(['pageTitle' => $title, 'subTitle' => $subTitle]);
    }

    /**
     * @param $title
     * @param $subTitle
     */
    public function setPageValue(array $value)
    {
        view()->share($value);
    }

    /**
     * @param int $errorCode
     * @param null $message
     * @return \Illuminate\Http\Response
     */
    public function showErrorPage($errorCode = 404, $message = null)
    {
        $data['message'] = $message;
        return response()->view('errors.' . $errorCode, $data, $errorCode);
    }

    /**
     * @param bool $error
     * @param int $responseCode
     * @param array $message
     * @param null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseJson($error = true, $responseCode = 200, $message = [], $data = null)
    {
        return response()->json([
            'error'         =>  $error,
            'response_code' => $responseCode,
            'message'       => $message,
            'data'          =>  $data
        ]);
    }

    /**
     * @param $route
     * @param $message
     * @param string $type
     * @param bool $error
     * @param bool $withOldInputWhenError
     * @return \Illuminate\Http\RedirectResponse
     */
    public function responseRedirect($route, $message, $type = 'info', $error = false, $withOldInputWhenError = false)
    {
        $this->setFlashMessage($message, $type);
        $this->showFlashMessages();

        if ($error && $withOldInputWhenError) {
            return redirect()->back()->withInput();
        }

        return redirect()->route($route);
    }

    /**
     * @param $message
     * @param string $type
     * @param bool $error
     * @param bool $withOldInputWhenError
     * @return \Illuminate\Http\RedirectResponse
     */
    public function responseRedirectBack($message, $type = 'info', $error = false, $withOldInputWhenError = false)
    {
        $this->setFlashMessage($message, $type);
        $this->showFlashMessages();
        if ($error && $withOldInputWhenError) {
            return redirect()->back()->withInput();
        }
        return redirect()->back();
    }
}
