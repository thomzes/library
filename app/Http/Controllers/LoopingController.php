<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoopingController extends Controller
{
    public function printNumbers()
    {
        $result = [];
        for ($i = 1; $i <= 100; $i++) {
            if ($i % 3 == 0 && $i % 5 == 0) {
                $result[] = "TigaLima";
            } elseif ($i % 3 == 0) {
                $result[] = "Tiga";
            } elseif ($i % 5 == 0) {
                $result[] = "Lima";
            } else {
                $result[] = (string)$i;
            }
        }

    echo implode(", ", $result);
    }
}
