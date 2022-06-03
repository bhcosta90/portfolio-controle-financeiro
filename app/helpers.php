<?php

use Costa\Modules\Charge\Utils\Enums\ChargeStatusEnum;

if (!function_exists('isExpired')) {
    function isExpired($status, $date, $value){
        $status = ChargeStatusEnum::from($status);
        $class = '';
        $dateStart = (new DateTime('first day of this month'))->format('Y-m-d');

        if ($status == ChargeStatusEnum::COMPLETED) {
            $class = 'text-success';
        } elseif ($date < $dateStart) {
            $class = 'text-danger';
        }

        return "<span class='{$class}'>{$value}</span>";
    }
}
if (!function_exists('links')) {
    function links(array $links = []): string
    {
        $str = "";
        $spanStart = "<span>";
        $spanFinish = "</span>";

        foreach ($links as $key => $value) {
            $str .= '<div class="action-column">';
            if ($key === 'edit') {
                $str .= "<a class='btn btn-outline-info btn-xs' href='{$value['link']}'>";
                $str .= $spanStart;
                $str .= '<i class="fas fa-pencil-alt"></i>';
                $str .= $spanFinish;
                $str .= "</a>";
            }

            if ($key === 'delete') {

                $data = [
                    'title' => __('Tem certeza?'),
                    'body' => __('Ao executar essa ação, o registro vai ser deletado'),
                    'yes' => __('Sim'),
                    'not' => __('Cancelar'),
                    'cancel' => __('Cancelar'),
                ];

                $strData = "";
                foreach ($data as $k => $v) {
                    $strData .= "data-{$k}='{$v}'";
                }

                $str .= "<span {$strData} class='btn btn-form-delete btn-outline-danger btn-xs' href='javascript:void(1)'>";
                $str .= $spanStart;
                $str .= '<i class="fas fa-trash-alt"></i>';
                $str .= $spanFinish;
                $str .= '<form method="POST" action="' . $value['link'] . '">';
                $str .= csrf_field() . method_field('DELETE');
                $str .= '</form>';
            }

            if (is_numeric($key)) {
                $link = $value['link'];
                $action = $value['link'];

                if ($value['form'] ?? null === true) {
                    $link = "javascript:void(1)";
                }

                $str .= "<a class='btn {$value['btn']} action-form btn-xs' href='{$link}'>";
                $str .= $spanStart;
                $str .= '<i class="' . $value['icon'] . '"></i>';
                $str .= $spanFinish;
                $str .= "</a>";

                $str .= '<form method="POST" action="' . $action . '">';
                $str .= csrf_field() . method_field('POST');
                $str .= '</form>';
            }

            $str .= '</div>';
        }

        return $str;
    }
}

if (!function_exists('register')) {
    function register($link, $title): string
    {
        $str = "";
        $str .= "<a class='btn btn-outline-success btn-sm' href='{$link}'>";
        $str .= "<span>";
        $str .= '<i class="far fa-plus-square"></i> ' . $title;
        $str .= "</span>";
        $str .= "</a>";

        return $str;
    }
}
