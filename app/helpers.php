<?php

if (!function_exists('btnLinkIcon')) {
    function btnLinkIcon($url, $icon, $title = '', $class = 'btn-outline-primary')
    {
        $html = "<a href='{$url}' class='btn {$class}' title='{$title}'>
                    <i class='{$icon}'></i> {$title}
                 </a>";
        return $html;
    }
}

if (!function_exists('btnLinkEditIcon')) {
    function btnLinkEditIcon($url)
    {
        return btnLinkIcon($url, 'far fa-edit', '', 'btn-outline-primary btn-sm');
    }
}

if (!function_exists('btnLinkAddIcon')) {
    function btnLinkAddIcon($url, $title = 'New register')
    {
        return btnLinkIcon($url, 'far fa-plus', __($title), 'btn-outline-primary btn-sm');
    }
}

if (!function_exists('btnLinkDelIcon')) {
    function btnLinkDelIcon($url, $icon = 'fas fa-trash-alt', $class = 'btn-outline-danger btn-sm btn-link-delete', $title = '', $textConfirm = 'Tem certeza que deseja deletar essa linha?')
    {
        $form_id = sha1($url);
        $html = btnLinkIcon("#{$form_id}", $icon, $title, $class);
        $html .= Form::open([
            'url' => $url,
            'id' => $form_id,
            'method' => 'DELETE',
            'class' => 'form-delete-confirmation',
            'data-text' => $textConfirm
        ]);
        $html .= Form::close();
        return $html;
    }
}
