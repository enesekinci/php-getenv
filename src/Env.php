<?php

namespace EnesEKINCI\GetEnv;

use EnesEKINCI\GetEnv\Exception\NotFoundEnvFile;

final class Config
{
    private static $instance;
    public static $filePath;
    private $setting;

    private function __construct()
    {
        if (!file_exists(self::$filePath)) {
            throw new NotFoundEnvFile('.env not found');
        }

        $content = removeUtf8Bom(file_get_contents(self::$filePath));
        $this->setting = $this->generateENV($content);
    }

    public function run(string $filePath)
    {
        self::$filePath = $filePath;
        self::instance();
    }

    private static function instance()
    {
        if (null === self::$instance)
            self::$instance = new Config();
        return self::$instance;
    }

    public static function get($key)
    {
        if ($key === 'all')
            return self::instance()->setting;
        return self::instance()->setting->$key ?? null;
    }

    private function generateENV($content)
    {
        $lines = array_filter(preg_split('/\n/', $content));

        $fields = [];

        $fields = array_map(function ($row) {
            $row = explode('=', $row);
            return [$row[0] => $row[1]];
        }, $lines);

        $setting = [];

        foreach ($fields as $field) {
            $val = (array) $field;
            foreach ($val as $k => $v) {
                if (false === strpos($k, '#'))
                    continue;

                $k = trim($k);
                $v = trim($v);

                if (false !== strpos($v, '\'')); {
                    $k = trim($k, "'");
                    $v = trim($v, "'");
                }

                if (false !== strpos($v, '"')); {
                    $k = trim($k, "\"");
                    $v = trim($v, "\"");
                }
                $setting[$k] = $v;
            }
        }
        $setting = (object) array_filter($setting);
        return $setting;
    }
}
