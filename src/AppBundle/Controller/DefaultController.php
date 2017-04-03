<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Device;
use AppBundle\Model\DeviceState;
use AppBundle\Model\StateFactory;
use AppBundle\Model\Time;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class DefaultController extends Controller
{
    public function getAction(string $device) : Response
    {
        $device = $this->getDoctrine()
            ->getRepository('AppBundle:Device')
            ->find($device);

        $serializer = $this->get('jms_serializer');

        if ($device === null) {
            throw new HttpException(404);
        }
        $this->maintainState($device);
        return new Response(
            $serializer->serialize($device, 'json'),
            200
        );
    }

    public function patchAction(string $device, Request $request) : Response
    {
        $serializer = $this->get('jms_serializer');
        /** @var Device $patch */
        $patch = $serializer->deserialize($request->getContent(), Device::class,'json');
        $entityManager = $this->getDoctrine()->getManager();
        $ownDevice = $entityManager
            ->getRepository('AppBundle:Device')
            ->find($device);

        if ($ownDevice === null) {
            throw new HttpException(404);
        }

        $this->maintainState($ownDevice);

        if ($patch->isDoorOpen() !== null) {
            if ($patch->isDoorOpen() === $ownDevice->isDoorOpen()) {
                throw new HttpException(400);
            }
            if ($ownDevice->getState() === DeviceState::STATE_RUNNING) {
                throw new HttpException(400);
            }
            $ownDevice->setDoorOpen($patch->isDoorOpen());
            $entityManager->merge($ownDevice);
        }

        if ($patch->getState() !== null && StateFactory::validState($patch)) {
            $ownDeviceState = StateFactory::createState($ownDevice);
            $ownDeviceState->{$patch->getState()}();
            $entityManager->merge($ownDevice);
        }

        $entityManager->flush();

        return new Response(
            $serializer->serialize($ownDevice, 'json'),
            200
        );
    }

    private function maintainState(Device $device)
    {
        if ($device->getState() === DeviceState::STATE_RUNNING
            && $device->getFinishesAt() - (new Time())->time() < 0
        ) {
            $device->setFinishesAt(0);
            $device->setState(DeviceState::STATE_READY);
            $this->getDoctrine()->getManager()->merge($device);
            $this->getDoctrine()->getManager()->flush();
        }
    }
}
