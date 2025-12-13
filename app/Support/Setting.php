<?php

namespace App\Support;

/**
 * Class Setting
 * @package App\Support
 */
class Setting
{
    public static function get($key, $default = null)
    {
        if ($setting = \App\Models\Setting::find($key)) {
            return $setting->value;
        }
        return $default;
    }

    public static function setEnv($key, $value = null): void
    {
        $path = base_path('.env');

        if (file_exists($path)) {
            $content = file_get_contents($path);
            
            $val = (string)$value;
            // Wrap in quotes if contains spaces
            if (strpos($val, ' ') !== false && strpos($val, '"') === false) {
                $val = '"' . $val . '"';
            }

            if (preg_match("/^{$key}=/m", $content)) {
                $content = preg_replace("/^{$key}=.*/m", "{$key}={$val}", $content);
            } else {
                $content .= PHP_EOL . "{$key}={$val}";
            }

            file_put_contents($path, $content);
        }
    }
}
