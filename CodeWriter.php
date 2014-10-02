<?php
namespace Plum\Gen;

class CodeWriter
{
    /**
     * The tab characters
     *
     * @var string
     */
    private $tab;

    /**
     * The buffered generated code
     *
     * @var string
     */
    private $buff;

    /**
     * The indentation level
     *
     * @var int
     */
    private $indentation = 0;

    public function __construct($tab = "    ")
    {
        $this->tab = $tab;
    }

    /**
     * Returns the buffered code
     *
     * @return string
     */
    public function bufferedCode()
    {
        return $this->buff;
    }

    /**
     * Writes given code(s) to the buffer
     *
     * @param string $code
     * @param string,... $codes
     */
    public function write($code, ...$codes)
    {
        $this->buff .= $code;
        $this->buff .= implode($codes);
    }

    /**
     * Writes PHP_EOL to the buffer
     */
    public function nl()
    {
        $this->write(
            PHP_EOL, str_repeat($this->tab, $this->indentation));
    }

    /**
     * Writes given code(s) followed by PHP_EOL to the buffer
     *
     * @param string $code
     * @param string,... $codes
     */
    public function writeln($code, ...$codes)
    {
        array_unshift($codes, $code);
        $this->write(...$codes);
        $this->nl();
    }

    /**
     * @param mixed $value
     *
     * @throws \InvalidArgumentException
     */
    public function literal($value)
    {
        if (is_string($value)) {
            $this->write('"', $value, '"');
        } else if (is_numeric($value)) {
            $this->write($value);
        } else if (is_array($value)) {
            $this->write("[");
            foreach ($value as $k => $v) {
                $this->literal($k);
                $this->write(" => ");
                $this->literal($v);
                $this->write(",");
            }
            $this->write("]");
        } else if ($value === null) {
            $this->write("null");
        } else if (is_bool($value)) {
            $this->write($value ? "true" : "false");
        } else if ($value instanceof \stdClass) {
            $this->write("(object)");
            $this->literal((array)$value);
        } else throw new \InvalidArgumentException(
            "Only scalar, array or \\stdClass instance can be written, ".
            gettype($value)." given"
        );
    }

    /**
     * Increase the indentation level
     *
     * @param int $step
     *
     * @throws \UnexpectedValueException
     */
    public function indent($step = 1)
    {
        if ($step < 1) throw new \UnexpectedValueException(
            "Indentation step must be a positive number, {$step} given"
        );

        $this->indentation += $step;
        $this->nl();
    }

    /**
     * Decrease the indentation level
     *
     * @param int $step
     *
     * @throws \UnexpectedValueException
     */
    public function outdent($step = 1)
    {
        if ($step < 1) throw new \UnexpectedValueException(
            "Indentation step must be a positive number, {$step} given"
        );

        if ($this->indentation - $step < 0) throw new \UnexpectedValueException(
            "Decreasing by {$step} step will result in negative indentation"
        );

        $this->indentation -= $step;
        $this->nl();
    }
} 
