<?php
/**
 * Genesis Design Palette Pro - Helper Module
 *
 * Contains various functionality and data used throughout
 *
 * @package Design Palette Pro
 */

/*
	Copyright 2014 Reaktiv Studios

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; version 2 of the License (GPL v2) only.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if ( ! class_exists( 'GP_Pro_Helper' ) ) {

// start the engine
class GP_Pro_Helper {

	/**
	 * set our initial default array
	 */
	public static function set_defaults() {

		// Set the default values
		$defaults   = array(
			// general
			'body-color-back-thin'              => '#ffffff',
			'body-color-back-main'              => '#f5f5f5',
			'body-color-text'                   => '#333333',
			'body-color-link'                   => '#e5554e',
			'body-color-link-hov'               => '#333333',
			'body-type-stack'                   => 'lato',
			'body-type-size'                    => '18',
			'body-type-weight'                  => '300',
			'body-type-style'                   => 'normal',

			// site header
			'header-color-back'                 => '#ffffff',
			'header-padding-top'                => '40',
			'header-padding-bottom'             => '40',
			'header-padding-left'               => '0',
			'header-padding-right'              => '0',

			// site title
			'site-title-text'                   => '#333333',
			'site-title-stack'                  => 'lato',
			'site-title-size'                   => '32',
			'site-title-weight'                 => '400',
			'site-title-transform'              => 'none',
			'site-title-align'                  => 'left',
			'site-title-style'                  => 'normal',
			'site-title-padding-top'            => '10',
			'site-title-padding-bottom'         => '10',
			'site-title-padding-left'           => '0',
			'site-title-padding-right'          => '0',

			// site description
			'site-desc-display'                 => 'block',
			'site-desc-text'                    => '#333333',
			'site-desc-stack'                   => 'lato',
			'site-desc-size'                    => '16',
			'site-desc-weight'                  => '300',
			'site-desc-transform'               => 'none',
			'site-desc-align'                   => 'left',
			'site-desc-style'                   => 'normal',

			// header navigation
			'header-nav-item-back'              => '#ffffff',
			'header-nav-item-back-hov'          => '#ffffff',
			'header-nav-item-link'              => '#333333',
			'header-nav-item-link-hov'          => '#e5554e',
			'header-nav-stack'                  => 'lato',
			'header-nav-size'                   => '16',
			'header-nav-weight'                 => '300',
			'header-nav-transform'              => 'none',
			'header-nav-style'                  => 'normal',
			'header-nav-item-padding-top'       => '30',
			'header-nav-item-padding-bottom'    => '30',
			'header-nav-item-padding-left'      => '24',
			'header-nav-item-padding-right'     => '24',

			// header widgets
			'header-widget-title-color'         => '#333333',
			'header-widget-title-stack'         => 'lato',
			'header-widget-title-size'          => '18',
			'header-widget-title-weight'        => '400',
			'header-widget-title-transform'     => 'none',
			'header-widget-title-align'         => 'right',
			'header-widget-title-style'         => 'normal',
			'header-widget-title-margin-bottom' => '20',

			'header-widget-content-text'        => '#333333',
			'header-widget-content-link'        => '#e5554e',
			'header-widget-content-link-hov'    => '#333333',
			'header-widget-content-stack'       => 'lato',
			'header-widget-content-size'        => '18',
			'header-widget-content-weight'      => '300',
			'header-widget-content-align'       => 'right',
			'header-widget-content-style'       => 'normal',

			// primary navigation
			'primary-nav-area-back'                 => '#333333',

			'primary-nav-top-stack'                 => 'lato',
			'primary-nav-top-size'                  => '16',
			'primary-nav-top-weight'                => '300',
			'primary-nav-top-transform'             => 'none',
			'primary-nav-top-align'                 => 'left',
			'primary-nav-top-style'                 => 'normal',

			'primary-nav-top-item-base-back'        => '#333333',
			'primary-nav-top-item-base-back-hov'    => '#333333',
			'primary-nav-top-item-base-link'        => '#ffffff',
			'primary-nav-top-item-base-link-hov'    => '#e5554e',

			'primary-nav-top-item-active-back'      => '#333333',
			'primary-nav-top-item-active-back-hov'  => '#333333',
			'primary-nav-top-item-active-link'      => '#e5554e',
			'primary-nav-top-item-active-link-hov'  => '#e5554e',

			'primary-nav-top-item-padding-top'      => '30',
			'primary-nav-top-item-padding-bottom'   => '30',
			'primary-nav-top-item-padding-left'     => '24',
			'primary-nav-top-item-padding-right'    => '24',

			'primary-nav-drop-stack'                => 'lato',
			'primary-nav-drop-size'                 => '14',
			'primary-nav-drop-weight'               => '300',
			'primary-nav-drop-transform'            => 'none',
			'primary-nav-drop-align'                => 'left',
			'primary-nav-drop-style'                => 'normal',

			'primary-nav-drop-item-base-back'       => '#ffffff',
			'primary-nav-drop-item-base-back-hov'   => '#ffffff',
			'primary-nav-drop-item-base-link'       => '#333333',
			'primary-nav-drop-item-base-link-hov'   => '#e5554e',

			'primary-nav-drop-item-active-back'     => '#ffffff',
			'primary-nav-drop-item-active-back-hov' => '#ffffff',
			'primary-nav-drop-item-active-link'     => '#e5554e',
			'primary-nav-drop-item-active-link-hov' => '#e5554e',

			'primary-nav-drop-item-padding-top'     => '20',
			'primary-nav-drop-item-padding-bottom'  => '20',
			'primary-nav-drop-item-padding-left'    => '20',
			'primary-nav-drop-item-padding-right'   => '20',

			'primary-nav-drop-border-color'         => '#eeeeee',
			'primary-nav-drop-border-style'         => 'solid',
			'primary-nav-drop-border-width'         => '1',

			// secondary navigation
			'secondary-nav-area-back'               => '#ffffff',

			'secondary-nav-top-stack'               => 'lato',
			'secondary-nav-top-size'                => '16',
			'secondary-nav-top-weight'              => '300',
			'secondary-nav-top-transform'           => 'none',
			'secondary-nav-top-align'               => 'left',
			'secondary-nav-top-style'               => 'normal',

			'secondary-nav-top-item-base-back'      => '#ffffff',
			'secondary-nav-top-item-base-back-hov'  => '#ffffff',
			'secondary-nav-top-item-base-link'      => '#333333',
			'secondary-nav-top-item-base-link-hov'  => '#e5554e',

			'secondary-nav-top-item-active-back'        => '#ffffff',
			'secondary-nav-top-item-active-back-hov'    => '#ffffff',
			'secondary-nav-top-item-active-link'        => '#e5554e',
			'secondary-nav-top-item-active-link-hov'    => '#e5554e',

			'secondary-nav-top-item-padding-top'        => '30',
			'secondary-nav-top-item-padding-bottom'     => '30',
			'secondary-nav-top-item-padding-left'       => '24',
			'secondary-nav-top-item-padding-right'      => '24',

			'secondary-nav-drop-stack'              => 'lato',
			'secondary-nav-drop-size'               => '14',
			'secondary-nav-drop-weight'             => '300',
			'secondary-nav-drop-transform'          => 'none',
			'secondary-nav-drop-align'              => 'left',
			'secondary-nav-drop-style'              => 'normal',

			'secondary-nav-drop-item-base-back'         => '#ffffff',
			'secondary-nav-drop-item-base-back-hov'     => '#ffffff',
			'secondary-nav-drop-item-base-link'         => '#333333',
			'secondary-nav-drop-item-base-link-hov'     => '#e5554e',

			'secondary-nav-drop-item-active-back'       => '#ffffff',
			'secondary-nav-drop-item-active-back-hov'   => '#ffffff',
			'secondary-nav-drop-item-active-link'       => '#e5554e',
			'secondary-nav-drop-item-active-link-hov'   => '#e5554e',

			'secondary-nav-drop-item-padding-top'       => '20',
			'secondary-nav-drop-item-padding-bottom'    => '20',
			'secondary-nav-drop-item-padding-left'      => '20',
			'secondary-nav-drop-item-padding-right'     => '20',

			'secondary-nav-drop-border-color'       => '#eeeeee',
			'secondary-nav-drop-border-style'       => 'solid',
			'secondary-nav-drop-border-width'       => '1',


			// post area wrapper
			'site-inner-padding-top'            => '40',

			// main entry area
			'main-entry-back'               => '#ffffff',
			'main-entry-border-radius'      => '0',
			'main-entry-padding-top'        => '50',
			'main-entry-padding-bottom'     => '50',
			'main-entry-padding-left'       => '60',
			'main-entry-padding-right'      => '60',
			'main-entry-margin-top'         => '0',
			'main-entry-margin-bottom'      => '40',
			'main-entry-margin-left'        => '0',
			'main-entry-margin-right'       => '0',

			// post title area
			'post-title-text'               => '#333333',
			'post-title-link'               => '#333333',
			'post-title-link-hov'           => '#e5554e',
			'post-title-stack'              => 'lato',
			'post-title-size'               => '36',
			'post-title-weight'             => '400',
			'post-title-transform'          => 'none',
			'post-title-align'              => 'left',
			'post-title-style'              => 'normal',
			'post-title-margin-bottom'      => '10',

			// entry meta
			'post-header-meta-text-color'       => '#333333',
			'post-header-meta-date-color'       => '#333333',
			'post-header-meta-author-link'      => '#e5554e',
			'post-header-meta-author-link-hov'  => '#333333',
			'post-header-meta-comment-link'     => '#e5554e',
			'post-header-meta-comment-link-hov' => '#333333',

			'post-header-meta-stack'            => 'lato',
			'post-header-meta-size'             => '16',
			'post-header-meta-weight'           => '300',
			'post-header-meta-transform'        => 'none',
			'post-header-meta-align'            => 'left',
			'post-header-meta-style'            => 'normal',

			// post text
			'post-entry-text'               => '#333333',
			'post-entry-link'               => '#e5554e',
			'post-entry-link-hov'           => '#333333',
			'post-entry-stack'              => 'lato',
			'post-entry-size'               => '18',
			'post-entry-weight'             => '300',
			'post-entry-style'              => 'normal',
			'post-entry-list-ol'            => 'decimal',
			'post-entry-list-ul'            => 'disc',

			// entry-footer
			'post-footer-category-text'         => '#333333',
			'post-footer-category-link'         => '#e5554e',
			'post-footer-category-link-hov'     => '#333333',
			'post-footer-tag-text'              => '#333333',
			'post-footer-tag-link'              => '#e5554e',
			'post-footer-tag-link-hov'          => '#333333',
			'post-footer-stack'                 => 'lato',
			'post-footer-size'                  => '16',
			'post-footer-weight'                => '300',
			'post-footer-transform'             => 'none',
			'post-footer-align'                 => 'left',
			'post-footer-style'                 => 'normal',
			'post-footer-divider-color'         => '#f5f5f5',
			'post-footer-divider-style'         => 'solid',
			'post-footer-divider-width'         => '2',

			// read more link
			'extras-read-more-link'         => '#e5554e',
			'extras-read-more-link-hov'     => '#333333',
			'extras-read-more-stack'        => 'lato',
			'extras-read-more-size'         => '18',
			'extras-read-more-weight'       => '300',
			'extras-read-more-transform'    => 'none',
			'extras-read-more-style'        => 'normal',

			// breadcrumbs
			'extras-breadcrumb-text'        => '#333333',
			'extras-breadcrumb-link'        => '#e5554e',
			'extras-breadcrumb-link-hov'    => '#333333',
			'extras-breadcrumb-stack'       => 'lato',
			'extras-breadcrumb-size'        => '18',
			'extras-breadcrumb-weight'      => '300',
			'extras-breadcrumb-transform'   => 'none',
			'extras-breadcrumb-style'       => 'normal',

			// pagination typography (apply to both )
			'extras-pagination-stack'       => 'lato',
			'extras-pagination-size'        => '16',
			'extras-pagination-weight'      => '300',
			'extras-pagination-transform'   => 'none',
			'extras-pagination-style'       => 'normal',

			// pagination text
			'extras-pagination-text-link'       => '#e5554e',
			'extras-pagination-text-link-hov'   => '#333333',

			// pagination numeric
			'extras-pagination-numeric-back'                => '#333333',
			'extras-pagination-numeric-back-hov'            => '#e5554e',
			'extras-pagination-numeric-active-back'         => '#e5554e',
			'extras-pagination-numeric-active-back-hov'     => '#e5554e',
			'extras-pagination-numeric-border-radius'       => '0',

			'extras-pagination-numeric-padding-top'         => '8',
			'extras-pagination-numeric-padding-bottom'      => '8',
			'extras-pagination-numeric-padding-left'        => '12',
			'extras-pagination-numeric-padding-right'       => '12',

			'extras-pagination-numeric-link'                => '#ffffff',
			'extras-pagination-numeric-link-hov'            => '#ffffff',
			'extras-pagination-numeric-active-link'         => '#ffffff',
			'extras-pagination-numeric-active-link-hov'     => '#ffffff',

			// author box
			'extras-author-box-back'            => '#ffffff',

			'extras-author-box-padding-top'     => '40',
			'extras-author-box-padding-bottom'  => '40',
			'extras-author-box-padding-left'    => '40',
			'extras-author-box-padding-right'   => '40',

			'extras-author-box-margin-top'      => '0',
			'extras-author-box-margin-bottom'   => '40',
			'extras-author-box-margin-left'     => '0',
			'extras-author-box-margin-right'    => '0',

			'extras-author-box-name-text'       => '#333333',
			'extras-author-box-name-stack'      => 'lato',
			'extras-author-box-name-size'       => '16',
			'extras-author-box-name-weight'     => '400',
			'extras-author-box-name-align'      => 'left',
			'extras-author-box-name-transform'  => 'none',
			'extras-author-box-name-style'      => 'normal',

			'extras-author-box-bio-text'        => '#333333',
			'extras-author-box-bio-link'        => '#e5554e',
			'extras-author-box-bio-link-hov'    => '#333333',
			'extras-author-box-bio-stack'       => 'lato',
			'extras-author-box-bio-size'        => '16',
			'extras-author-box-bio-weight'      => '300',
			'extras-author-box-bio-style'       => 'normal',

			// comment list
			'comment-list-back'             => '#ffffff',
			'comment-list-padding-top'      => '40',
			'comment-list-padding-bottom'   => '40',
			'comment-list-padding-left'     => '40',
			'comment-list-padding-right'    => '40',

			'comment-list-margin-top'       => '0',
			'comment-list-margin-bottom'    => '40',
			'comment-list-margin-left'      => '0',
			'comment-list-margin-right'     => '0',

			// comment list title
			'comment-list-title-text'           => '#333333',
			'comment-list-title-stack'          => 'lato',
			'comment-list-title-size'           => '24',
			'comment-list-title-weight'         => '400',
			'comment-list-title-transform'      => 'none',
			'comment-list-title-align'          => 'left',
			'comment-list-title-style'          => 'normal',
			'comment-list-title-margin-bottom'  => '10',

			// single comments
			'single-comment-padding-top'        => '32',
			'single-comment-padding-bottom'     => '32',
			'single-comment-padding-left'       => '32',
			'single-comment-padding-right'      => '32',
			'single-comment-margin-top'         => '24',
			'single-comment-margin-bottom'      => '0',
			'single-comment-margin-left'        => '0',
			'single-comment-margin-right'       => '0',

			// color setup for standard and author comments
			'single-comment-standard-back'          => '#f5f5f5',
			'single-comment-standard-border-color'  => '#ffffff',
			'single-comment-standard-border-style'  => 'solid',
			'single-comment-standard-border-width'  => '2',
			'single-comment-author-back'            => '#f5f5f5',
			'single-comment-author-border-color'    => '#ffffff',
			'single-comment-author-border-style'    => 'solid',
			'single-comment-author-border-width'    => '2',

			// comment name
			'comment-element-name-text'             => '#333333',
			'comment-element-name-link'             => '#e5554e',
			'comment-element-name-link-hov'         => '#333333',
			'comment-element-name-stack'            => 'lato',
			'comment-element-name-size'             => '16',
			'comment-element-name-weight'           => '300',
			'comment-element-name-style'            => 'normal',

			// comment date
			'comment-element-date-link'             => '#e5554e',
			'comment-element-date-link-hov'         => '#333333',
			'comment-element-date-stack'            => 'lato',
			'comment-element-date-size'             => '16',
			'comment-element-date-weight'           => '300',
			'comment-element-date-style'            => 'normal',

			// comment body
			'comment-element-body-text'             => '#333333',
			'comment-element-body-link'             => '#666666',
			'comment-element-body-link-hov'         => '#e5554e',
			'comment-element-body-stack'            => 'lato',
			'comment-element-body-size'             => '18',
			'comment-element-body-weight'           => '300',
			'comment-element-body-style'            => 'normal',

			// comment reply
			'comment-element-reply-link'            => '#e5554e',
			'comment-element-reply-link-hov'        => '#333333',
			'comment-element-reply-stack'           => 'lato',
			'comment-element-reply-size'            => '18',
			'comment-element-reply-weight'          => '300',
			'comment-element-reply-align'           => 'left',
			'comment-element-reply-style'           => 'normal',

			// trackback list
			'trackback-list-back'               => '#ffffff',
			'trackback-list-padding-top'        => '40',
			'trackback-list-padding-bottom'     => '16',
			'trackback-list-padding-left'       => '40',
			'trackback-list-padding-right'      => '40',

			'trackback-list-margin-top'         => '0',
			'trackback-list-margin-bottom'      => '40',
			'trackback-list-margin-left'        => '0',
			'trackback-list-margin-right'       => '0',

			// trackback list title
			'trackback-list-title-text'             => '#333333',
			'trackback-list-title-stack'            => 'lato',
			'trackback-list-title-size'             => '24',
			'trackback-list-title-weight'           => '400',
			'trackback-list-title-transform'        => 'none',
			'trackback-list-title-align'            => 'left',
			'trackback-list-title-style'            => 'normal',
			'trackback-list-title-margin-bottom'    => '10',

			// trackback name
			'trackback-element-name-text'           => '#333333',
			'trackback-element-name-link'           => '#e5554e',
			'trackback-element-name-link-hov'       => '#333333',
			'trackback-element-name-stack'          => 'lato',
			'trackback-element-name-size'           => '18',
			'trackback-element-name-weight'         => '300',
			'trackback-element-name-style'          => 'normal',

			// trackback date
			'trackback-element-date-link'           => '#e5554e',
			'trackback-element-date-link-hov'       => '#333333',
			'trackback-element-date-stack'          => 'lato',
			'trackback-element-date-size'           => '18',
			'trackback-element-date-weight'         => '300',
			'trackback-element-date-style'          => 'normal',

			// trackback body
			'trackback-element-body-text'           => '#333333',
			'trackback-element-body-stack'          => 'lato',
			'trackback-element-body-size'           => '18',
			'trackback-element-body-weight'         => '300',
			'trackback-element-body-style'          => 'normal',

			// comment form
			'comment-reply-back'                => '#ffffff',
			'comment-reply-padding-top'         => '40',
			'comment-reply-padding-bottom'      => '16',
			'comment-reply-padding-left'        => '40',
			'comment-reply-padding-right'       => '40',

			'comment-reply-margin-top'          => '0',
			'comment-reply-margin-bottom'       => '40',
			'comment-reply-margin-left'         => '0',
			'comment-reply-margin-right'        => '0',

			// comment form title
			'comment-reply-title-text'          => '#333333',
			'comment-reply-title-stack'         => 'lato',
			'comment-reply-title-size'          => '24',
			'comment-reply-title-weight'        => '400',
			'comment-reply-title-transform'     => 'none',
			'comment-reply-title-align'         => 'left',
			'comment-reply-title-style'         => 'normal',
			'comment-reply-title-margin-bottom' => '10',

			// comment form notes
			'comment-reply-notes-text'          => '#333333',
			'comment-reply-notes-link'          => '#e5554e',
			'comment-reply-notes-link-hov'      => '#333333',
			'comment-reply-notes-stack'         => 'lato',
			'comment-reply-notes-size'          => '18',
			'comment-reply-notes-weight'        => '300',
			'comment-reply-notes-style'         => 'normal',

			// comment allowed tags
			'comment-reply-atags-base-back'     => '#f5f5f5',
			'comment-reply-atags-base-text'     => '#333333',
			'comment-reply-atags-base-stack'    => 'lato',
			'comment-reply-atags-base-size'     => '16',
			'comment-reply-atags-base-weight'   => '300',
			'comment-reply-atags-base-style'    => 'normal',

			// comment allowed tags code
			'comment-reply-atags-code-text'     => '#333333',
			'comment-reply-atags-code-stack'    => 'monospace',
			'comment-reply-atags-code-size'     => '14',
			'comment-reply-atags-code-weight'   => '300',

			// comment fields labels
			'comment-reply-fields-label-text'       => '#333333',
			'comment-reply-fields-label-stack'      => 'lato',
			'comment-reply-fields-label-size'       => '18',
			'comment-reply-fields-label-weight'     => '300',
			'comment-reply-fields-label-transform'  => 'none',
			'comment-reply-fields-label-align'      => 'left',
			'comment-reply-fields-label-style'      => 'normal',

			// comment fields inputs
			'comment-reply-fields-input-field-width'            => '50',
			'comment-reply-fields-input-border-style'           => 'solid',
			'comment-reply-fields-input-border-width'           => '1',
			'comment-reply-fields-input-border-radius'          => '0',
			'comment-reply-fields-input-padding'                => '16',
			'comment-reply-fields-input-margin-bottom'          => '0',
			'comment-reply-fields-input-base-back'              => '#ffffff',
			'comment-reply-fields-input-focus-back'             => '#ffffff',
			'comment-reply-fields-input-base-border-color'      => '#dddddd',
			'comment-reply-fields-input-focus-border-color'     => '#999999',
			'comment-reply-fields-input-text'                   => '#333333',
			'comment-reply-fields-input-stack'                  => 'lato',
			'comment-reply-fields-input-size'                   => '18',
			'comment-reply-fields-input-weight'                 => '300',
			'comment-reply-fields-input-style'                  => 'normal',

			// comment button
			'comment-submit-button-back'                => '#333333',
			'comment-submit-button-back-hov'            => '#e5554e',
			'comment-submit-button-text'                => '#ffffff',
			'comment-submit-button-text-hov'            => '#ffffff',
			'comment-submit-button-stack'               => 'lato',
			'comment-submit-button-size'                => '16',
			'comment-submit-button-weight'              => '300',
			'comment-submit-button-transform'           => 'uppercase',
			'comment-submit-button-style'               => 'normal',
			'comment-submit-button-padding-top'         => '16',
			'comment-submit-button-padding-bottom'      => '16',
			'comment-submit-button-padding-left'        => '24',
			'comment-submit-button-padding-right'       => '24',
			'comment-submit-button-border-radius'       => '0',

			// sidebar widgets
			'sidebar-widget-back'               => '#ffffff',
			'sidebar-widget-border-radius'      => '0',
			'sidebar-widget-padding-top'        => '40',
			'sidebar-widget-padding-bottom'     => '40',
			'sidebar-widget-padding-left'       => '40',
			'sidebar-widget-padding-right'      => '40',
			'sidebar-widget-margin-top'         => '0',
			'sidebar-widget-margin-bottom'      => '40',
			'sidebar-widget-margin-left'        => '0',
			'sidebar-widget-margin-right'       => '0',

			// sidebar widget titles
			'sidebar-widget-title-text'             => '#333333',
			'sidebar-widget-title-stack'            => 'lato',
			'sidebar-widget-title-size'             => '18',
			'sidebar-widget-title-weight'           => '400',
			'sidebar-widget-title-transform'        => 'none',
			'sidebar-widget-title-align'            => 'left',
			'sidebar-widget-title-style'            => 'normal',
			'sidebar-widget-title-margin-bottom'    => '20',

			// sidebar widget content
			'sidebar-widget-content-text'           => '#333333',
			'sidebar-widget-content-link'           => '#e5554e',
			'sidebar-widget-content-link-hov'       => '#333333',
			'sidebar-widget-content-stack'          => 'lato',
			'sidebar-widget-content-size'           => '16',
			'sidebar-widget-content-weight'         => '300',
			'sidebar-widget-content-align'          => 'left',
			'sidebar-widget-content-style'          => 'normal',

			// footer widget row
			'footer-widget-row-back'                => '#333333',
			'footer-widget-row-padding-top'         => '40',
			'footer-widget-row-padding-bottom'      => '0',
			'footer-widget-row-padding-left'        => '0',
			'footer-widget-row-padding-right'       => '0',

			// footer widget singles
			'footer-widget-single-back'             => '#333333',
			'footer-widget-single-margin-bottom'    => '0',
			'footer-widget-single-padding-top'      => '0',
			'footer-widget-single-padding-bottom'   => '0',
			'footer-widget-single-padding-left'     => '0',
			'footer-widget-single-padding-right'    => '0',
			'footer-widget-single-border-radius'    => '0',

			// footer widget title
			'footer-widget-title-text'              => '#ffffff',
			'footer-widget-title-stack'             => 'lato',
			'footer-widget-title-size'              => '18',
			'footer-widget-title-weight'            => '400',
			'footer-widget-title-transform'         => 'none',
			'footer-widget-title-align'             => 'left',
			'footer-widget-title-style'             => 'normal',
			'footer-widget-title-margin-bottom'     => '20',

			// footer widget content
			'footer-widget-content-text'            => '#999999',
			'footer-widget-content-link'            => '#999999',
			'footer-widget-content-link-hov'        => '#ffffff',
			'footer-widget-content-stack'           => 'lato',
			'footer-widget-content-size'            => '18',
			'footer-widget-content-weight'          => '300',
			'footer-widget-content-align'           => 'left',
			'footer-widget-content-style'           => 'normal',

			// bottom footer
			'footer-main-back'              => '#ffffff',
			'footer-main-padding-top'       => '40',
			'footer-main-padding-bottom'    => '40',
			'footer-main-padding-left'      => '0',
			'footer-main-padding-right'     => '0',

			'footer-main-content-text'          => '#333333',
			'footer-main-content-link'          => '#e5554e',
			'footer-main-content-link-hov'      => '#333333',
			'footer-main-content-stack'         => 'lato',
			'footer-main-content-size'          => '16',
			'footer-main-content-weight'        => '300',
			'footer-main-content-transform'     => 'none',
			'footer-main-content-align'         => 'center',
			'footer-main-content-style'         => 'normal',
		);

		// set the filter on defaults and return
		return apply_filters( 'gppro_set_defaults', $defaults );
	}

	/**
	 * checks if we are using a supported child theme
	 *
	 * @return boolean|array   the array of data for the theme or false
	 */
	public static function is_child_theme() {

		// fetch my selected child theme
		$theme  = GP_Pro_Themes::get_selected_child_theme();

		// if we have no theme, or it's Genesis, or no data on saved one, return false
		if ( empty( $theme ) || ! empty( $theme ) && $theme == 'genesis' || false === $data = GP_Pro_Themes::get_supported_themes( $theme ) ) {
			return false;
		}

		// return our data
		return $data;
	}

	/**
	 * checks for the new REM stuff in Genesis 2.2
	 *
	 * @return boolean|array   the array of data for the theme or false
	 */
	public static function is_using_rems() {

		// first check for the new Genesis a11y function
		if ( function_exists( 'genesis_a11y' ) ) {

			// test for REMs
			$rems   = genesis_a11y( 'rems' );

			// return result
			return empty( $rems ) ? false : true;
		}

		// use the old one
		if ( function_exists( 'get_theme_support' ) ) {

			// check for the whole accessibility thing
			$a11y   = get_theme_support( 'genesis-accessibility' );

			// return result
			return empty( $a11y ) || empty( $a11y[0] ) || ! in_array( 'rems', $a11y[0] ) ? false : true;
		}

		// nothing left. guess false
		return false;
	}

	/**
	 * set the base font size for use in REM calculations
	 */
	public static function base_font_size() {
		return apply_filters( 'gppro_base_font_size', 10 );
	}

	/**
	 * fetch the default value for the called field
	 *
	 * @param  string  $name     the setting key name to check for.
	 *
	 * @return string            either a single default value or false if none
	 */
	public static function get_default( $name = '' ) {

		// Get core plugin instance
		$GPP = Genesis_Palette_Pro::getInstance();

		// get my defaults
		$defaults   = $GPP->get_defaults();

		// return empty if there is no default to be found, or enforce lower case and return
		return ! empty( $defaults[ $name ] ) ? esc_attr( strtolower( $defaults[ $name ] ) ) : '';
	}

	/**
	 * count the total of defaults and compare against the max
	 * vars. if we are close (or over), set it so we serialize
	 * information
	 *
	 * @return string            either 'standard' or 'serialized'
	 */
	public static function maybe_serialize_vars() {

		// Get core plugin instance
		$GPP = Genesis_Palette_Pro::getInstance();

		// get my defaults
		$defaults   = $GPP->get_defaults();

		// and count them, adding a "buffer" of 100
		$defnumb    = count( $defaults ) + 100;

		// get my max vars
		$maxvars    = GP_Pro_Utilities::get_max_vars_val();

		// return our string
		return absint( $maxvars ) >= absint( $defnumb ) ? 'standard' : 'serialize';
	}

	/**
	 * DEPRECATED
	 *
	 * setup for retrieving the name portion of a field
	 * removed in v1.3.12 in favor of 'get_field_item'
	 *
	 * @param  string  $name       the setting key name to check for
	 *
	 * @return string|mixed
	 */
	public static function get_field_name( $name = '' ) {
		return 'gppro-settings[' . sanitize_title_with_dashes( $name, '', 'save' ) . ']';
	}

	/**
	 * DEPRECATED
	 *
	 * setup for retrieving the ID portion of a field
	 * removed in v1.3.12 in favor of 'get_field_item'
	 *
	 * @param  string  $name       the setting key name to check for
	 *
	 * @return string|mixed
	 */
	public static function get_field_id( $name = '' ) {
		return sanitize_title_with_dashes( $name, '', 'save' );
	}

	/**
	 * DEPRECATED
	 *
	 * setup for retrieving the value of a field
	 * removed in v1.3.12 in favor of 'get_field_item'
	 *
	 * @param  string  $name       the setting key name to check for
	 *
	 * @return string|mixed
	 */
	public static function get_field_value( $name = '' ) {

		// get my data
		$data   = get_option( 'gppro-settings' );

		// check for and return the value or default
		return isset( $data[ $name ] ) ? esc_attr( $data[ $name ] ) : self::get_default( $name );
	}

	/**
	 * pull the stored values and return a single value
	 *
	 * @param  string  $key        the setting key name to check for. 'all' will return all
	 * @param  boolean $fallback   whether to return the default or not
	 *
	 * @return string|array        either a single value, array of all, or false if none
	 */
	public static function get_single_value( $key = '', $fallback = false ) {

		// check for data
		$data   = self::get_single_option( 'gppro-settings', '', $fallback );

		// check for a key and data first
		if ( empty( $key ) || empty( $data ) && false === $fallback ) {
			return false;
		}

		// if my key is "all" then send back the entire thing
		if ( $key == 'all' ) {
			return $data;
		}

		// return the value if we have one, ignoring the rest of the process
		if ( ! empty( $data[ $key ] ) ) {
			return esc_attr( $data[ $key ] );
		}

		// if we have a zero value, that's OK. return it
		if ( isset( $data[ $key ] ) && is_numeric( $data[ $key ] ) && absint( $data[ $key ] ) === 0 ) {
			return $data[ $key ];
		}

		// return false if no data present and no fallback is requested, or the default
		return empty( $data[ $key ] ) && false !== $fallback ? self::get_default( $key ) : false;
	}

	/**
	 * setup for retrieving the name or ID portion of a field
	 *
	 * @param  string  $key        the setting key to check for
	 * @param  string  $type       whether to return name or ID
	 *
	 * @return string|mixed
	 */
	public static function get_field_item( $key = '', $type = 'name' ) {

		// if requesting value, return that and finish
		if ( ! empty( $type ) && $type == 'value' ) {
			return self::get_single_value( $key, true );
		}

		// do my setup
		$setup  = sanitize_title_with_dashes( $key, '', 'save' );

		// return it
		return ! empty( $type ) && $type == 'id' ? $setup : 'gppro-settings[' . $setup . ']';
	}

	/**
	 * build default font stack option set
	 *
	 * @return array|string
	 */
	public static function stacks() {

		$stacks['serif']	= array( // The serif fonts.

			'baskerville'	=> array(
				'label'	=> __( 'Baskerville', 'gppro' ),
				'css'	=> 'Baskerville, "Baskerville old face", "Hoefler Text", Garamond, "Times New Roman", serif',
				'src'	=> 'native',
				'size'	=> '0',
			),

			'bigcaslon'	=> array(
				'label'	=> __( 'Big Caslon', 'gppro' ),
				'css'	=> '"Big Caslon", "Book Antiqua", "Palatino Linotype", Georgia, serif',
				'src'	=> 'native',
				'size'	=> '0',
			),

			'garamond'	=> array(
				'label'	=> __( 'Garamond', 'gppro' ),
				'css'	=> 'Garamond, Baskerville, "Baskerville Old Face", "Hoefler Text", "Times New Roman", serif',
				'src'	=> 'native',
				'size'	=> '0',
			),

			'hoeflertext'	=> array(
				'label'	=> __( 'Hoefler Text', 'gppro' ),
				'css'	=> '"Hoefler Text", "Baskerville old face", Garamond, "Times New Roman", serif',
				'src'	=> 'native',
				'size'	=> '0',
			),

			'lucidabright'	=> array(
				'label'	=> __( 'Lucida Bright', 'gppro' ),
				'css'	=> '"Lucida Bright", Lucidabright, Georgia, serif',
				'src'	=> 'native',
				'size'	=> '0',
			),

			'palatino'	=> array(
				'label'	=> __( 'Palatino', 'gppro' ),
				'css'	=> 'Palatino, "Palatino Linotype", "Palatino LT STD", "Book Antiqua", Georgia, serif',
				'src'	=> 'native',
				'size'	=> '0',
			),
		);

		$stacks['sans']	= array( // The sans-serif fonts.

			'helvetica'	=> array(
				'label'	=> __( 'Helvetica', 'gppro' ),
				'css'	=> '"Helvetica Neue", Helvetica, Arial, sans-serif',
				'src'	=> 'native',
				'size'	=> '0',
			),

			'gillsans'	=> array(
				'label'	=> __( 'Gill Sans', 'gppro' ),
				'css'	=> '"Gill Sans", "Gill Sans MT", Calibri, sans-serif',
				'src'	=> 'native',
				'size'	=> '0',
			),

			'impact'	=> array(
				'label'	=> __( 'Impact', 'gppro' ),
				'css'	=> 'Impact, Haettenschweiler, "Franklin Gothic Bold", Charcoal, "Helvetica Inserat", "Bitstream Vera Sans Bold", "Arial Black", sans-serif',
				'src'	=> 'native',
				'size'	=> '0',
			),

			'lucidagrande'	=> array(
				'label'	=> __( 'Lucida Grande', 'gppro' ),
				'css'	=> '"Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", Geneva, Verdana, sans-serif',
				'src'	=> 'native',
				'size'	=> '0',
			),

			'trebuchet'	=> array(
				'label'	=> __( 'Trebuchet', 'gppro' ),
				'css'	=> '"Trebuchet MS", "Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", Tahoma, sans-serif',
				'src'	=> 'native',
				'size'	=> '0',
			),

			'verdana'	=> array(
				'label'	=> __( 'Verdana', 'gppro' ),
				'css'	=> 'Verdana, Geneva, sans-serif',
				'src'	=> 'native',
				'size'	=> '0',
			),
		);

		$stacks['mono']	= array( // The monospaced fonts.

			'monospace'	=> array(
				'label'	=> __( 'Monospace', 'gppro' ),
				'css'	=> 'monospace, serif',
				'src'	=> 'native',
				'size'	=> '0',
			),

		);

		// return the data, filtered
		return apply_filters( 'gppro_font_stacks', $stacks );
	}

	/**
	 * build default font size set
	 *
	 * @return array|string
	 */
	public static function font_sizes( $scale = 'text' ) {

		$sizes	= array(
			'small'	=> array(
				'8'		=> __( '8px',	'gppro' ),
				'10'	=> __( '10px',	'gppro' ),
				'12'	=> __( '12px',	'gppro' ),
				'14'	=> __( '14px',	'gppro' ),
				'16'	=> __( '16px',	'gppro' ),
			),
			'text'	=> array(
				'14'	=> __( '14px',	'gppro' ),
				'15'	=> __( '15px',	'gppro' ),
				'16'	=> __( '16px',	'gppro' ),
				'17'	=> __( '17px',	'gppro' ),
				'18'	=> __( '18px',	'gppro' ),
				'19'	=> __( '19px',	'gppro' ),
				'20'	=> __( '20px',	'gppro' ),
				'21'	=> __( '21px',	'gppro' ),
				'22'	=> __( '22px',	'gppro' ),
				'23'	=> __( '23px',	'gppro' ),
				'24'	=> __( '24px',	'gppro' ),
			),
			'title'	=> array(
				'24'	=> __( '24px',	'gppro' ),
				'26'	=> __( '26px',	'gppro' ),
				'28'	=> __( '28px',	'gppro' ),
				'30'	=> __( '30px',	'gppro' ),
				'32'	=> __( '32px',	'gppro' ),
				'34'	=> __( '34px',	'gppro' ),
				'36'	=> __( '36px',	'gppro' ),
				'38'	=> __( '38px',	'gppro' ),
				'40'	=> __( '40px',	'gppro' ),
				'42'	=> __( '42px',	'gppro' ),
			),
		);

		$sizes	= apply_filters( 'gppro_default_css_font_sizes', $sizes );

		// return the sizes with requested scale
		return $sizes[$scale];
	}

	/**
	 * build default font weights set
	 *
	 * @return array|mixed $items
	 *
	 */
	public static function font_weights() {

		// set the data array
		$items  = array(
			'300'   => __( '300 (Light)', 'gppro' ),
			'400'   => __( '400 (Normal)', 'gppro' ),
			'700'   => __( '700 (Bold)', 'gppro' )
		);

		// return the items, filtered
		return apply_filters( 'gppro_default_css_font_weights', $items );
	}

	/**
	 * build border types
	 *
	 * @return array|string $items
	 */
	public static function css_borders() {

		// set the data array
		$items  = array(
			'solid'     => __( 'Solid', 'gppro' ),
			'dotted'    => __( 'Dotted', 'gppro' ),
			'dashed'    => __( 'Dashed', 'gppro' ),
			'double'    => __( 'Double', 'gppro' ),
			'groove'    => __( 'Groove', 'gppro' ),
			'ridge'     => __( 'Ridge', 'gppro' ),
			'inset'     => __( 'Inset', 'gppro' ),
			'none'      => __( 'None', 'gppro' )
		);

		// return the items, filtered
		return apply_filters( 'gppro_default_css_borders', $items );
	}

	/**
	 * build list stypes types
	 *
	 * @return array|string $items
	 *
	 */
	public static function list_styles() {

		// set the data array
		$items  = array(
			'disc'                  => __( 'Disc', 'gppro' ),
			'circle'                => __( 'Circle', 'gppro' ),
			'square'                => __( 'Square', 'gppro' ),
			'decimal'               => __( 'Decimal', 'gppro' ),
			'decimal-leading-zero'  => __( 'Decimal (leading zero)', 'gppro' ),
			'lower-roman'           => __( 'Roman (lower)', 'gppro' ),
			'upper-roman'           => __( 'Roman (upper)', 'gppro' ),
			'lower-greek'           => __( 'Greek (lower)', 'gppro' ),
			'lower-latin'           => __( 'Latin (lower)', 'gppro' ),
			'upper-latin'           => __( 'Latin (upper)', 'gppro' ),
			'armenian'              => __( 'Armenian', 'gppro' ),
			'georgian'              => __( 'Georgian', 'gppro' ),
			'lower-alpha'           => __( 'Alpha (lower)', 'gppro' ),
			'upper-alpha'           => __( 'Alpha (upper)', 'gppro' ),
			'none'                  => __( 'None', 'gppro' )
		);

		// return the data, filtered
		return apply_filters( 'gppro_default_css_list_styles', $items );
	}

	/**
	 * build text alignment list
	 *
	 * @return array|string $items
	 *
	 */
	public static function text_alignments() {

		// set the data array
		$items  = array(
			'left'      => __( 'Left', 'gppro' ),
			'center'    => __( 'Center', 'gppro' ),
			'right'     => __( 'Right', 'gppro' ),
			'justify'   => __( 'Justified', 'gppro' ),
			'inherit'   => __( 'Inherit', 'gppro' )
		);

		// return the data, filtered
		return apply_filters( 'gppro_default_css_text_alignments', $items );
	}

	/**
	 * build text transform list
	 *
	 * @return array|string $items
	 *
	 */
	public static function text_transforms() {

		// set the array
		$items  = array(
			'none'          => __( 'Normal', 'gppro' ),
			'uppercase'     => __( 'UPPERCASE', 'gppro' ),
			'lowercase'     => __( 'lowercase', 'gppro' ),
			'capitalize'    => __( 'Capitalize First', 'gppro' ),
			'inherit'       => __( 'Inherit', 'gppro' )
		);

		// return the data, filtered
		return apply_filters( 'gppro_default_css_text_transforms', $items );
	}

	/**
	 * build text decorations list
	 *
	 * @return array|string $items
	 *
	 */
	public static function text_decorations() {

		// set the data array
		$items  = array(
			'none'          => __( 'None', 'gppro' ),
			'underline'     => __( 'Underline', 'gppro' ),
			'overline'      => __( 'Overline', 'gppro' ),
			'line-through'  => __( 'Line Through', 'gppro' ),
		);

		// return the data, filtered
		return apply_filters( 'gppro_default_css_text_decorations', $items );
	}

	/**
	 * build CSS positioning list
	 *
	 * @return array|string $items
	 *
	 */
	public static function css_positions() {

		// set the data array
		$items  = array(
			'relative'  => __( 'Relative', 'gppro' ),
			'absolute'  => __( 'Absolute', 'gppro' ),
			'static'    => __( 'Static', 'gppro' ),
			'fixed'     => __( 'Fixed', 'gppro' ),
			'inherit'   => __( 'Inherit', 'gppro' ),
		);

		// return the data, filtred
		return apply_filters( 'gppro_default_css_position', $items );
	}

	/**
	 * Insert another array into an associative array after the supplied key
	 *
	 * @param string $key The key of the array you want to pivot around
	 * @param array $source_array The 'original' source array
	 * @param array $insert_array The 'new' associative array to merge in by the key
	 *
	 * @return array $modified_array
	 */
	public static function array_insert_after( $key, $source_array, $insert_array ) {
		return self::array_splice_key( $key, $source_array, $insert_array );
	}

	/**
	 * Insert another array into an associative array before the supplied key
	 *
	 * @param string $key The key of the array you want to pivot around
	 * @param array $source_array The 'original' source array
	 * @param array $insert_array The 'new' associative array to merge in by the key
	 *
	 * @return array $modified_array
	 */
	public static function array_insert_before( $key, $source_array, $insert_array ) {
		return self::array_splice_key( $key, $source_array, $insert_array, 0 );
	}

	/**
	 * Insert another array into an associative array around a given key
	 *
	 * @param string $key The key of the array you want to pivot around
	 * @param array $source_array The 'original' source array
	 * @param array $insert_array The 'new' associative array to merge in by the key
	 * @param int $direction Where to insert the new array relative to given $position by $key
	 *   Allowed values: positive or negative numbers - default is 1 (insert after $key), use 0 to insert before
	 *
	 * @return array $modified_array
	 */
	public static function array_splice_key( $key, $source_array, $insert_array, $direction = 1 ) {

		// if for some reason we didn't get two arrays, bail
		if ( ! is_array( $source_array ) || ! is_array( $insert_array ) ) {
			return;
		}

		$found = array_search( $key, array_keys( $source_array ) );
		if ( false === $found ) {
			return $source_array + $insert_array;
		}
		$position = $found + $direction;

		// setup the return with the source array
		$modified_array = $source_array;

		if ( count( $source_array ) < $position && $position !== 0 ) {
			// push one or more elements onto the end of array
			array_push( $modified_array, $insert_array );
		} else if ( $position < 0 ){
			// prepend one or more elements to the beginning of an array
			array_unshift( $modified_array, $insert_array );
		} else {
			$modified_array = array_slice( $source_array, 0, $position, true ) +
	            $insert_array +
	            array_slice( $source_array, $position, NULL, true );
		}
		return $modified_array;
	}

	/**
	 * Global filter to enable rem support
	 *
	 * Disabled by default
	 *
	 * @since 1.2.5
	 * @return boolean
	 */
	public static function rems_enabled() {
		return apply_filters( 'gppro_enable_rems', false );
	}

	/**
	 * return a CSS class for inline items to apply CSS
	 *
	 *
	 * @since 1.2.5
	 * @return string
	 */
	public static function get_inline_css_class( $count = 0 ) {

		// set a default
		$class	= 'gppro-inline-item';

		// a single
		if ( $count <= 1 ) {
			$class	= 'gppro-inline-single';
		}

		// a double
		if ( $count == 2 ) {
			$class	= 'gppro-inline-double';
		}

		// a triple
		if ( $count == 3 ) {
			$class	= 'gppro-inline-triple';
		}

		// more than three? we ain't doing inline
		if ( $count > 3 ) {
			$class	= 'gppro-inline-break';
		}

		// send it back
		return $class;
	}

	/**
	 * check the preview URL being provided and
	 * ensure it has the proper scheme
	 *
	 * @since 1.3.8
	 * @return string
	 */
	public static function check_preview_url_scheme( $url = '' ) {

		// strip the existing scheme off the link
		$clean  = str_replace( array( 'http://', 'https://' ), '', $url );

		// check for SSL
		$scheme = is_ssl() ? 'https' : 'http';

		// return the URL with the appropriate scheme
		return esc_url( $scheme . '://' . $clean );
	}

	/**
	 * get an option from the database and return a value
	 *
	 * @param  string $name      the name of the option in the DB
	 * @param  string $key       an optional key name for serialized data
	 * @param  string $default   a default value to return
	 *
	 * @return mixed             the value if found, the default, or null
	 */
	public static function get_single_option( $name = '', $key = '', $default = '' ) {

		// first attempt to fetch the option
		$option = get_option( $name, $default );

		// if we have nothing, return nothing
		if ( empty( $option ) && empty( $default ) ) {
			return false;
		}

		// return the whole item if no key requested
		if ( ! empty( $option ) && empty( $key ) ) {
			return $option;
		}

		// if we requested a key and have it, return it
		if ( ! empty( $key ) && ! empty( $option[$key] ) ) {
			return $option[$key];
		}

		// nothing. return
		return false;
	}

	/**
	 * set the buildtime for the CSS file
	 *
	 * @return [type] [description]
	 */
	public static function set_css_buildtime() {

		// we don't, so set the time
		$stamp  = ! function_exists( 'current_time' ) ? time() : current_time( 'timestamp' );

		// update it
		GP_Pro_Utilities::update_single_option( 'gppro-buildtime', $stamp );

		// return it
		return $stamp;
	}

	/**
	 * Fetch the stored buildtime for the CSS file and return it unless a format is given.
	 *
	 * @param  string $format  Optional timestamp formatting.
	 *
	 * @return string $stamp   The timestamp (numeric or formatted).
	 */
	public static function get_css_buildtime( $format = '' ) {

		// If we don't have a stored timestamp, set it first.
		if ( false === $stamp = self::get_single_option( 'gppro-buildtime', false, false ) ) {
			$stamp  = self::set_css_buildtime();
		}

		// If just the stamp was requested, return it.
		if ( empty( $format ) ) {
			return $stamp;
		}

		// Return the formatted timestamp.
		return date( apply_filters( 'gppro_timestamp_format', $format ) , $stamp );
	}

	/**
	 * remove top level items from a section array
	 *
	 * @param  array  $sections  the current section items
	 * @param  array  $settings  the items to be removed
	 *
	 * @return array  $sections  the remaining section items
	 */
	public static function remove_settings_from_section( $sections = array(), $settings = array() ) {

		// bail with no sections or setting items
		if ( empty( $sections ) || empty( $settings ) ) {
			return $sections;
		}

		// loop the setting item array
		foreach ( (array) $settings as $setting ) {

			// check for the setting itself
			if ( ! isset( $sections[ $setting ] ) ) {
				continue;
			}

			// unset the setting
			unset( $sections[ $setting ] );
		}

		// return the sections
		return $sections;
	}

	/**
	 * remove pieces of the settings array from a single setting item
	 *
	 * @param  array  $sections  the current section items
	 * @param  string $setting   the key name of the setting item to remove pieces from
	 * @param  array  $pieces    the pieces to remove
	 *
	 * @return array  $sections  the remaining section items
	 */
	public static function remove_items_from_settings( $sections = array(), $setting = '', $pieces = array() ) {

		// bail with no sections, setting, or pieces
		if ( empty( $sections ) || empty( $setting ) || empty( $pieces ) ) {
			return $sections;
		}

		// bail with no item or if the item has no data
		if ( ! isset( $sections[ $setting ]['data'] ) ) {
			return $sections;
		}

		// loop the pieces array
		foreach ( (array) $pieces as $piece ) {

			// check for the setting item itself
			if ( ! isset( $sections[ $setting ]['data'][ $piece ] ) ) {
				continue;
			}

			// unset the setting item
			unset( $sections[ $setting ]['data'][ $piece ] );
		}

		// return the sections
		return $sections;
	}

	/**
	 * remove pieces of the data array from a single item
	 *
	 * @param  array  $sections  the current section items
	 * @param  string $setting   the key name of the setting item to remove pieces from
	 * @param  string $item      the key name of the item inside the setting to remove pieces from
	 * @param  array  $pieces    the pieces to remove
	 *
	 * @return array  $sections  the remaining section items
	 */
	public static function remove_data_from_items( $sections = array(), $setting = '', $item = '', $pieces = array() ) {

		// bail with no sections, setting, item, or pieces
		if ( empty( $sections ) || empty( $setting ) || empty( $item ) || empty( $pieces ) ) {
			return $sections;
		}

		// bail with no item or if the item has no data
		if ( ! isset( $sections[ $setting ]['data'][ $item ] ) ) {
			return $sections;
		}

		// loop the pieces array
		foreach ( (array) $pieces as $piece ) {

			// check for the setting item itself
			if ( ! isset( $sections[ $setting ]['data'][ $item ][ $piece ] ) ) {
				continue;
			}

			// unset the setting item
			unset( $sections[ $setting ]['data'][ $item ][ $piece ] );
		}

		// return the sections
		return $sections;
	}

	/**
	 * take the current settings and add them to the backup
	 *
	 */
	public static function set_settings_backup() {

		// fetch the current options
		$data   = get_option( 'gppro-settings', '' );

		// back up current
		add_option( 'gppro-settings-backup', $data, null, 'no' );
	}

	/**
	 * purge the options stored by DPP
	 *
	 * @return void
	 */
	public static function purge_options( $active = true ) {

		// our core options
		delete_option( 'gppro_expiration_data' );
		delete_option( 'gppro_core_active' );
		delete_option( 'gppro_core_config_key' );

		// delete the active flag if requested
		if ( ! empty( $active ) ) {
			delete_option( 'gppro_plugin_active' );
		}
	}

	/**
	 * purge the actual settings stored by DPP
	 *
	 * @return void
	 */
	public static function purge_settings() {
		delete_option( 'gppro-settings' );
		delete_option( 'gppro-webfont-alert' );
		delete_option( 'gppro-custom-css' );
		delete_option( 'gppro-user-preview-url' );
		delete_option( 'gppro-user-preview-type' );
		delete_option( 'gppro-site-favicon-file' );
		delete_option( 'gppro-buildtime' );
	}

	/**
	 * purge the transients stored by DPP
	 *
	 * @return void
	 */
	public static function purge_transients() {
		delete_transient( 'gppro_core_license_check' );
		delete_transient( 'gppro_core_license_verify' );
		delete_transient( 'gppro_check_file_access' );
	}

	/**
	 * Get a URL for one of the forms on the admin.
	 *
	 * @param  string $action  An optional query string action to include.
	 *
	 * @return string $link    The URL created.
	 */
	public static function get_form_url( $action = '' ) {

		// Get my base link for the DPP admin
		$base   = menu_page_url( 'genesis-palette-pro', 0 );

		// If we have no action string, just return the base.
		if ( empty( $action ) ) {
			return $base;
		}

		// Set up the link with the action arg.
		$link  = add_query_arg( 'action', esc_attr( $action ), $base );

		// Return the link setup.
		return apply_filters( 'gppro_form_url', $link, $action );
	}


// end class
}

// end exists check
}
