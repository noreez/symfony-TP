<?php

namespace App\Subscriber;

use App\Event\VideoRegisterEvent;
use App\Event\VideoRemoveEvent;
use App\Event\VideoUpdateEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class VideoSubscriber implements EventSubscriberInterface
{
    private $logger;
    private $date;
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->date = new \DateTime();
    }

    public static function getSubscribedEvents()
    {
        return [
            VideoRegisterEvent::NAME => 'VideoRegisterEvent',
            VideoUpdateEvent::NAME => 'VideoUpdateEvent',
            VideoRemoveEvent::NAME => 'VideoRemoveEvent',
        ];
    }

    public function VideoRegisterEvent(VideoRegisterEvent $videoRegisterEvent)
    {
        $video = $videoRegisterEvent->getUser();

        $this->logger->info('Video added successflly at '. $this->date->format('Y-m-d H:i:s'));
        $this->logger->info('Video created by '. $video->getUser()->getEmail());
        $this->logger->info('Video title : '. $video->getTitle());
        $this->logger->info('Video id : '. $video->getId());
    }

    public function VideoUpdateEvent(VideoUpdateEvent $VideoUpdateEvent)
    {
        $video = $VideoUpdateEvent->getUser();

        $this->logger->info('Video update successflly at '. $this->date->format('Y-m-d H:i:s'));
        $this->logger->info('Video update by '. $video->getUser()->getEmail());
        $this->logger->info('Video update title : '. $video->getTitle());
        $this->logger->info('Video update id : '. $video->getId());
    }

    public function VideoRemoveEvent(VideoRemoveEvent $VideoRemoveEvent)
    {
        $video = $VideoRemoveEvent->getUser();

        $this->logger->info('Video remove successflly at '. $this->date->format('Y-m-d H:i:s'));
        $this->logger->info('Video remove by '. $video->getUser()->getEmail());
        $this->logger->info('Video remove title : '. $video->getTitle());
        $this->logger->info('Video remove id : '. $video->getId());
    }
}