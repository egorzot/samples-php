<?php

/**
 * This file is part of Temporal package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Temporal\Samples\CancelActivity;

use Temporal\Client\GRPC\ServiceClient;
use Temporal\Client\WorkflowClient;
use Temporal\SampleUtils\Logger;
use Psr\Log\LoggerInterface;

class CancelActivity implements CancelActivityInterface
{
    private LoggerInterface $logger;

    public function __construct()
    {
        $this->logger = new Logger();
    }

    public function cancel(string $workflowId): void
    {
        $this->log(
            "Starting cancel activity.",
        );

        $host = getenv('TEMPORAL_ADDRESS') ?: getenv('TEMPORAL_CLI_ADDRESS');
        if (empty($host)) {
            $host = 'localhost:7233';
        }

        $workflowClient = WorkflowClient::create(ServiceClient::create($host));

         $this->log(
             "created new workflowClient, get workflowID:".$workflowId,
         );
        $workflow = $workflowClient->newUntypedRunningWorkflowStub($workflowId);
        $this->log(
            "got workflow with ID:".$workflowId,
        );

        $workflow->cancel();

        $this->log(
            "cancelled workflow with ID:".$workflowId
        );
    }

    /**
     * @param string $message
     * @param mixed ...$arg
     */
    private function log(string $message, ...$arg)
    {
        // by default all error logs are forwarded to the application server log and docker log
        $this->logger->debug(sprintf($message, ...$arg));
    }
}