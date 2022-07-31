<?php

namespace ConsolePrettyLog;

/**
 * Class Line
 * @package ConsolePrettyLog
 * @author William Alvares <30/07/2022 20:09>
 */
class Line
{
    /**
     * @var int[]
     */
    private array $codes;
    /**
     * @var array|null
     */
    private ?array $texts;
    /**
     * @var array|null
     */
    private ?array $styles;
    /**
     * @var string
     */
    private string $mask;
    /**
     * @var array|null
     */
    private ?array $columnsSize;
    /**
     * @var string
     */
    private string $separator;
    /**
     * @var string
     */
    private string $paddingCharacter;
    /**
     * @var bool
     */
    private bool $enableDate;
    /**
     * @var string
     */
    private string $dateFormat;

    /**
     */
    public function __construct()
    {
        $this->codes = [
            'bold' => 1,
            'italic' => 3,
            'underline' => 4,
            'strikethrough' => 9,
            'red' => 31,
            'green' => 32,
            'yellow' => 33,
            'blue' => 34,
            'magenta' => 35,
            'cyan' => 36,
            'white' => 37,
            'redbg' => 41,
            'greenbg' => 42,
            'yellowbg' => 43,
            'bluebg' => 44,
            'magentabg' => 45,
            'cyanbg' => 46,
            'lightgreybg' => 47
        ];

        $this->mask = "";
        $this->texts = null;
        $this->columnsSize = null;
        $this->separator = "|";
        $this->paddingCharacter = ".";
        $this->enableDate = true;
        $this->dateFormat = "Y-m-d H:i:s";
    }

    /**
     * @param string $separator
     * @return Line
     */
    public function separator(string $separator): Line
    {
        $this->separator = $separator;
        return $this;
    }

    /**
     * @param string $paddingCharacter
     * @return Line
     */
    public function paddingCharacter(string $paddingCharacter): Line
    {
        $this->paddingCharacter = $paddingCharacter;
        return $this;
    }

    /**
     * @param bool $enableDate
     * @return Line
     */
    public function enableDate(bool $enableDate = true): Line
    {
        $this->enableDate = $enableDate;
        return $this;
    }

    /**
     * @param string $dateFormat
     * @return Line
     */
    public function dateFormat(string $dateFormat): Line
    {
        $this->dateFormat = $dateFormat;
        return $this;
    }

    /**
     * @param string|null $text
     * @param array|null $styles
     * @return Line
     */
    public function text(?string $text, ?array $styles = []): Line
    {
        $formatMap = array_map(function ($v) {
            return $this->codes[$v];
        }, $styles);

        $this->texts[] = iconv('UTF-8', 'ascii//TRANSLIT', $text);
        $this->styles[] = implode(';', $formatMap);

        return $this;
    }

    /**
     * @param array|null $columnsSize
     * @return Line
     */
    public function columnsSize(?array $columnsSize): Line
    {
        $this->columnsSize = $columnsSize;
        return $this;
    }

    /**
     * @return void
     */
    public function print(): void
    {
        if ($this->columnsSize === null) {
            $this->texts();
        } else {
            $this->textsWithPaddings();
        }

        if ($this->enableDate === true) {
            echo sprintf("\e[34m[%s] \e[0m", date($this->dateFormat));
        }

        echo vsprintf($this->mask . "\n", $this->texts);

        $this->newLine();
    }

    /**
     * @return void
     */
    private function texts(): void
    {
        $count = count($this->texts);

        $i = 0;
        foreach ($this->texts as $key => $text) {
            $i++;
            $this->mask .= "\e[{$this->styles[$key]}m";
            $this->mask .= "%s";
            $this->mask .= "\e[0m";
            $this->mask .= $count === $i ? "" : " {$this->separator} ";
        }
    }

    /**
     * @return void
     */
    private function textsWithPaddings(): void
    {
        $count = count($this->columnsSize);

        $i = 0;
        foreach ($this->columnsSize as $key => $size) {
            $i++;
            $this->mask .= "\e[{$this->styles[$key]}m";
            $this->mask .= "%-'{$this->paddingCharacter}{$size}s";
            $this->mask .= "\e[0m";
            $this->mask .= $count === $i ? "" : " {$this->separator} ";
        }
    }

    /**
     * @return void
     */
    private function newLine(): void
    {
        $this->texts = [];
        $this->styles = [];
        $this->mask = "";
    }
}