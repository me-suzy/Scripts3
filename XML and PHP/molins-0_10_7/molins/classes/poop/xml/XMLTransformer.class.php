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
 * @version $Id: XMLTransformer.class.php,v 1.5 2005/10/12 21:01:05 slizardo Exp $
 * @package poop.xml
 */
 
class XMLTransformer {
	private static $xslt;
	
	public static function init() {
		self::$xslt = new XSLTProcessor();
		self::$xslt->setParameter(null, 'CONTEXT_PATH', CONTEXT_PATH);
	}

	/**
	 * @param string $xsl_path
	 * @param string $xml_path
	 */
	public static function transform($xsl_path, $xml_path) {
		if(file_exists($xsl_path) == false) {
			throw new FileNotFoundException($xsl_path);
		}
		$xml_content = @file_get_contents($xml_path);
		if($xml_content == false) {
			throw new IOException($xml_path);
		}
		$xsl = new DomDocument();
		if(@$xsl->load($xsl_path) == false) {
			throw new XMLException(_('error cargando xsl'), $xsl_path);
		}

		$xml = new DomDocument();
		$xml->load($xml_path);
	
		self::$xslt->importStylesheet($xsl);

		$result = self::$xslt->transformToXml($xml);

		if($result == false) throw new XSLTException(_('error transformando xml'), $xml_path);

		return $result;
	}
}

XMLTransformer::init();

?>
