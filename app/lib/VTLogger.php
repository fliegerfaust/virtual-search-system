<?php

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;

/**
 * Данный класс нужен для перекрытия метода записи очередной строки в лог - addRecord.
 * Сделано это для того, чтобы можно было вести отдельный журнал, предназначенный для 
 * логгирования специальный событий, типа ошибок валидации данных, ошибок авторизации.
 * Например, при типе ошибки "валидации данных" передаётся идентификатор журанала '2'.
 * Массив идентификаторов журналов можно дополнять по мере необходимости. Остальные
 * же события, типа FatalError, Exception и т.д. пишутся встроенным логгером Laravel.
 */
class VTLogger extends Monolog\Logger 
{

    protected static $logIds = array(
        1 => 'Audit log',
        2 => 'Validation log',
        //etc
    );

    protected $path;
    protected $formatter;
    protected $stream;

	 public function __construct($name, array $handlers = array(), array $processors = array())
    {
        $this->name = $name;
        $this->handlers = $handlers;
        $this->processors = $processors;
    }

	/**
     * Adds a log record(overriding Monolog method special for VSS).
     *
     * @param  integer $level   The logging level
     * @param  string  $message The log message
     * @param  array   $context The log context
     * @param  array   $lodId   The log ID - идентификатор журнала
     * @return Boolean Whether the record has been processed
     */
    public function addRecord($level, $message, array $context = array(), $logId = null)
    {
        if (!$this->handlers) {
            $this->pushHandler(new StreamHandler('php://stderr', static::DEBUG));
        }

        if (!static::$timezone) {
            static::$timezone = new \DateTimeZone(date_default_timezone_get() ?: 'Europe/Moscow');
        }

        $record = array(
            'message' => (string) $message,
            'context' => $context,
            'level' => $level,
            'level_name' => static::getLevelName($level),
            'channel' => $this->name,
            'datetime' => \DateTime::createFromFormat('U.u', sprintf('%.6F', microtime(true)), static::$timezone)->setTimezone(static::$timezone),
            'extra' => array(),
            'logId' => $logId,
            'logName' => static::getLogIdName($logId),
        );
        // check if any handler will handle this message
        $handlerKey = null;
        foreach ($this->handlers as $key => $handler) {
            if ($handler->isHandling($record)) {
                $handlerKey = $key;
                break;
            }
        }
        // none found
        if (null === $handlerKey) {
            return false;
        }

        // found at least one, process message and dispatch it
        foreach ($this->processors as $processor) {
            $record = call_user_func($processor, $record);
        }
        while (isset($this->handlers[$handlerKey]) &&
            false === $this->handlers[$handlerKey]->handle($record)) {
            $handlerKey++;
        }

        return true;
    }

   public static function getLogIdName($logId)
    {
        if (!isset(static::$logIds[$logId])) {
            throw new InvalidArgumentException('Level "'.$logId.'" is not defined, use one of: '.implode(', ', array_keys(static::$logIds)));
        }

        return static::$logIds[$logId];
    }
}