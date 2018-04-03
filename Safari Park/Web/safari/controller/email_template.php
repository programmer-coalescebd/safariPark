<?php
/**
 * Created by PhpStorm.
 * User: ahstanin
 * Date: 8/7/17
 * Time: 6:07 PM
 */
(strpos($_SERVER["REQUEST_URI"], "controller") !== false) ? exit('Direct access not allowed') : '';


class email_template
{
    public function execute($file, $input)
    {
        $content = file_get_contents($file . ".php");
        $filter = preg_replace('/<?php(.*?)?>/s', "", $content);
        $filter = str_replace('<?check("view");', "", $filter);
        $filter = str_replace('?>', "", $filter);
        $find = array(
            "{HEADING}",
            "{MESSAGE}",
            "{BUTTON_TEXT}",
            "{BUTTON_LINK}",
            "{DOMAIN}"
        );
        $replace = array(
            $input["0"],
            $input["1"],
            $input["2"],
            $input["3"],
            APP_DOM
        );
        $filter_one = str_replace($find, $replace, $filter);
        return $filter_one;
    }
}