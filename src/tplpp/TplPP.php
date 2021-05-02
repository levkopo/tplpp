<?php

namespace tplpp;

use JetBrains\PhpStorm\Language;

class TplPP {
    protected static string $resources;
    protected static Template $mainTemplate;

    public static function setResources(#[Language("file-reference")] string $resources): void{
        self::$resources = $resources;
    }

    private static function init(): void {
        if(self::$mainTemplate===null) {
            self::$mainTemplate = new Template("");
        }
    }

    public function setValue(string $name, $value): void {
        self::init();
        self::$mainTemplate->setValue($name, $value);
    }

    public function setBool(string $name, bool $bool): void {
        self::init();
        self::$mainTemplate->setBool($name, $bool);
    }

    public static function getTemplate($name): Template {
        self::init();
        $tpl = new Template(file_get_contents(realpath(
            Utils::appendPath(self::$resources, $name.".tpl"))));
        return $tpl->setParent(self::$mainTemplate);
    }
}