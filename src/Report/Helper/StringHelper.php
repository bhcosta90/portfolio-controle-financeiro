<?php

namespace Core\Report\Helper;

class StringHelper
{
    public static function cut(string|null $string, $max, $break = false): string
    {
        $tamanho = strlen($string);

        if ($tamanho <= $max) {
            $new_text = $string;
        } else {
            $max -= 3;
            if ($break) {
                $new_text = trim(substr($string, 0, $max));
            } else {
                $last_space = strrpos(substr($string, 0, $max), ' ');
                $new_text = trim(substr($string, 0, $last_space));
            }

            if (empty($new_text)) {
                return $string;
            }

            $new_text .= '...';
        }

        return $new_text;
    }
}
