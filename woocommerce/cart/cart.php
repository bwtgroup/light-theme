<?php
/**
 * Custom Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.3.8
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

wc_print_notices(); ?>
<h2 class="text-center">Your Shopping Bag</h2>
<h4 class="text-center"><i>Items reserved for limited time only</i></h4>
<?php do_action( 'woocommerce_before_cart' ); ?>

<form action="<?php echo esc_url( wc_get_cart_url() ); ?>" id="cart-table" method="post" xmlns="http://www.w3.org/1999/html">

<?php do_action( 'woocommerce_before_cart_table' ); ?>

<table class="shop_table shop_table_responsive cart" cellspacing="0">
	<thead>
		<tr>

			<th class="product-thumbnail">&nbsp;</th>
			<th class="product-name"><?php _e( 'Product', 'light-store' ); ?></th>
			<th class="product-price"><?php _e( 'Price', 'light-store' ); ?></th>
			<th class="product-quantity"><?php _e( 'QTY', 'light-store' ); ?></th>
			<th class="product-subtotal"><?php _e( 'Amount', 'light-store' ); ?></th>
			<th class="product-remove">&nbsp;</th>
		</tr>
	</thead>
	<tbody>
		<?php do_action( 'woocommerce_before_cart_contents' ); ?>

		<?php
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
			$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

			if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
				$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
				?>
				<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

					<td class="product-thumbnail">
						<?php
							$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

							if ( ! $product_permalink ) {
								echo $thumbnail;
							} else {
								printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail );
							}
						?>
					</td>

					<td class="product-name" data-title="<?php _e( 'Product', 'light-store' ); ?>">
						<?php
							if ( ! $product_permalink ) {
								echo apply_filters( 'woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key ) . '&nbsp;';
							} else {
								echo apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_title() ), $cart_item, $cart_item_key );
							}

							// Meta data
							echo WC()->cart->get_item_data( $cart_item );

							// Backorder notification
							if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
								echo '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'light-store' ) . '</p>';
							}
						?>
					</td>

					<td class="product-price" data-title="<?php _e( 'Price', 'light-store' ); ?>">
						<?php
							echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
						?>
					</td>

					<td class="product-quantity" data-title="<?php _e( 'Qty', 'light-store' ); ?>">
						<?php
							if ( $_product->is_sold_individually() ) {
								$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
							} else {
								$product_quantity = woocommerce_quantity_input( array(
									'input_name'  => "cart[{$cart_item_key}][qty]",
									'input_value' => $cart_item['quantity'],
									'max_value'   => $_product->backorders_allowed() ? '' : $_product->get_stock_quantity(),
									'min_value'   => '1'
								), $_product, false );
							}

							echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item );
						?>
					</td>

					<td class="product-subtotal" data-title="<?php _e( 'Total', 'light-store' ); ?>">
						<?php
							echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
						?>
					</td>

					<td class="product-remove">
						<?php
						echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
							'<a href="%s" class="remove ajax-remove-cart-item" title="%s" data-product_id="%s" data-product_sku="%s" data-item-key="%s">&times;</a>',
							esc_url( WC()->cart->get_remove_url( $cart_item_key ) ),
							__( 'Remove this item', 'light-store' ),
							esc_attr( $product_id ),
							esc_attr( $_product->get_sku() ),
							$cart_item_key
						), $cart_item_key );
						?>

					</td>
				</tr>
				<?php
			}
		}

		do_action( 'woocommerce_cart_contents' );
		?>

		<?php do_action( 'woocommerce_after_cart_contents' ); ?>
	</tbody>
</table>

<div class="cart-collaterals">
	<div class="cart_totals <?php if ( WC()->customer->has_calculated_shipping() ) echo 'calculated_shipping'; ?>">
		<?php do_action( 'woocommerce_before_cart_totals' ); ?>

		<strong>
			<?php _e( 'Subtotal: ', 'light-store' ); ?>
			<span class="sub-total"><?php wc_cart_totals_subtotal_html(); ?></span>
		</strong>

		<?php do_action( 'woocommerce_after_cart_totals' ); ?>
	</div>

	<div class="wc-proceed-to-checkout">

		<a href="<?php echo esc_url( get_home_url() ) ;?>" class="color-black pull-left return">
			<u><?php echo __( 'Continue Shopping', 'light-store' ); ?></u>
		</a>

		<a href="<?php echo esc_url( wc_get_checkout_url() ) ;?>" class="checkout-button button alt wc-forward pull-right">
			<?php echo __( 'Order now', 'light-store' ); ?>
		</a>
	</div>
</div>

<?php if ( wc_coupons_enabled() ) { ?>
	<div class="coupon">

		<label for="coupon_code"><?php _e( 'Coupon:', 'light-store' ); ?></label> <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'light-store' ); ?>" /> <input type="submit" class="button" name="apply_coupon" value="<?php esc_attr_e( 'Apply Coupon', 'light-store' ); ?>" />

		<?php do_action( 'woocommerce_cart_coupon' ); ?>
	</div>
<?php } ?>


<?php do_action( 'woocommerce_cart_actions' ); ?>

<?php wp_nonce_field( 'woocommerce-cart' ); ?>

<?php do_action( 'woocommerce_after_cart_table' ); ?>

</form>

<?php do_action( 'woocommerce_after_cart' ); ?>
