<?php

namespace App\Controllers;
use CodeIgniter\Controller;

class TestController extends Controller
{
  public function index()
  {
      return view('/test');
  }
}
