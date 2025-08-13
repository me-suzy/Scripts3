var MENU_POS_XP =[
// Level 0 block configuration
{
	// Item's height in pixels
	'height'     : 25,
	// Item's width in pixels
	'width'      : 60,
	// if Block Orientation is vertical
	'vertical'   : false,
	// Time Delay in milliseconds before subling block expands
	// after mouse pointer overs an item
	'expd_delay' : 150,
	// Style class names for the level
	'css': {
		// Block outing table class
		'table' : '',
		// Item outer tag style class for all item states or
		// classes for [<default state>, <hovered state>, <clicked state>]
		'outer' : ['mXPmouto','',''],
		// Item inner tag style class for all item states or
		// classes for [<default state>, <hovered state>, <clicked state>]
		'inner' : ''
	}
},
// Level 1 block configuration
{
	'width'      : 160,
	'height'     : 24,
	// Vertical Offset between adjacent levels in pixels
	'block_top'  : 22,
	// Horizontal Offset between adjacent levels in pixels
	'block_left' : 0,
	// block behaviour if single frame:
	// 1 - shift to the edge, 2 - flip relatively to left upper corner
	'wise_pos'   : 2,
	'vertical'   : true,
	// transition effects for the block
	// [index on expand, duration on expand, index on collapse, duration on collapse]
	'transition' : [0, 0.3, 0, 0.3],
	// Time Delay in milliseconds before menu collapses after mouse
	// pointer lefts all items
	'hide_delay' : 150,
	'css' : {
		'table' : 'mXPtable',
		'outer' : '',
		'inner' : ''
	}
},
// Level 2 block configuration
{
	'block_top'  : 0,
	'block_left' : 160
}
	//Subling level configurations are inherited from level 2
]

