<?php
/**
 * Plugin Name: WooCommerce Product Slug Export & Import
 * Plugin URI:  https://github.com/akarimg/Woo-Slug-Export-Import
 * Description: Enables exporting and importing product slugs during WooCommerce product data export/import (CSV).
 * Version:     1.0.0
 * Author:      Abdelkarim Guettaf
 * Author URI:  karim.wahaproject.org
 * License:     MPL-2.0
 * License URI: https://www.mozilla.org/en-US/MPL/2.0/
 * Text Domain: woocommerce-product-slug-export-import
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

/**
 * Add the "Slug" column for product export and handle import.
 */
function woocommerce_product_slug_export_import() {

  // Add "Slug" column during export
  add_filter( 'woocommerce_product_export_column_names', 'add_slug_export_column' );
  add_filter( 'woocommerce_product_export_product_default_columns', 'add_slug_export_column' );

  // Populate "Slug" data during export
  add_filter( 'woocommerce_product_export_product_column_slug', 'add_export_product_slug', 10, 2 );

  // Import mapping option for "Slug"
  add_filter( 'woocommerce_csv_product_import_mapping_options', 'add_slug_import_option' );

  // Default mapping for "Slug" during import
  add_filter( 'woocommerce_csv_product_import_mapping_default_columns', 'add_default_slug_column_mapping' );

  // Process and set slug during import
  add_filter( 'woocommerce_product_import_pre_insert_product_object', 'process_import_product_slug', 10, 2 );
}

add_action( 'plugins_loaded', 'woocommerce_product_slug_export_import' );

// Helper functions (same as before)

function add_slug_export_column( $product_export_columns ) {
  $product_export_columns['slug'] = 'Slug';
  return $product_export_columns;
}

function add_export_product_slug( $product_slug, $product ) {
  $product_slug = $product->get_slug();
  return $product_slug;
}

function add_slug_import_option( $import_mapping_options ) {
  $import_mapping_options['slug'] = 'Slug';
  return $import_mapping_options;
}

function add_default_slug_column_mapping( $default_import_column_mappings ) {
  $default_import_column_mappings['Slug'] = 'slug';
  return $default_import_column_mappings;
}

function process_import_product_slug( $product_object, $import_data ) {
  if ( ! empty( $import_data['slug'] ) ) {
    $product_slug = sanitize_title($import_data['slug']);
    $product_object->set_slug( $product_slug );
  }
  return $product_object;
}

