<?php

/*
 * This file is part of Phraseanet
 *
 * (c) 2005-2013 Alchemy
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\Phrasea\Controller\User;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class Notifications implements ControllerProviderInterface
{

    /**
     * {@inheritDoc}
     */
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        $controllers->before(function(Request $request) use ($app) {
            $app['firewall']->requireNotGuest();
        });

        /**
         * Read all notifications
         *
         * name         : read_notifications_full
         *
         * description  : Read full notification
         *
         * method       : GET
         *
         * parameters   : none
         *
         * return       : JSON Response
         */
        $controllers->get('/', $this->call('listNotifications'))
            ->bind('get_notifications');

        /**
         * Set notifications as readed
         *
         * name         : set_notifications_readed
         *
         * description  : Set notifications as readed
         *
         * method       : POST
         *
         * parameters   : none
         *
         * return       : JSON Response
         */
        $controllers->post('/read/', $this->call('readNotifications'))
            ->bind('set_notifications_readed');

        return $controllers;
    }

    /**
     * Set notifications as readed
     *
     * @param  Application  $app
     * @param  Request      $request
     * @return JsonResponse
     */
    public function readNotifications(Application $app, Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            $app->abort(400);
        }

        try {
            $app['events-manager']->read(
                explode('_', (string) $request->request->get('notifications')),
                $app['authentication']->getUser()->get_id()
            );

            return $app->json(array('success' => true, 'message' => ''));
        } catch (\Exception $e) {
            return $app->json(array('success' => false, 'message' => $e->getMessage()));
        }
    }

    /**
     * Get all notifications
     *
     * @param  Application  $app
     * @param  Request      $request
     * @return JsonResponse
     */
    public function listNotifications(Application $app, Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            $app->abort(400);
        }

        $page = (int) $request->query->get('page', 0);

        return $app->json($app['events-manager']->get_notifications_as_array(($page < 0 ? 0 : $page)));
    }

    /**
     * Prefix the method to call with the controller class name
     *
     * @param  string $method The method to call
     * @return string
     */
    private function call($method)
    {
        return sprintf('%s::%s', __CLASS__, $method);
    }
}
