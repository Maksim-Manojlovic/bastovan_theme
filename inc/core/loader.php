<?php

if ( ! defined('ABSPATH') ) exit;

/**
 * CORE
 */

require_once __DIR__.'/setup.php';
require_once __DIR__.'/helpers.php';

/**
 * CONTENT
 */

require_once __DIR__.'/../content/menus.php';
require_once __DIR__.'/../content/post-types.php';
require_once __DIR__.'/../content/taxonomies.php';
require_once __DIR__.'/../content/sidebars.php';

/**
 * SECTIONS
 */

require_once __DIR__.'/../sections/section-loader.php';
require_once __DIR__.'/../sections/sections-registry.php';
require_once __DIR__.'/../sections/sections-cache.php';

/**
 * ADMIN
 */

require_once __DIR__.'/../admin/admin.php';
require_once __DIR__.'/../admin/meta-boxes.php';
require_once __DIR__.'/../admin/page-sections.php';
require_once __DIR__.'/../admin/theme-options.php';

/**
 * PERFORMANCE
 */

require_once __DIR__.'/../performance/performance.php';
require_once __DIR__.'/../performance/cache-clear.php';

/**
 * ASSETS
 */

require_once __DIR__.'/../assets/assets.php';

/**
 * NAVIGATION
 */

require_once __DIR__.'/../navigation/class-nav-walker.php';

/**
 * SHORTCODES
 */

require_once __DIR__.'/../shortcodes/shortcodes.php';