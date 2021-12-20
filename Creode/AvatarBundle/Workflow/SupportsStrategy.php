<?php

/**
 * Pimcore
 *
 * This source file is available under two different licenses:
 * - GNU General Public License version 3 (GPL)
 * - Pimcore Enterprise License (PEL)
 * Full copyright and license information is available in
 * LICENSE.md which is distributed with this source code.
 *
 *  @copyright  Copyright (c) Pimcore GmbH (http://www.pimcore.org)
 *  @license    http://www.pimcore.org/license     GPLand PEL
 */

namespace Creode\AvatarBundle\Workflow;

use Pimcore\Model\DataObject\Avatar;
use Symfony\Component\Workflow\SupportStrategy\WorkflowSupportStrategyInterface;
use Symfony\Component\Workflow\WorkflowInterface;

class SupportsStrategy implements WorkflowSupportStrategyInterface
{
    public function supports(WorkflowInterface $workflow, $subject): bool
    {
        if ($workflow->getName() == 'avatar_data_enrichment') {
            if ($subject instanceof Avatar/* && strpos($subject->getFullPath(), '/upload/new') === 0*/) {
                return true;
            }
        }

        return false;
    }
}
