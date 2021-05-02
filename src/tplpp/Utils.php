<?php


namespace tplpp;


class Utils {

    public static function appendPath(string $rootPath, string $childPath): string{
        $rootPath = str_replace(['/', '\\'], "/", $rootPath);
        $childPath = str_replace(['/', '\\'], "/", $childPath);
        if(!str_ends_with($rootPath, "/")) {
            $rootPath .= "/";
        }

        if(str_starts_with($childPath, "/"))
            $childPath = ltrim($childPath, "/");

        return $rootPath.$childPath;
    }
}