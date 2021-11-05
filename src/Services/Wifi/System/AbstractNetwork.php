<?php
namespace App\Services\Wifi\System;

/**
 * Class AbstractNetwork.
 */
abstract class AbstractNetwork
{
    /** @var string */
    const WPA2_SECURITY = 'WPA2';

    /** @var string */
    const WPA_SECURITY = 'WPA';

    /** @var string */
    const WEP_SECURITY = 'WEP';

    /** @var string */
    const UNKNOWN_SECURITY = 'Unknown';

    /** @var string */
    public $bssid;

    /** @var string */
    public $ssid;

    /** @var int */
    public $channel;

    /** @var float */
    public $quality;

    /** @var float */
    public $dbm;

    /** @var string */
    public $security;

    /** @var string */
    public $securityFlags;

    /** @var int */
    public $frequency;

    /** @var bool */
    public $connected;

    /** @var array */
    protected static $securityTypes = [
        self::WPA2_SECURITY,
        self::WPA_SECURITY,
        self::WEP_SECURITY,
    ];

    /**
     * @var Command
     */
    protected $command;

    /**
     * AbstractNetwork constructor.
     *
     * @param Command $command
     */
    public function __construct(Command $command)
    {
        $this->command = $command;
    }

    /**
     * @return string
     */
    public function getSecurityType(): string
    {
        $securityType = self::UNKNOWN_SECURITY;

        foreach (self::$securityTypes as $securityType) {
            if (strpos($this->security, $securityType) !== false) {
                break;
            }
        }

        return $securityType;
    }

    /**
     * @return Command
     */
    public function getCommand(): Command
    {
        return $this->command;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return implode('|', [
            $this->bssid,
            $this->ssid,
            $this->quality,
            $this->dbm,
            $this->security,
            $this->securityFlags,
            $this->frequency,
            var_export($this->connected, true),
        ]);
    }

    /**
     * @param string $password
     * @param string $device
     */
    abstract public function connect(string $password, string $device): void;

    /**
     * @param string $device
     */
    abstract public function disconnect(string $device): void;

    /**
     * @param array $network
     *
     * @return self
     */
    abstract public function createFromArray(array $network): self;
}
