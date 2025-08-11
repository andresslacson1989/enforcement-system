<?php

namespace App\Http\Controllers\pages;

class MiscError extends Controller
{
  public function index()
  {
    $pageConfigs = ['myLayout' => 'blank'];
    return view('content.pages.pages-misc-error', ['pageConfigs' => $pageConfigs]);
  }
}
