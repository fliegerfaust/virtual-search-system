<?php
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;

/**
 * Класс, предоставляющий отформатированный экземпляр VTLogger (app/lib/VTLogger.php)
 */
class FormattedVTLogger {
	public function makeFormattedVTLogger($name) {
		$logger = new VTLogger($name);
		$dateFormat = "d.m.Y H:i:s";
		$output = "[%datetime%] %logId% > %logName% > %level% > %level_name% > %message% > %context%\n";
		$formatter = new LineFormatter($output, $dateFormat);
		$stream = new StreamHandler(storage_path().'/logs/VTLog.log', Logger::DEBUG);
		$stream->setFormatter($formatter);
		$logger->pushHandler($stream);

		return $logger;
	}
}