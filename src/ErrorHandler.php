<?php
namespace Freimvork;

class ErrorHandler {
    protected $error;
    protected $trace;

    public function __construct($error) {
        $this->error = $error;
    }

    public function printError()
    {
        echo "
            <br/>
            <b> Error: {$this->error->getMessage()} </b> in {$this->error->getFile()}:{$this->error->getLine()}
            <br/>
        ";

        return $this;
    }

    public function printTrace()
    {
        $errorLog = '
            <br/> Stack Trace: <br/>
        ';
        $traces = $this->error->getTrace();
        foreach ($traces as $index => $trace) {
            $errorLog .= self::printTraceItem($index,$trace);
        }

        echo $errorLog;
    }

    protected static function printTraceItem($index, $item)
    {
        $agruments = self::recursiveImplode(', ', $item["args"]);
        if (!\array_key_exists('file', $item)) {
            return "#{$index} closure()<br/>";
        }
        return "
            #{$index} {$item["file"]}({$item["line"]}):
            <b> {$item["class"]}{$item["type"]}{$item["function"]}({$agruments}) </b>
            <br/>
        ";
    }

    private static function recursiveImplode(string $glue = '', array $array = []) 
    {
        return \implode($glue, \array_map(function($item) use ($glue){
            switch (true) {
                case \is_array($item): return "[" . self::recursiveImplode($glue, $item) ."]";
                case \is_object($item): return \get_class($item);
                default: return $item;
            }
        }, $array));
    }
}