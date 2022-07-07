<?php

namespace Core\Application\Report\Type;

class Html extends Abstracts\ReportTypeAbstract
{
    private $width = '100%';
    private $resource = null;
    private $lineAdd = 0;

    public function __get($name)
    {
        $value = parent::__get($name);
        $value = preg_replace('/%%.+?%%/', ' ', $value);
        return $value;
    }

    public function addReport()
    {
        if ($this->showOnlyHeader) {
            throw new Exceptions\ReportTypeException("Displaying report header only.", 302);
        }

        if (!$this->showHeader) {
            return null;
        }

        $this->width = $this->horizontal ? '1200' : '100%';
        $header = '
                <div class="header" width="' . $this->width . '">
                    <div class="abovetitle">' . $this->abovetitle . '</div>
                    <h1>' . $this->title . '</h1>
                    <div class="subtitle">' . $this->subtitle . '</div>
                </div>';

        $this->add($header);
    }

    protected function add($html)
    {
        $this->resource .= $html;
    }

    public function addColumn()
    {
        $varColumnText = $this->column_text;
        $varColumnText = ($varColumnText !== "") ? $varColumnText : '&nbsp;';
        $varColumnText = nl2br(str_replace(' ', '&nbsp;', $varColumnText));
        $this->column_text = $varColumnText;
        $this->columns[] = $this->column;

        $this->column_style = '';
    }

    public function addLine()
    {
        $lineStyle = $this->linha_style;
        if ($lineStyle == 'capa') {
            return null;
        }

        $column = "";
        foreach ($this->columns as $key => $arColuna) {
            $text = !empty($arColuna['link']) ? "<a href='{$arColuna['link']}' target='_blank'>{$arColuna['text']}</a>" : $arColuna['text'];
            if ($lineStyle == 'header') {
                $column .= '<th width="' . $this->sizeFixe($key, $this->columns) . '%">' .
                    '<div align="' . $arColuna['alignment'] . '">' .
                    $text .
                    '</div>' .
                    '</th>' . "\n";
                continue;
            } else if ($lineStyle == 'footer') {
                $column .= '<td width="' . $this->sizeFixe($key, $this->columns) . '%">' .
                    '<div align="' . $arColuna['alignment'] . '">' .
                    $text .
                    '</div>' .
                    '</td>' . "\n";
                continue;
            }

            if (($arColuna['style'] ?? null) == 'riscado') {
                $column .= '<td class="invalidate" width="' . $this->sizeFixe($key, $this->columns) . '%">' .
                    '<div align="' . $arColuna['alignment'] . '">' .
                    $text .
                    '</div>' .
                    '</td>' . "\n";
            } else if (($arColuna['style'] ?? null) == 'bold') {
                $column .= '<td width="' . $this->sizeFixe($key, $this->columns) . '%">' .
                    '<b><div align="' . $arColuna['alignment'] . '">' .
                    $text .
                    '</div></b>' .
                    '</td>' . "\n";
            } else if (($arColuna['style'] ?? null) == 'total') {
                $column .= '<td class="subTotal" width="' . $this->sizeFixe($key, $this->columns) . '%">' .
                    '<b><div align="' . $arColuna['alignment'] . '">' .
                    $text .
                    '</div></b>' .
                    '</td>' . "\n";
            } else {
                $column .= '<td class="' . ($arColuna['style'] ?? null) . '" width="' . $this->sizeFixe($key, $this->columns) . '%">' .
                    '<div align="' . $arColuna['alignment'] . '">' .
                    $text .
                    '</div>' .
                    '</td>' . "\n";
            }
        }

        $line = $this->executeLine($column);
        $this->lineAdd++;
        $this->columns = [];
        $this->add($line);
    }

    private function executeLine($column)
    {
        $lineStyle = $this->line_style;
        $linha = "";
        $style = "";

        if ($lineStyle == 'header') {
            $linha .= '<table width="' . $this->width . '" >
                          <thead >' .
                '<tr >' . $column .
                '</tr>
                          </thead>
                      </table>';
        } else if ($lineStyle == 'footer') {

            $linha .= '<table width="' . $this->width . '" >
                          <tfoot >' .
                '<tr >' . $column .
                '</tr>
                          </tfoot>
                      </table>';
        } else {
            $typeLine = $this->celBag();
            if ($lineStyle == 'bold') {
                $style = 'bold';
            } else if ($lineStyle == 'text') {
                $typeLine = "";
            }

            $linha .= '<table width="' . $this->width . '" >
                          <tr class="' . $typeLine . ' ' . $style . '">' . $column . '
                          </tr>
                      </table>';
        }

        return $linha;
    }

    private function celBag()
    {
        $lineStyle = $this->linha_style;
        if (
            $this->notold
            || ($lineStyle == 'header')
            || ($lineStyle == 'headerfixe')
            || ($lineStyle == 'footer')
            || ($lineStyle == 'title')
            || ($lineStyle == 'large')
            || ($lineStyle == 'medium')
            || ($lineStyle == 'rtf')
            || ($lineStyle == 'text')
            || ($this->notold)
        ) {
            return '';
        }
        return ($this->lineAdd % 2 == 0) ? '' : 'odd';
    }

    public function __toString()
    {
        return $this->render();
    }

    public function render()
    {
        return '<div class="report">' . $this->resource . '</div>';
    }

    public function addPage()
    {
        new Exceptions\ReportTypeException('Método ainda não implantado adicionarPagina');
    }
}
