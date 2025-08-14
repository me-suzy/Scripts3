<?php

/** 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the LGPL License.
 *
 * Copyright(c) 2005 by Santiago Lizardo Oscares. All rights reserved.
 *
 * To contact the author write to {@link mailto:santiago.lizardo@gmail.com slizardo}
 * The latest version of Molins can be obtained from:
 * {@link http://www.phpize.com/}
 *
 * @author slizardo <santiago.lizardo@gmail.com>
 * @version $Id: CategoriesDispatchAction.class.php,v 1.2 2005/10/13 13:42:59 slizardo Exp $
 * @package sample
 */

import('sample.Category');

class CategoriesDispatchAction extends DispatchAction {

	public function add(ActionMapping $mapping, ActionForm $form, HttpRequest $request, HttpResponse $response) {
		
		$name = $request->getParameter('name');
		$description = $request->getParameter('description');

		try {
			$category = new Category();
			$category->setName($name);
			$category->setDescription($description);
			$category->insert();
			
		} catch (Exception $e) {
	
			return $mapping->findForward('exception');
		}

		return $mapping->findForward('success');
	}

	public function remove(ActionMapping $mapping, ActionForm $form, HttpRequest $request, HttpResponse $response) {

		$id = $request->getParameter('id');

		try {
			$category = new Category($id);
			$category->delete();
			
		} catch (Exception $e) {
	
			return $mapping->findForward('exception');
		}

		return $mapping->findForward('success');
	}
}
	
?>
