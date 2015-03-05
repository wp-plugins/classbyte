<?php
namespace CB;

if (!defined("ABSPATH")) exit;

/**
 * Class Session for Wordpress
 * @package CB
 */

class Session
{
    public static $defaultKey = '';

    /**
     * @param $key
     * @param mixed $data
     * @param int $expire
     */
    public static function set($key, $data, $expire = 0)
    {
        if ($expire && is_numeric($expire)) {
            $expire = (int) $expire;
        } else {
            $expire = DAY_IN_SECONDS;
        }

        self::delete($key);
        $unique_id = md5(time() . mt_rand());
        set_transient($unique_id, $data, $expire);
        setcookie($key, $unique_id, time() + $expire, COOKIEPATH);
    }

    /**
     * @param string $key
     */
    public static function delete($key)
    {
        delete_transient(self::getHash($key));
        setcookie($key, "", time() - 3600, COOKIEPATH);
    }

    /**
     * @param $key
     * @return bool
     */
    public static function exist($key)
    {
        return (isset($_COOKIE[$key]) && get_transient(self::getHash($key)) !== false) ? true : false;
    }

    /**
     * @param $key
     * @return mixed
     */
    public static function getHash($key)
    {
        return (isset($_COOKIE[$key])) ? $_COOKIE[$key] : false;
    }

    /**
     * @param $key
     * @return mixed|string
     */
    public static function get($key)
    {
        $transientHash = self::getHash($key);
        return ($transientHash && get_transient($transientHash) !== false) ? get_transient($transientHash) : '';
    }
}
