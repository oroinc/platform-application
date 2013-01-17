<?php

namespace Acme\Bundle\DemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Process\Process;
use Symfony\Component\HttpFoundation\Response;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use JMS\DiExtraBundle\Annotation as DI;
use JMS\JobQueueBundle\Entity\Job;

/**
 * @Route("/job")
 */
class JobController extends Controller
{
    /** @DI\Inject("%jms_job_queue.statistics%") */
    protected $statisticsEnabled;

    /**
     * @Route("/{page}/{limit}", name="acme_demo_job_index", requirements={"page"="\d+","limit"="\d+"}, defaults={"page"=1,"limit"=20})
     * @Template
     */
    public function indexAction($page, $limit)
    {
        $em     = $this->getDoctrine()->getManagerForClass('JMSJobQueueBundle:Job');
        $failed = $em->getRepository('JMSJobQueueBundle:Job')->findLastJobsWithError(5);
        $query  = $em->createQueryBuilder();

        $query
            ->select('j')
            ->from('JMSJobQueueBundle:Job', 'j')
            ->where($query->expr()->isNull('j.originalJob'))
            ->orderBy('j.id', 'desc');

        foreach ($failed as $i => $job) {
            $query
                ->andWhere($query->expr()->neq('j.id', '?' . $i))
                ->setParameter($i, $job->getId());
        }

        return array(
            'failed' => $failed,
            'pid'    => $this->getDaemonPid(),
            'pager'  => $this->get('knp_paginator')->paginate($query, $page, $limit),
        );
    }

   /**
     * @Route("/show/{id}", name="acme_demo_job_show", requirements={"id"="\d+"})
     * @Template
     */
    public function showAction(\JMS\JobQueueBundle\Entity\Job $job)
    {
        $relatedEntities = array();

        foreach ($job->getRelatedEntities() as $entity) {
            $class = \Doctrine\Common\Util\ClassUtils::getClass($entity);
            $relatedEntities[] = array(
                'class' => $class,
                'id'    => json_encode($this->getDoctrine()->getManagerForClass($class)->getClassMetadata($class)->getIdentifierValues($entity)),
                'raw'   => $entity,
            );
        }

        $statisticData = $statisticOptions = array();

        if ($this->statisticsEnabled) {
            $dataPerCharacteristic = array();
            foreach ($this->getDoctrine()->getManagerForClass('JMSJobQueueBundle:Job')->getConnection()->query("SELECT * FROM jms_job_statistics WHERE job_id = ".$job->getId()) as $row) {
                $dataPerCharacteristic[$row['characteristic']][] = array(
                    $row['createdAt'],
                    $row['charValue'],
                );
            }

            if ($dataPerCharacteristic) {
                $statisticData = array(array_merge(array('Time'), $chars = array_keys($dataPerCharacteristic)));
                $startTime     = strtotime($dataPerCharacteristic[$chars[0]][0][0]);
                $endTime       = strtotime($dataPerCharacteristic[$chars[0]][count($dataPerCharacteristic[$chars[0]])-1][0]);
                $scaleFactor   = $endTime - $startTime > 300 ? 1/60 : 1;

                // this assumes that we have the same number of rows for each characteristic.
                for ($i = 0, $c = count(reset($dataPerCharacteristic)); $i < $c; $i++) {
                    $row = array((strtotime($dataPerCharacteristic[$chars[0]][$i][0]) - $startTime) * $scaleFactor);

                    foreach ($chars as $name) {
                        $value = (float) $dataPerCharacteristic[$name][$i][1];

                        switch ($name) {
                            case 'memory':
                                $value /= 1024 * 1024;

                                break;
                        }

                        $row[] = $value;
                    }

                    $statisticData[] = $row;
                }
            }
        }

        return array(
            'job'                  => $job,
            'pid'                  => $this->getDaemonPid(),
            'relatedEntities'      => $relatedEntities,
            'statisticData'        => $statisticData,
            'statisticOptions'     => $statisticOptions,
            'incomingDependencies' => $this->getDoctrine()
                ->getManagerForClass('JMSJobQueueBundle:Job')
                ->getRepository('JMSJobQueueBundle:Job')
                ->getIncomingDependencies($job),
        );
    }

    /**
     * @Route("/run-daemon", name="acme_demo_job_run_daemon")
     */
    public function runDaemonAction()
    {
        $ret = array('error' => 0);

        if ($this->getDaemonPid()) {
            $ret['error']   = 1;
            $ret['message'] = 'Daemon already started';
        } else {
            /**
             * @todo Move parameters to config
             */
            $process = new Process(sprintf('php %sconsole jms-job-queue:run --max-runtime=999999999 --max-concurrent-jobs=5 >> /dev/null &', $this->get('kernel')->getRootDir() . DIRECTORY_SEPARATOR));

            $process->start();

            if (!$process->isSuccessful()) {
                $ret['error']   = 1;
                $ret['message'] = $process->getErrorOutput();
            } else {
                $ret['message'] = $this->getDaemonPid();
            }
        }

        if ($this->getRequest()->isXmlHttpRequest()) {
            return new Response(json_encode($ret));
        } else {
            if ($ret['error']) {
                $this->get('session')->getFlashBag()->add('error', sprintf('There was an error while starting job queue process: %s', $ret['message']));
            } else {
                $this->get('session')->getFlashBag()->add('success', $ret['message']);
            }

            return $this->redirect($this->generateUrl('acme_demo_job'));
        }
    }

    /**
     * @Route("/stop-daemon", name="acme_demo_job_stop_daemon")
     */
    public function stopDaemonAction()
    {
        $ret = array('error' => 0);
        $pid = $this->getDaemonPid();

        if (!$pid) {
            $ret['error']   = 1;
            $ret['message'] = 'Daemon not started';
        } else {
            $process = new Process(sprintf('kill -9 %u', $pid));

            $process->run();

            if (!$process->isSuccessful()) {
                $ret['error']   = 1;
                $ret['message'] = $process->getErrorOutput();
            } else {
                $ret['message'] = 'Daemon stopped';
            }
        }

        if ($this->getRequest()->isXmlHttpRequest()) {
            return new Response(json_encode($ret));
        } else {
            if ($ret['error']) {
                $this->get('session')->getFlashBag()->add('error', sprintf('There was an error while stopping job queue process: %s', $ret['message']));
            } else {
                $this->get('session')->getFlashBag()->add('success', $ret['message']);
            }

            return $this->redirect($this->generateUrl('acme_demo_job'));
        }
    }

    /**
     * @Route("/add-dummy", name="acme_demo_job_dummy")
     */
    public function dummyJobsAction()
    {
        $em    = $this->getDoctrine()->getEntityManager();
        $tasks = ['acme:lighttask', 'acme:heavytask'];

        for ($i = 0, $cnt = rand(1, 15); $i < $cnt; $i++) {
            // second arg, for example:
            // ['some-args', 'or', '--options="foo"']
            $job = new Job($tasks[array_rand($tasks)]);

            $em->persist($job);
        }

        $em->flush();

        return $this->redirect($this->generateUrl('jobs'));
    }

    /**
     * @Route("/status", name="acme_demo_job_status")
     */
    public function statusAction()
    {
        return $this->getRequest()->isXmlHttpRequest()
            ? new Response($this->getDaemonPid())
            : $this->redirect($this->generateUrl('jobs'));
    }

    /**
     * Check if jobs queue daeumon is running
     *
     * @return int|null Daemon process id on success, null otherwise
     */
    protected function getDaemonPid()
    {
        $process = new Process('ps ax | grep "[j]ms-job-queue:run"');

        $process->run();

        // daemon pid, if running
        $pid = null;

        if (preg_match('#^.+app/console jms-job-queue:run#Usm', $process->getOutput(), $matches)) {
            $pid = (int)$matches[0];
        }

        return $pid;
    }
}