<?php
/**
 * Plugin Name: CMB Recruitment API
 * Description: REST API (namespace cmb/v1) phục vụ trang tuyển dụng React: danh sách tin tuyển dụng, nộp hồ sơ.
 * Version: 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/includes/cpt-application.php';
require_once __DIR__ . '/includes/cpt-contact.php';
require_once __DIR__ . '/includes/admin-category-field.php';
require_once __DIR__ . '/includes/admin-location-field.php';
require_once __DIR__ . '/includes/admin-taxonomy-image-field.php';
require_once __DIR__ . '/includes/admin-job-field-ui.php';
require_once __DIR__ . '/includes/routes-jobs.php';
require_once __DIR__ . '/includes/routes-applications.php';
require_once __DIR__ . '/includes/admin-application-badge.php';
require_once __DIR__ . '/includes/blog-category-visibility.php';
require_once __DIR__ . '/includes/routes-blog.php';
require_once __DIR__ . '/includes/routes-site-config.php';
require_once __DIR__ . '/includes/routes-contact.php';
require_once __DIR__ . '/includes/routes-static-pages.php';
