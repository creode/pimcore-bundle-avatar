<?php

namespace Creode\AvatarBundle\EventListener;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Event\GuardEvent;
use Symfony\Component\Workflow\TransitionBlocker;

class AvatarEventListener implements EventSubscriberInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    public function guard2DImageSubmit(GuardEvent $event)
    {

        // just returning stops this transition appearing in the dropdown list.
        // @TODO: Figure out how to put debugging messages in here
        // @TODO: Document how these guards work
        return;

        $this->logger->debug('woohoo {name}', ['name' => 'Geoff']);

        $eventTransition = $event->getTransition();
        $hourLimit = 20;

        if (date('H') <= $hourLimit) {
            return;
        }

        // Block the transition "publish" if it is more than 8 PM
        // with the message for end user
        $event->addTransitionBlocker(new TransitionBlocker("Nah, don't think so!" , '0'));
    }

    /** @noinspection PhpArrayShapeAttributeCanBeAddedInspection */
    public static function getSubscribedEvents(): array
    {
        return [
            'workflow.avatar_data_enrichment.guard.request_3d_asset' => ['guard2DImageSubmit'],
        ];
    }
}
