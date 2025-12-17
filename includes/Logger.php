<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/Config.php';

use Monolog\Logger as MonologLogger;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Processor\WebProcessor;
use Monolog\Processor\IntrospectionProcessor;
use Monolog\Level;

/**
 * Logger Wrapper Class
 *
 * Singleton wrapper around Monolog for consistent structured logging throughout the application
 */
class Logger {

    /**
     * Singleton Monolog instance
     *
     * @var MonologLogger|null
     */
    private static ?MonologLogger $instance = null;

    /**
     * Log file path (directory)
     *
     * @var string
     */
    private static string $logPath = '/var/log/viewfinder/';

    /**
     * Minimum log level
     *
     * @var string
     */
    private static string $logLevel = 'INFO';

    /**
     * Whether logger has been configured
     *
     * @var bool
     */
    private static bool $configured = false;

    /**
     * Get or create Monolog instance
     *
     * @return MonologLogger
     */
    public static function getInstance(): MonologLogger {
        if (self::$instance === null) {
            self::createLogger();
        }

        return self::$instance;
    }

    /**
     * Configure logger settings
     *
     * @param string $logPath Directory path for log files
     * @param string $logLevel Minimum log level (DEBUG, INFO, WARNING, ERROR, CRITICAL)
     * @return void
     */
    public static function configure(string $logPath, string $logLevel = 'INFO'): void {
        self::$logPath = rtrim($logPath, '/') . '/';
        self::$logLevel = strtoupper($logLevel);
        self::$configured = true;

        // Reset instance to force recreation with new settings
        self::$instance = null;
    }

    /**
     * Log INFO level message
     *
     * @param string $message Log message
     * @param array $context Additional context data
     * @return void
     */
    public static function info(string $message, array $context = []): void {
        self::getInstance()->info($message, $context);
    }

    /**
     * Log WARNING level message
     *
     * @param string $message Log message
     * @param array $context Additional context data
     * @return void
     */
    public static function warning(string $message, array $context = []): void {
        self::getInstance()->warning($message, $context);
    }

    /**
     * Log ERROR level message
     *
     * @param string $message Log message
     * @param array $context Additional context data
     * @return void
     */
    public static function error(string $message, array $context = []): void {
        self::getInstance()->error($message, $context);
    }

    /**
     * Log DEBUG level message
     *
     * @param string $message Log message
     * @param array $context Additional context data
     * @return void
     */
    public static function debug(string $message, array $context = []): void {
        self::getInstance()->debug($message, $context);
    }

    /**
     * Log CRITICAL level message
     *
     * @param string $message Log message
     * @param array $context Additional context data
     * @return void
     */
    public static function critical(string $message, array $context = []): void {
        self::getInstance()->critical($message, $context);
    }

    /**
     * Log exception with full context
     *
     * @param \Throwable $exception Exception to log
     * @return void
     */
    public static function logException(\Throwable $exception): void {
        $context = [
            'exception_class' => get_class($exception),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString()
        ];

        // Add ViewfinderException-specific context if available
        if ($exception instanceof ViewfinderException) {
            $context['error_code'] = $exception->getErrorCode();
            $context['user_message'] = $exception->getUserMessage();
            $context = array_merge($context, $exception->getContext());
        }

        self::getInstance()->error($exception->getMessage(), $context);
    }

    /**
     * Create and configure Monolog logger instance
     *
     * @return void
     */
    private static function createLogger(): void {
        // Use Config values if configured, otherwise use defaults
        if (!self::$configured) {
            self::$logPath = defined('Config::LOG_PATH') ? Config::LOG_PATH : self::$logPath;
            self::$logLevel = defined('Config::LOG_LEVEL') ? Config::LOG_LEVEL : self::$logLevel;
        }

        // Convert string level to Monolog Level enum
        $level = match (self::$logLevel) {
            'DEBUG' => Level::Debug,
            'INFO' => Level::Info,
            'WARNING' => Level::Warning,
            'ERROR' => Level::Error,
            'CRITICAL' => Level::Critical,
            default => Level::Info
        };

        // Create logger instance
        self::$instance = new MonologLogger('viewfinder');

        // Ensure log directory exists
        if (!is_dir(self::$logPath)) {
            @mkdir(self::$logPath, 0755, true);
        }

        // Add rotating file handler (daily rotation, 30-day retention)
        $fileHandler = new RotatingFileHandler(
            self::$logPath . 'viewfinder.log',
            30, // Keep 30 days of logs
            $level
        );
        self::$instance->pushHandler($fileHandler);

        // Add stderr handler for ERROR and above (useful for Docker logs)
        $stderrHandler = new StreamHandler('php://stderr', Level::Error);
        self::$instance->pushHandler($stderrHandler);

        // Add processors for additional context
        self::$instance->pushProcessor(new WebProcessor());

        // Add introspection processor only for DEBUG level
        if ($level === Level::Debug) {
            self::$instance->pushProcessor(new IntrospectionProcessor());
        }
    }
}
