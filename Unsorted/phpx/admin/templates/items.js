var MENU_ITEMS_XP = [
	[wrap_root('Home'), 'index.php', {'sw' : 110}],
	[wrap_root('Core Modules'), null, {'sw' : 110},
            [wrap_parent('Web Pages'), 'page.php', null,
                [wrap_child('Create Web Page'), 'page.php?action=create'],
                [wrap_child('Import Themes'), 'theme.php?action=import'],
                [wrap_child('View Themes'), 'theme.php'],
                [wrap_child('Page Configuration'), 'config.php?action=page']
            ],
            [wrap_parent('News'), 'news.php', null,
                [wrap_child('Create News Item'), 'news.php?action=create'],
                [wrap_child('Create News Category'), 'news.php?action=cat&subaction=create'],
                [wrap_child('News Submissions'), 'news.php?action=sub'],
                [wrap_child('News Category Admin'), 'news.php?action=cat'],
                [wrap_child('News Configuration'), 'config.php?action=news']
            ],
            [wrap_parent('Text Insertion'), 'page.php?action=insert',null,
                [wrap_child('Create Text For Insertion'), 'page.php?action=insert&subaction=add']
            ],
            [wrap_parent('Users'), 'user.php',null,
                [wrap_child('Create User'), 'user.php?action=add'],
                [wrap_child('Search Users'), 'user.php?action=search'],
                [wrap_child('Email Users'), 'user.php?action=email'],
                [wrap_child('User Ban Control'), 'user.php?action=ban']
            ],
            [wrap_parent('Images'), 'images.php',null,
                [wrap_child('Add Image'), 'images.php?action=create']
            ],
            [wrap_parent('Menu'), 'menu.php',null,
                [wrap_child('Create Menu Item'), 'menu.php?action=create'],
                [wrap_child('Menu Configuration'), 'config.php?action=menu']
            ],
            [wrap_parent('FAQ'), 'faq.php',null,
                [wrap_child('Create FAQ Category'), 'faq.php?action=create&subaction=cat'],
                [wrap_child('Create FAQ'), 'faq.php?action=create&subaction=faq'],
                [wrap_child('FAQ Configuration'), 'config.php?action=faq']
            ]
        ],
        [wrap_root('Forums'), 'forums.php', {'sw' : 110},
            [wrap_child('Create Forum Category'), 'forums.php?action=addCat'],
            [wrap_child('Create Forum'), 'forums.php?action=addForum'],
            [wrap_child('Manage Forum Order'), 'forums.php?action=order'],
            [wrap_child('Censor Words'), 'forums.php?action=words'],
            [wrap_child('User Avatars'), 'forums.php?action=avatars'],
            [wrap_child('Forum Ranks'), 'forums.php?action=ranks'],
            [wrap_child('Prune Forums'), 'forums.php?action=prune'],
            [wrap_child('Smilies'), 'forums.php?action=smile'],
            [wrap_child('Flags'), 'forums.php?action=flag'],
            [wrap_child('Xcode'), 'forums.php?action=xcode'],
            [wrap_child('Forum Configuration'), 'forums.php?action=config']
        ],
        [wrap_root('Help & Docs'), null, {'sw' : 110},
            [wrap_child('General Configuration'), 'config.php'],
            [wrap_child('Security Configuration'), 'config.php?action=security'],
            [wrap_parent('Database Functions'), null, null,
                [wrap_child('Backup Database'), 'config.php?action=database&subaction=backup'],
                [wrap_child('Restore Database'), 'config.php?action=database&subaction=restore'],
                [wrap_child('Database Maintenence'), 'config.php?action=database']
            ],
            [wrap_child('User Documentation'), 'javascript:startHelp(\'help.php\')'],
            [wrap_child('PHPX Website'), 'http://www.phpx.org/']
        ]
];

