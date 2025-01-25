<?php
/*
Plugin Name: افزونه دکمه سفارش جدید برای محصولات تمام شده
Description: این پلاگین به محصولاتی که موجودی آنها تمام شده است، یک دکمه اضافه می‌کند که کاربر را به صفحه خاصی هدایت می‌کند.
Version: 1.0
Author: Mahdi Safari
Text Domain: custom-out-of-stock-button
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! function_exists( 'is_plugin_active' ) ) {
    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
    add_action( 'admin_notices', 'custom_out_of_stock_button_woocommerce_notice' );
    return;
}

function custom_out_of_stock_button_woocommerce_notice() {
    ?>
    <div class="notice notice-error">
        <p><?php _e( 'پلاگین "دکمه سفارش جدید برای محصولات تمام شده" نیاز به ووکامرس دارد. لطفاً ووکامرس را نصب و فعال کنید.', 'custom-out-of-stock-button' ); ?></p>
    </div>
    <?php
}

function enqueue_custom_scripts() {
    wp_enqueue_script( 'jquery' );
}
add_action( 'wp_enqueue_scripts', 'enqueue_custom_scripts' );

function add_custom_button_with_javascript() {
    if ( ! is_product() ) {
        return;
    }

    global $product;

    if ( ! $product->is_in_stock() ) {
        $redirect_url = 'https://microwaveelectronic.com/%D8%AB%D8%A8%D8%AA-%D8%B3%D9%81%D8%A7%D8%B1%D8%B4-3/';

        $button_text = __( 'ثبت سفارش جدید', 'custom-out-of-stock-button' );

        ?>
        <script type="text/javascript">
            jQuery(document).ready(function($) {
                // انتخاب دکمه "افزودن به سبد خرید"
                var addToCartButton = $('a.ajax_add_to_cart[data-product_id="<?php echo $product->get_id(); ?>"]');

                if (addToCartButton.length) {
                    var newButton = '<a href="<?php echo esc_url( $redirect_url ); ?>" class="button custom-out-of-stock-button"><?php echo esc_html( $button_text ); ?></a>';
                    addToCartButton.after(newButton);
                }
            });
        </script>
        <?php
    }
}
add_action( 'wp_footer', 'add_custom_button_with_javascript' );

function add_custom_button_styles() {
    if ( ! is_product() ) {
        return;
    }
    ?>
    <style>
        body.single-product .custom-out-of-stock-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #ff0000;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 10px;
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            cursor: pointer;
            border: none;
            transition: background-color 0.3s ease;
        }

        body.single-product .custom-out-of-stock-button:hover {
            background-color: #cc0000;
        }

        body.single-product .custom-out-of-stock-button {
            margin-left: 10px; /* فاصله از دکمه "افزودن به سبد خرید" */
        }
    </style>
    <?php
}
add_action( 'wp_head', 'add_custom_button_styles' );
