<?php

namespace supercrafter333\WaterdogPEFixer;

use pocketmine\event\Listener;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\LoginPacket;
use pocketmine\network\mcpe\RakLibInterface;
use pocketmine\plugin\PluginBase;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;

class WaterdogPEFixer extends PluginBase implements Listener {

    public function onEnable() : void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    #################################
    ##[Fix Waterdog(PE) IP & XUID ]##
    #################################

    public function onPacketReceive(DataPacketReceiveEvent $event) : void {
        $packet = $event->getPacket();
        if($packet instanceof LoginPacket) {
            foreach ( $this->getServer()->getNetwork()->getInterfaces() as $interface ) {
                if ( $interface instanceof RakLibInterface ) {
                    try {
                        $reflector = new ReflectionProperty( $interface, "interface" );
                        $reflector->setAccessible( true );
                        $reflector->getValue( $interface )->sendOption( "packetLimit", 900000000000 );
                    } catch ( ReflectionException $e ) {}
                }
            }
            if(isset($packet->clientDataJwt["Waterdog_IP"])) {
                $class = new ReflectionClass($event->getPlayer());

                $prop = $class->getProperty("ip");
                $prop->setAccessible(true);
                $prop->setValue($event->getPlayer(), $packet->clientDataJwt["Waterdog_IP"]);
            }
            if (isset($packet->clientDataJwt["Waterdog_XUID"])) {
                $class = new ReflectionClass($event->getPlayer());

                $prop = $class->getProperty("xuid");
                $prop->setAccessible(true);
                $prop->setValue($event->getPlayer(), $packet->clientDataJwt["Waterdog_XUID"]);
                $packet->xuid = $packet->clientDataJwt["Waterdog_XUID"];
            }
        }
    }
    #################################
    #################################
    #################################
}