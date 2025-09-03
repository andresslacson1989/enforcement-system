<?php

namespace App\Http\Controllers\pages;

use App\Http\Classes\FormClass;

class ActivityBoard
{
    public function formNew($form)
    {
        return (new FormClass)->new($form);
    }

    public function formView($form, $id)
    {
        return (new FormClass)->view($form, $id);
    }
}
