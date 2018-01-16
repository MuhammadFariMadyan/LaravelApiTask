<?php
namespace App\Helpers;
class Helper
{

    public function setAlert($status)
    {
        if ($status == 1) {
            return "Are you sure you want to inactivate this record?";
        } else {
            return "Are you sure you want to activate this record?";
        }
    }
}