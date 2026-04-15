<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class RoutingController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function __construct()
    {
        // $this->
        // middleware('auth')->
        // except('index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Auth::guard('admin')->check()) {
            return redirect('/dashboards/index');
        } else {
            return redirect('admin/login');
        }
    }

    /**
     * Display a view based on first route param
     *
     * @return \Illuminate\Http\Response
     */
    public function root(Request $request, $first = null)
    {
        if (!$first)
            return $this->index($request);
        if (view()->exists($first)) {
            return view($first);
        }
        return $this->index($request);
    }

    /**
     * second level route
     */
    public function secondLevel(Request $request, $first = null, $second = null)
    {
        if (!$first || !$second)
            return $this->index($request);
        $view = $first . '.' . $second;
        if (view()->exists($view)) {
            return view($view);
        }
        return $this->index($request);
    }

    /**
     * third level route
     */
    public function thirdLevel(Request $request, $first = null, $second = null, $third = null)
    {
        if (!$first || !$second || !$third)
            return $this->index($request);
        $view = $first . '.' . $second . '.' . $third;
        if (view()->exists($view)) {
            return view($view);
        }
        return $this->index($request);
    }
}

