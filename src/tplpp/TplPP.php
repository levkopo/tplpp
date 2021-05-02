<?php

namespace tplpp;

use Closure;
use JetBrains\PhpStorm\Language;

class TplPP {
    public static array $functions = [];

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

    public static function initDefaultFunctions(): void {
        self::$functions['use'] = function(array $args){
            /**@var Template $this*/

            $response = "";
            foreach($args as $arg){
                $childTemplate = TplPP::getTemplate($arg);
                $childTemplate->setParent($this);
                $response .= $childTemplate->build();
            }


            return $response;
        };

        self::$functions['php'] = function(array $args){
            /**@var Template $this*/

            $response = "";
            foreach($args as $arg){
                if(function_exists($arg)){
                    $response .= $arg($this);
                }
            }


            return $response;
        };
    }

    public static function setValue(string $name, $value): void {
        self::init();
        self::$mainTemplate->setValue($name, $value);
    }

    public static function setBool(string $name, bool $bool): void {
        self::init();
        self::$mainTemplate->setBool($name, $bool);
    }

    public static function registerFunction(string $name, Closure $function): void {
        self::$functions[$name] = $function;
    }

    public static function getTemplate($name): Template {
        self::init();
        $tpl = new Template(file_get_contents(realpath(
            Utils::appendPath(self::$resources, $name.".tpl"))));
        return $tpl->setParent(self::$mainTemplate);
    }
}