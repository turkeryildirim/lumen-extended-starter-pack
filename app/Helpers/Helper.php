<?php

if (!function_exists('get_class_constants')) {
    /**
     *  Get the class constants
     *
     * @param string $className
     * @return array|bool
     * @throws \ReflectionException
     */
    function get_class_constants($className)
    {
        if (!class_exists($className)) {
            return false;
        }

        $object_class = new ReflectionClass($className);
        $constants_array = array_keys($object_class->getConstants());

        if (empty($constants_array)) {
            return false;
        }

        return $constants_array;
    }
}

if (!function_exists('get_class_constants_value')) {
    /**
     * Get the class constants value
     *
     * @param string $className
     * @return array|bool
     * @throws \ReflectionException
     */
    function get_class_constants_value($className)
    {
        $constants_array = get_class_constants($className);

        if (empty($constants_array)) {
            return false;
        }

        $array = array();
        foreach ($constants_array as $constant) {
            $array[] = constant("{$className}::".$constant);
        }

        return $array;
    }
}

if (! function_exists('config_path')) {
    /**
     * Get the configuration path.
     *
     * @param  string $path
     * @return string
     */
    function config_path($path = '')
    {
        return app()->basePath() . DIRECTORY_SEPARATOR. 'config' . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }

    /**
     * Alias of config_path
     *
     * @param  string $path
     * @return string
     */
    function configPath($path = '')
    {
        return config_path($path = '');
    }
}

if (! function_exists('app_path')) {
    /**
     * Get the app path.
     *
     * @param  string $path
     * @return string
     */
    function app_path($path = '')
    {
        return app()->basePath() . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

if (! function_exists('convert_to_datetime')) {
    /**
     * Convert the given string to datetime string with the given format
     *
     * @param        $dateTime
     * @param string $format
     * @return false|string
     */
    function convert_to_datetime($dateTime, $format = 'Y-m-d H:i:s')
    {
        if ($time = strtotime($dateTime)) {
            $date_time = date($format, $time);

            return $date_time;
        }

        return false;
    }
}

if (!function_exists('to_object')) {
    function to_object(array $array)
    {
        if (empty($array)) {
            return null;
        }
        if (count(array_filter(array_keys($array), 'is_string')) === 0) {
            return null;
        }

        $object = new stdClass();
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $value = to_object($value);
            }

            if (is_string($key)) {
                $object->$key = $value;
            }
        }
        return $object;
    }
}

if (!function_exists('create_authorization_token')) {
    function create_authorization_token(\App\Models\UserModel $user)
    {
        return encrypt($user->id .'|'. $user->email. '|'. $user->last_login_date. '|'. time());
    }
}
