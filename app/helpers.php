<?php

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByPath;

if (!function_exists('ativo')) {
    function ativo($is)
    {
        $class = $is ? 'bg-success' : 'bg-danger';

        return "<div class='{$class}' style='width:10px; height:10px; position: relative; margin-top: 7px'></div>";
    }
}

if (!function_exists('btnLinkIcon')) {
    function btnLinkIcon($url, $icon, $title = '', $class = 'btn-outline-primary', $target = '')
    {
        $html = "<a target='{$target}' href='{$url}' class='btn {$class}' title='{$title}'>
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
    function btnLinkDelIcon($url, $icon = 'fas fa-trash-alt', $class = 'btn-outline-danger btn-sm btn-link-delete', $title = '', $textConfirm = 'Tem certeza que deseja deletar essa linha?', $action = 'DELETE')
    {
        $form_id = sha1($url);
        $html = btnLinkIcon("#{$form_id}", $icon, $title, $class);
        $html .= Form::open([
            'url' => $url,
            'id' => $form_id,
            'method' => $action,
            'class' => 'form-delete-confirmation',
            'data-text' => $textConfirm
        ]);
        $html .= Form::close();
        return $html;
    }
}

if (!function_exists('tenantRoute')) {
    function tenantRoute($fn, $middleware = 'web')
    {
        /*Route::middleware([
            'web',
            InitializeTenancyByDomain::class,
            PreventAccessFromCentralDomains::class,
        ])->group(function ()  use($fn){
            $fn();
        });

        Route::middleware([
            'api',
            InitializeTenancyByDomain::class,
            PreventAccessFromCentralDomains::class,
        ])->group(function ()  use ($fn) {
            $fn();
        });*/

        Route::group([
            'prefix' => '/{tenant}',
            'middleware' => [InitializeTenancyByPath::class, $middleware],
        ], function () use ($fn) {
            $fn();
        });
    }
}

if (!function_exists('str')) {
    function str()
    {
        return app(Illuminate\Support\Str::class);
    }
}
