<?php
namespace App\Services\Wifi\Exceptions;

use RuntimeException;

/**
 * Class CommandException.
 */
class CommandException extends RuntimeException
{
    /** @var string */
    protected $command;

    /** @var string */
    protected $output;

    /**
     * CommandException constructor.
     *
     * @param string $command
     * @param string $output
     * @param int    $returnCode
     */
    public function __construct(string $command, string $output, int $returnCode)
    {
        if ($returnCode == 127) {
            $message = 'Command not found: "'.$command.'"';
        } else {
            $message = 'Command "'.$command.'" exited with code '.$returnCode.': '.$output;
        }

        parent::__construct($message);
    }
}
