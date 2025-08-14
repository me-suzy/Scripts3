<?php

/** 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the LGPL License.
 *
 * Copyright(c) 2005 by Santiago Lizardo Oscares. All rights reserved.
 *
 * The latest version of Molins can be obtained from <http://www.phpize.com>.
 *
 * @author slizardo <santiago.lizardo@gmail.com>
 * @version $Id: GenericPortlet.class.php,v 1.4 2005/10/19 15:25:32 slizardo Exp $
 * @package poop.portlet
 */

import('poop.portlet.Porlet');
import('poop.portlet.PorletConfig');

abstract class GenericPortlet implements Portlet, PortletConfig {

	private $config;
	
	/**
	 * Called by the portlet container to indicate to a portlet that the 
	 * portlet is being placed into service.
	 *
	 * @throws PortletException
	 */
	public function init(PortletConfig $config) {
		$this->config = $config;
	}
	
	/**
	 * Called by the portlet container to allow the portlet to process
	 * an action request.
	 *
	 * @throws PortletException
	 * @throws IOException
	 */
	public function processAction(ActionRequest $request, ActionResponse $response) {
		throw new PortletException('processAction method not implemented');
	}

	/**
	 * Called by the portlet container to allow the portlet to generate
	 * the content of the response based on its current state.
	 *
	 * @throws PortletException
	 * @throws IOException
	 */
	public function render(RenderRequest $request, RenderResponse $response);

	/**
	 * Called by the portlet container to indicate to a portlet that the
	 * portlet is being taken out of service.  
	 */
	public function destroy();	
}
	
?>
