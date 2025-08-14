<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slider;

class AdminSliderController extends Controller
{
    public function index() 
    {
        $sliders = Slider::get();
        return view('admin.slider.index', compact('sliders'));
    }
}
