<?php


class PathHelper
{
    public static function getDirectory($path)
    {
        $ds = addslashes(DS);

        //dd($_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);

        return preg_replace("/(?!$ds)[^$ds]*$/", '', $path);
    }

    public static function dirToUrl($path)
    {

    }

    public static function parentFolders($directory)
    {
        if (strpos($directory, ROOT) === 0)
        {
            return substr($directory, strlen(ROOT) + 1);

            return array_filter(
                    explode('\\',
                        substr($directory, strlen(ROOT))),
                            function($e) {   if (!empty($e)) return $e; });
        }
    }
}