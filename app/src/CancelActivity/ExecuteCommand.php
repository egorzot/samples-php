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
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Temporal\Client\WorkflowOptions;
use Temporal\Common\IdReusePolicy;
use Temporal\SampleUtils\Command;

class ExecuteCommand extends Command
{
    protected const NAME = 'cancel-activity';
    protected const DESCRIPTION = 'Execute ActivityCancel\CancelWorkflow';

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $workflowId = "wf-".random_int(1,1000);

        $workflow = $this->workflowClient->newWorkflowStub(
            CancelWorkflowInterface::class,
            WorkflowOptions::new()
                ->withWorkflowExecutionTimeout(CarbonInterval::minute())
                ->withWorkflowId($workflowId)
                ->withWorkflowIdReusePolicy(IdReusePolicy::POLICY_ALLOW_DUPLICATE)

        );

        $output->writeln("Starting <comment>CancelWorkflow</comment>... ");

        $run = $this->workflowClient->start($workflow, $workflowId);

        $output->writeln(
            sprintf(
                'Started: WorkflowID=<fg=magenta>%s</fg=magenta>, RunID=<fg=magenta>%s</fg=magenta>',
                $run->getExecution()->getID(),
                $run->getExecution()->getRunID(),
            )
        );

        return self::SUCCESS;
    }
}