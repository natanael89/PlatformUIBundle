<?php

/**
 * File containing the DashboardController class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\PlatformUIBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use EzSystems\PlatformUIBundle\Components\Component;
use EzSystems\PlatformUIBundle\Components\NavigationHub;
use eZ\Publish\API\Repository\Values\Content\Location;

class ProtoController extends Controller
{
    protected $toolbars;

    protected $navigationHub;

    public function __construct(NavigationHub $navigationHub, array $toolbars)
    {
        $this->navigationHub = $navigationHub;
        $this->toolbars = $toolbars;
    }

    protected function setToolbarsVisibility($config)
    {
        foreach ($this->toolbars as $toolbar) {
            $toolbar->setVisible((bool)$config[$toolbar->getId()]);
        }
    }

    public function dashboardAction(Request $request)
    {
        $this->setToolbarsVisibility($request->attributes->get('toolbars'));
        $parameters = [
            'title' => 'Dashboard',
            'navigationHub' => $this->navigationHub,
            'toolbars' => $this->toolbars,
            'content' => $this->renderView(
                'eZPlatformUIBundle:PlatformUI:dashboard.html.twig'
            ),
        ];
        if ($request->headers->has('x-ajax-update')) {
            return JsonResponse::create($parameters);
        }

        return $this->render('eZPlatformUIBundle:PlatformUI:proto.html.twig', $parameters);
    }

    public function locationViewAction(Request $request, Location $location)
    {
        $this->setToolbarsVisibility($request->attributes->get('toolbars'));
        $parameters = [
            'title' => $location->contentInfo->name,
            'navigationHub' => $this->navigationHub,
            'toolbars' => $this->toolbars,
            'content' => $this->renderView(
                'eZPlatformUIBundle:PlatformUI:locationview.html.twig',
                ['location' => $location]
            ),
        ];
        if ($request->headers->has('x-ajax-update')) {
            return JsonResponse::create($parameters);
        }

        return $this->render('eZPlatformUIBundle:PlatformUI:proto.html.twig', $parameters);
    }
}