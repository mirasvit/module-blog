<?php

namespace Mirasvit\Blog\Controller;

use Magento\Framework\App\ActionFactory;
use Magento\Framework\App\Request\Http;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\RouterInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Event\ManagerInterface as EventManagerInterface;
use Mirasvit\Blog\Model\Url;

//use Magento\Framework\Url;

class Router implements RouterInterface
{
    /**
     * @var ActionFactory
     */
    private $actionFactory;

    /**
     * @var EventManagerInterface
     */
    private $eventManager;

    /**
     * @var Url
     */
    private $url;

    public function __construct(
        Url $url,
        ActionFactory $actionFactory,
        EventManagerInterface $eventManager
    ) {
        $this->url           = $url;
        $this->actionFactory = $actionFactory;
        $this->eventManager  = $eventManager;
    }

    /**
     * {@inheritdoc}
     */
    public function match(RequestInterface $request)
    {
        /** @var Http $request */

        $identifier = trim($request->getPathInfo(), '/');
        $this->eventManager->dispatch('core_controller_router_match_before', [
            'router'    => $this,
            'condition' => new DataObject(['identifier' => $identifier, 'continue' => true]),
        ]);

        $pathInfo = $request->getPathInfo();

        $result = $this->url->match($pathInfo);

        if ($result) {
            $params = $result->getParams();

            $request
                ->setModuleName($result->getModuleName())
                ->setControllerName($result->getControllerName())
                ->setActionName($result->getActionName())
                ->setParams($params);

            return $this->actionFactory->create(
                'Magento\Framework\App\Action\Forward',
                ['request' => $request]
            );
        }

        return false;
    }
}
