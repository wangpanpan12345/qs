<?php
/**
 * Created by PhpStorm.
 * User: wangpan
 * Date: 17/2/21
 * Time: 下午1:39
 */
namespace App\Lib\Password;

trait RedirectsUsers
{
    /**
     * Get the post register / login redirect path.
     *
     * @return string
     */
    public function redirectPath()
    {
        return property_exists($this, 'redirectTo') ? $this->redirectTo : '/home';
    }
}
