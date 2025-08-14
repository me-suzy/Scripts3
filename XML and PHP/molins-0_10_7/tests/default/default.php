<?php

$tests = array(
	'poop.lang.tests.StringBufferTest',
	'poop.lang.tests.PrimitivesTest',
	'poop.lang.reflect.tests.ClassUtilTest',
	'poop.util.tests.CollectionsTest',
	'poop.sql.tests.DatabaseTest',
	'poop.graphics.tests.GraphicsTest',
	'poop.io.tests.FileTest',
	'poop.util.tests.I18NTest',
	'poop.util.tests.StringTokenizerTest',	
	'poop.util.tests.CalendarTest'		
);

sort($tests);

$tests_2d = array(
	'poop.graphics.tests.ColorTest',
	'poop.graphics.tests.EllipseTest',
	'poop.graphics.tests.ArcTest',
	'poop.graphics.tests.LineTest',
	'poop.graphics.tests.PointTest',
	'poop.graphics.tests.RectangleTest'
);

sort($tests_2d);

$template->assign('title', 'Tests for Molins');
$template->assign('styleSheets', array('/styleSheets/empty.css'));
$template->assign('tests', $tests);
$template->assign('tests_2d', $tests_2d);

?>
