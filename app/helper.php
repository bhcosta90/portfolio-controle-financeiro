<?php

if (!function_exists('links')) {
    function links(array $links = []): string
    {
        $str = "";
        $spanStart = "<span>";
        $spanFinish = "</span>";

        foreach ($links as $key => $value) {
            if (!empty($value['hidden']) && $value['hidden']) {
                continue;
            }
            $str .= '<div class="action-column">';
            if ($key === 'edit') {
                $str .= "<a class='btn btn-outline-info btn-xs btn-sm' href='{$value['link']}'>";
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

                $str .= "<span {$strData} class='btn btn-form-delete btn-outline-danger btn-xs btn-sm' href='javascript:void(1)'>";
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

                $str .= "<a class='btn {$value['btn']} action-form btn-xs btn-sm' href='{$link}'>";
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

        return $str ?: '-';
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

if (!function_exists('urlBank')) {
    function urlBank($link, $title = 'Back'): string
    {
        $str = "";
        $str .= "<a class='btn btn-outline-info btn-sm' href='{$link}'>";
        $str .= "<span>";
        $str .= '<i class="fas fa-undo-alt"></i> ' . $title;
        $str .= "</span>";
        $str .= "</a>";

        return $str;
    }
}

if (!function_exists('printOut')) {
    function printOut($link, $title): string
    {
        $str = "";
        $str .= "<a class='btn btn-outline-info btn-sm' target='_blank' href='{$link}'>";
        $str .= "<span>";
        $str .= '<i class="fas fa-print"></i> ' . $title;
        $str .= "</span>";
        $str .= "</a>";

        return $str;
    }
}
