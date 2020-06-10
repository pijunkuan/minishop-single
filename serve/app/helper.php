<?php
/*
 * This file is part of the MiniShop
 *
 * (c) Jacky <pijunkuan@qq.com>
 *
 *  This source file is subject to the MIT license that is bundled
 *  with this source code in the file LICENSE.
 */

if(!function_exists("v")){

}

if(!function_exists("view_template_path")){
    function view_template_path($viewName){
        $namespace = config('minishop.system.theme.use',"default");
        $viewName = preg_match('/::/',$viewName) ? $viewName : $namespace. '::' . $viewName;
        return $viewName;
    }
}


if (!function_exists('array_compress')) {
    function array_compress(array $arr, string $prevKey = ''): array
    {
        $rows = [];
        foreach ($arr as $key => $item) {
            $tmpKey = ($prevKey ? $prevKey . '.' : '') . $key;
            if (is_array($item)) {
                $rows = array_merge($rows, array_compress($item, $tmpKey));
            } else {
                $rows[$tmpKey] = $item;
            }
        }
        return $rows;
    }
}
