<?php

namespace App\Traits;

trait ResponseTrait {

    /**
     * Standardized professional response format.
     * Mirroring perfume_admin pattern.
     */
    public function response($key, $msg, $data = [], $anotherKey = [], $page = false) {

        $allResponse['key'] = (string) $key;
        $allResponse['msg'] = (string) $msg;

        # additional data
        if (!empty($anotherKey)) {
            foreach ($anotherKey as $otherkey => $value) {
                $allResponse[$otherkey] = $value;
            }
        }

        # res data
        if ([] != $data && (in_array($key, ['fail', 'success', 'needActive', 'exception']))) {
            $allResponse['data'] = $data;
        }

        return response()->json($allResponse, $this->getCode($key));
    }

    public function unauthenticatedReturn() {
        return $this->response('unauthenticated', trans('auth.unauthenticated'));
    }

    public function unauthorizedReturn($otherData = []) {
        return $this->response('unauthorized', trans('auth.not_authorized'), [], $otherData);
    }

    public function failMsg($msg) {
        return $this->response('fail', $msg);
    }

    public function successMsg($msg = 'done') {
        return $this->response('success', $msg);
    }

    public function successData($data) {
        return $this->response('success', trans('apis.success'), $data);
    }

    public function getCode($key) {
        switch ($key) {
        case 'success':
            $code = 200;
            break;
        case 'fail':
            $code = 400;
            break;
        case 'needActive':
        case 'phoneNeedActive':
        case 'emailNeedActive':
            $code = 203;
            break;
        case 'unauthorized':
            $code = 401;
            break;
        case 'unauthenticated':
            $code = 403;
            break;
        case 'blocked':
            $code = 423;
            break;
        case 'exception':
            $code = 500;
            break;
        default:
            $code = 200;
            break;
        }

        return $code;
    }

    public function successResponse($data = [], $message = null) {
        return response()->json([
            'code'    => 200,
            'type'    => 'success',
            'data'    => $data,
            'message' => $message ?? __('admin.success'),
        ]);
    }

    public function failResponse($message = null, $data = null, $code = 300) {
        return response()->json([
            'code'    => $code,
            'type'    => 'error',
            'data'    => $data,
            'message' => $message ?? __('admin.fail'),
        ]);
    }

    public function validationError($errorsBag = null, $message = null) {
        return response()->json([
            'code'    => 300,
            'type'    => 'error',
            'errors'  => $errorsBag,
            'message' => $message ?? __('admin.data_error'),
        ]);
    }
}
