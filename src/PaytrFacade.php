<?php
/**
 * @author Gizem Sever <gizemsever68@gmail.com>
 */

namespace Gizemsever\LaravelPaytr;

use Illuminate\Support\Facades\Facade;

class PaytrFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return Paytr::class;
    }
}