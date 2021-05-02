<?php


namespace tplpp;


class Template {

    private array $values;
    private array $booleans;
    private string $tpl;

    public function __construct(string $tpl) {
        $this->tpl = $tpl;
    }

    public function setParent(Template $template): Template {
        $this->values = $template->values;
        $this->booleans = $template->booleans;
        return $this;
    }

    public function setValue(string $name, $value): Template {
        $this->values[$name] = $value;

        return $this;
    }

    public function setBool(string $name, bool $bool): Template {
        $this->booleans[$name] = $bool;

        return $this;
    }

    public function build(): string{
        foreach($this->values as $name => $value) {
            $this->tpl = str_replace("[$name]", $value, $this->tpl);
        }

        foreach($this->booleans as $name => $value) {
            if($value) {
                $this->tpl = str_replace('{'.$name.'}', "", $this->tpl);
                if(preg_match_all('/{!'.$name.'}(.+?){!'.$name.'}/', $this->tpl, $output_array)) {
                    foreach($output_array[0] as $a) {
                        $this->tpl = str_replace($a, "", $this->tpl);
                    }
                }
            }else {
                $this->tpl = str_replace('{!'.$name.'}', "", $this->tpl);
                if(preg_match_all('/{'.$name.'}(.+?){'.$name.'}/', $this->tpl, $output_array)) {
                    foreach($output_array[0] as $a) {
                        $this->tpl = str_replace($a, "", $this->tpl);
                    }
                }
            }
        }

        if(preg_match_all('/\[tpl-(.+?): (.+?)]/', $this->tpl, $output_array)){
            for($i = 0, $iMax = count($output_array[0]); $i < $iMax; $i++){
                if($output_array[1][$i]==="use") {
                    $childTemplate = TplPP::getTemplate($output_array[2][$i]);
                    $childTemplate->setParent($this);

                    $this->tpl = str_replace($output_array[0][$i], $childTemplate->build(), $this->tpl);
                }
            }
        }

        return $this->tpl;
    }
}