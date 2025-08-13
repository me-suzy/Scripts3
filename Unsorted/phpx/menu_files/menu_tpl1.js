var MENU_POS0=[
// Level 0 block configuration
{
	// Item's width in pixels
	'width'      : 110,
	// Item's height in pixels
	'height'     : 18,
	// if Block Orientation is vertical
	'vertical'   : false,
	// Block outing table parameters:
	// [cellpadding, cellspacing, border]
	'table'      : [0, 0, 0],
	// Time Delay in milliseconds before subling block expands
	// after mouse pointer overs an item
	'expd_delay' : 150,
	'css' : {
		'table' : 'm0table',
		'inner' : 'm0inner',
		'outer' : ['m0mouto', 'm0movero', 'm0mdowno']
	}
},
// Level 1 block configuration
{
	'width'      : 150,
	'height'     : 20,
	// Vertical Offset between adjacent levels in pixels
	'block_top'  : 18,
	// Horizontal Offset between adjacent levels in pixels
	'block_left' : 0,
	'vertical'   : true,
	// block behaviour if single frame:
	// 1 - shift to the edge, 2 - flip relatively to left upper corner
	'wise_pos'   : 1,
	'table'      : [0, 1, 0],
	// Time Delay in milliseconds before menu collapses after mouse
	// pointer lefts all items
	'hide_delay' : 150,
	'expd_delay' : 150,
	// transition effects the for the block
	// [index on expand, duration on expand, index on collapse, duration on collapse]
	'css' : {
		'table' : 'm1table',
		'inner' : 'm0inner',
		'outer' : ['m0mouto', 'm1movero', 'm0mdowno']
	},
	'opacity'    : 85
},
{
	'width'      : 150,
	'height'     : 20,
	// Vertical Offset between adjacent levels in pixels
	'block_top'  : 16,
	// Horizontal Offset between adjacent levels in pixels
	'block_left' : 80,
	'vertical'   : true,
	// block behaviour if single frame:
	// 1 - shift to the edge, 2 - flip relatively to left upper corner
	'wise_pos'   : 1,
	'table'      : [0, 1, 0],
	// Time Delay in milliseconds before menu collapses after mouse
	// pointer lefts all items
	'hide_delay' : 150,
	'expd_delay' : 150,
	// transition effects the for the block
	// [index on expand, duration on expand, index on collapse, duration on collapse]
	'css' : {
		'table' : 'm1table',
		'inner' : 'm0inner',
		'outer' : ['m0mouto', 'm1movero', 'm0mdowno']
	},
	'opacity'    : 85
}
// Level 2 block configuration is inherited from level 1
]
