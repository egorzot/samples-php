<?php

/**
 * This file is part of Temporal package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Temporal\Samples\CancelActivity;

use Carbon\CarbonInterval;
use Temporal\Activity\ActivityOptions;
use Temporal\Common\RetryOptions;
use Temporal\Exception\IllegalStateException;
use Temporal\Workflow;

/**
 * Demonstrates activity retries using an exponential backoff algorithm. Requires a local instance
 * of the Temporal service to be running.
 */
class CancelWorkflow implements CancelWorkflowInterface
{
    private $cancelActivity;

    public function __construct()
    {
        $this->cancelActivity = Workflow::newActivityStub(
            CancelActivityInterface::class,
            ActivityOptions::new()
                ->withScheduleToCloseTimeout(CarbonInterval::seconds(20))
        );
    }

    public function cancelActivity(string $workflowId)
    {
        return yield $this->cancelActivity->cancel($workflowId);
    }
}