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
 * @version $Id: SizeLimitExceededException.class.php,v 1.3 2005/10/14 09:52:51 slizardo Exp $
 * @package poop.commons.fileupload
 */

import('poop.commons.fileupload.FileUploadException');

/**
 * Thrown to indicate that the request size exceeds the configured maximum.
 */
class SizeLimitExceededException extends FileUploadException {}

?>
