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
    private ?array $textsInitial;
    /**
     * @var array|null
     */
    private ?array $styles;
    /**
     * @var array|null
     */
    private ?array $stylesInitial;
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
        $this->textsInitial = null;
        $this->texts = null;
        $this->stylesInitial = null;
        $this->styles = null;
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
        $this->texts[] = $this->textEnconding($text);
        $this->styles[] = $this->getStyleCodes($styles);

        return $this;
    }

    /**
     * @param string|null $text
     * @param array|null $styles
     * @return Line
     */
    public function textInitial(?string $text, ?array $styles = []): Line
    {
        $this->textsInitial[] = $this->textEnconding($text);
        $this->stylesInitial[] = $this->getStyleCodes($styles);

        return $this;
    }

    /**
     * @param string|null $text
     * @return false|string
     */
    private function textEnconding(?string $text)
    {
        return iconv('UTF-8', 'ascii//TRANSLIT', $text);
    }

    /**
     * @param array|null $styles
     * @return string
     */
    private function getStyleCodes(?array $styles = []): string
    {
        $formatMap = array_map(function ($v) {
            return $this->codes[$v];
        }, $styles);

        return implode(';', $formatMap);
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
        $this->textsInitial();

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

            $codes = $this->styles[$key] ?? null;
            if ($codes === null) {
                continue;
            }

            $this->mask .= "\e[{$codes}m";
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

            $codes = $this->styles[$key] ?? null;
            if ($codes === null) {
                continue;
            }

            $this->mask .= "\e[{$codes}m";
            $this->mask .= "%-'{$this->paddingCharacter}{$size}s";
            $this->mask .= "\e[0m";
            $this->mask .= $count === $i ? "" : " {$this->separator} ";
        }
    }

    /**
     * @return void
     */
    private function textsInitial(): void
    {
        if ($this->textsInitial === null) {
            return;
        }

        $this->texts = array_merge($this->textsInitial, $this->texts);
        $this->styles = array_merge($this->stylesInitial, $this->styles);
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