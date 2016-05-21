<?php
/**
 * Created by PhpStorm.
 * User: anders
 * Date: 21/05/16
 * Time: 03:04
 */

namespace php_components_essentials;


class Events
{
    private static  $events;
    private static  $wildcard = false;

    /**
     * $event can either be a string or an array of different event types.
     * 
     * @param string | array    $event
     * @param callable          $callback
     */
    public function _onEvent ($event, $callback)
    {
        /**
         * If it's an listener for any type of event.
         */
        if ($event === '*')
        {
            self::$wildcard = true;
        }

        /**
         * If the event type is an string, it can be set as is.
         */
        if (is_string($event) === true)
        {
            self::$events[$event] = &$callback;
        }

        /**
         * Sometimes it cna be desired to specify multiple events
         *  as an array.
         */
        else if (is_array($event) === true)
        {
            $i = sizeof($event);
            while (--$i !== -1) // Run until and while $i is >= 0 (bitwise false)
            {
                self::$events[$event[$i]] = &$callback;
            }
        }
    }

    /**
     * Returns false if no event was triggered.
     * In case the wildcard has been set and not the
     *  event specified by the script, the wildcard event is called.
     *
     * The code then exists if false has not been returned.
     *
     * @param string    $event
     * @param array     $res
     * @return bool || exit()
     */
    protected function triggerEvent (string $event, array $res)
    {
        /**
         * check specific event for firing.
         */
        if (isset(self::$events[$event]))
        {
            call_user_func(self::$events[$event], $res);
        }

        /**
         * Call the wildcard event if set by the programmer.
         */
        else if (self::$wildcard === true)
        {
            call_user_func(self::$events['*'], $res);
        }

        /**
         * Nothing was triggered, false is returned.
         */
        else
        {
            return false; //not triggered..
        }

        /**
         * Stop the code at successfully triggering an event type.
         */
        exit(); //if not returned false, a event was called. stop the code.
    }
}