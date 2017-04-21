<?php
namespace App\Lib\Hashing;

//use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\SessionGuard;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;
use Illuminate\Http\Request;

/**
 * Created by PhpStorm.
 * User: wangpan
 * Date: 17/2/20
 * Time: 下午5:56
 */
class AuthmeHasher implements HasherContract
{

    /**
     * Hash the given value.
     *
     * @param  string $value
     * @param  array $options
     * @return string
     */
    public function make($value, array $options = [])
    {
        $hash = hash('sha256', $value . $options["email"]);

        return $hash;
    }

    /**
     * Check the given plain value against a hash.
     *
     * @param  string $value
     * @param  string $hashedValue
     * @param  array $options
     * @return bool
     */
    public function check($value, $hashedValue, array $options = [])
    {
        if (strlen($hashedValue) === 0) {
            return false;
        }
        return $hashedValue === hash('sha256', $value . $options["email"]);
    }

    /**
     * Check if the given hash has been hashed using the given options.
     *
     * @param  string $hashedValue
     * @param  array $options
     * @return bool
     */
    public function needsRehash($hashedValue, array $options = [])
    {
        if (strlen($hashedValue) === 0) {
            return false;
        }
        return $hashedValue;
    }


}