<?php

namespace Core\Report\Type\Abstracts;

abstract class ReportTypeAbstract
{
    public array $report = [];
    protected array $columns = [];
    protected array $column = [];

    protected array $line = [];

    public function __get($name)
    {
        $name = mb_strtolower($name);

        if (strpos($name, 'column_') === 0) {
            return $this->column[substr($name, 7)];
        }

        if (strpos($name, 'line_') === 0) {
            return $this->line[substr($name, 5)];
        }

        return $this->getLineColumn($name);
    }

    public function __set($name, $value)
    {
        $arOption = explode("_", strtolower($name));
        $strOption = mb_strtolower($arOption[0]);

        if ($strOption == 'column') {
            $this->column[$arOption[1]] = $value;
            return null;
        }

        if ($strOption == 'line') {
            $this->line[$arOption[1]] = $value;
            return null;
        }

        $this->report[$name] = $value;
        if (($name == 'title') && (($this->report['title_select'] ?? null) == null)) {
            $this->report['title_select'] = $value;
        }
    }

    private function getLineColumn($name)
    {
        if ($name == 'column') {
            return $this->column;
        }

        if ($name == 'line') {
            return $this->line;
        }

        return null;
    }

    abstract public function addReport();

    abstract public function addColumn();

    abstract public function addLine();

    abstract public function addPage();

    abstract public function render();

    abstract public function __toString();

    protected function sizeFixe($indexColumn, $arColumn, $scapeTotalAvailable = 100, $scapeLine = 0)
    {
        $sizeColumn = $arColumn[$indexColumn]['size'];
        $sizeAllColumns = 0;
        $nmColumns = 0;
        foreach ($arColumn as $arColumn) {
            $sizeAllColumns += $arColumn['size'];
            $nmColumns++;
        }
        $scapeTotalAvailable = $scapeTotalAvailable - ($scapeLine * $nmColumns);
        $sizeFixe = ($sizeAllColumns > 0) ? ($sizeColumn * $scapeTotalAvailable) / $sizeAllColumns : $sizeColumn * $scapeTotalAvailable;
        return round($sizeFixe, 2);
    }
}
