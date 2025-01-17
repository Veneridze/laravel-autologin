<?php
namespace Veneridze\Autologin\Traits;

use Veneridze\Autologin\Facades\Autologin;

trait HasAutologin
{
    public function getAutologinLink(string $link)
    {
        return Autologin::to($this, $link);
    }
}