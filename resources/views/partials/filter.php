<?php

$str = "";
foreach ($filter as $rs) {
    if (!empty($_GET[$rs['name']])) {
        $str .= __($rs['label']) . ': ';

        switch ($rs['type']) {
            case 'date_between':
                $str .= str()->date($_GET[$rs['name']][0]) . ' até ' . str()->date($_GET[$rs['name']][1]);
                break;
            case 'checkbox':
                foreach ($rs['options'] as $k => $option) {
                    if ((empty($_GET[$rs['name']]) && in_array($k, $rs['values'] ?? []))
                        || (!empty($_GET[$rs['name']]) && in_array($k, $_GET[$rs['name']]))
                    ) {
                        $str .= __($option) . ', ';
                    } 
                }
                $str = substr($str, 0, -2);
                break;
            default: $str .= $_GET[$rs['name']];
        }

        $str .= '; ';
    }
}

$str = substr($str, 0, -2);

if (empty($str)) {
    $str = "Filtrar";
}

$idFilter = rand(1000, 9999);

?>

<a href="#" data-toggle="modal" data-target="#exampleModal<?php echo $idFilter; ?>" data-bs-toggle="modal" data-bs-target="#exampleModal<?php echo $idFilter; ?>">
    <i class='fa fa-filter'></i> <?php echo $str; ?>
</a>

<div class="modal fade" id="exampleModal<?php echo $idFilter; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg1">
        <form class="modal-content" method="get">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><?php echo __('Filtrar')?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-0">
                <?php
                foreach ($filter as $rs) {
                    echo '<div class="card mt-3">';
                    echo '<div class="card-header" onclick="$(this).parent().find(\'.card-body\').toggleClass(\'d-none\')">'.__($rs['label']).'</div>';
                    echo '<div class="card-body ' . (empty($_GET[$rs['name']]) ? 'd-none' : '') . '">';
                    switch ($rs['type']) {
                        case 'text':
                            echo '<input type="text" ';
                            echo 'placeholder="' . __($rs['placeholder'] ?? '') . '" ';
                            echo 'value="' . (!empty($_GET[$rs['name']]) ? $_GET[$rs['name']] : null) . '" ';
                            echo 'class="form-control" name="' . $rs['name'] . '">';
                            break;
                        case 'checkbox':
                            foreach ($rs['options'] as $k => $option) {
                                $checked = "";
                                if ((empty($_GET[$rs['name']]) && in_array($k, $rs['values'] ?? []))
                                    || (!empty($_GET[$rs['name']]) && in_array($k, $_GET[$rs['name']]))
                                ) {
                                    $checked = "checked";
                                } 
                                echo "<div><label>";
                                echo "<input type='checkbox' value='{$k}' name='{$rs['name']}[]' {$checked} /> ";
                                echo $option;
                                echo "</label></div>";
                            }
                            break;
                        case 'selected':
                            echo "<select>";
                            foreach ($rs['options'] as $k => $option) {
                                $checked = "";
                                if ((empty($_GET[$rs['name']]) && in_array($k, $rs['values'] ?? []))
                                    || (!empty($_GET[$rs['name']]) && in_array($k, $_GET[$rs['name']]))
                                ) {
                                    $checked = "selected";
                                } 
                                echo "<option value='{$k}' {$checked}> ";
                                echo $option;
                                echo "</option>";
                            }
                            echo "</select>";
                            break;

                        case 'date_between':
                            echo '<div class="row">';
                            echo '<div class="col-6">';
                            echo '<input type="date" ';
                            echo 'placeholder="' . __($rs['placeholder'] ?? '') . '" ';
                            echo 'value="' . (!empty($_GET[$rs['name']][0]) ? $_GET[$rs['name']][0] : ($rs['value'][0] ?? null)) . '" ';
                            echo 'class="form-control" name="' . $rs['name'] . '[0]">';
                            echo '</div>';

                            echo '<div class="col-6">';
                            echo '<input type="date" ';
                            echo 'placeholder="' . __($rs['placeholder'] ?? '') . '" ';
                            echo 'value="' . (!empty($_GET[$rs['name']][1]) ? $_GET[$rs['name']][1] : ($rs['value'][1] ?? null)) . '" ';
                            echo 'class="form-control" name="' . $rs['name'] . '[1]">';
                            echo '</div>';

                            echo '</div>';
                            break;
                        case 'number':
                            echo '<input type="number" ';
                            echo 'placeholder="' . __($rs['placeholder'] ?? '') . '" ';
                            echo 'value="' . (!empty($_GET[$rs['name']]) ? $_GET[$rs['name']] : null) . '" ';
                            echo 'class="form-control" name="' . $rs['name'] . '">';
                            break;
                    }
                    echo '</div>';
                    echo '</div>';
                }
                ?>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary"><?php echo __('Aplicar') ?></button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal"><?php echo __('Fechar') ?></button>
            </div>
        </form>
    </div>
</div>