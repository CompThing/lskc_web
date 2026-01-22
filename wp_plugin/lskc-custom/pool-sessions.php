<?php
/**
 * Pool Session Summary Query
 *
 * This based off logic in old Pool Session Summary Page template
 */

get_header(); ?>

<?php
     function num_products_in_order( $product_id, $order)
     {
        $count = 0;
         
        // Iterating through each WC_Order_Item_Product objects
        foreach ($order->get_items() as $item_key => $item_values)
        {
            if ($item_values->get_product_id() == $product_id)
            {
               $count = $item_values->get_quantity() ;
            }
        }
        return $count;
     }
    
     function retrieve_orders_ids_from_a_product_id( $product_id )
     {
        global $wpdb;
    
        # Requesting All defined statuses Orders IDs for a defined product ID
        $orders_ids = $wpdb->get_col( "
            SELECT DISTINCT woi.order_id
            FROM {$wpdb->prefix}woocommerce_order_itemmeta as woim, 
                 {$wpdb->prefix}woocommerce_order_items as woi, 
                 {$wpdb->prefix}posts as p
            WHERE  woi.order_item_id = woim.order_item_id
            AND woi.order_id = p.ID
            AND woim.meta_key LIKE '_product_id'
            AND woim.meta_value LIKE '$product_id'
            ORDER BY woi.order_item_id DESC"
        );
        //$orders_statuses = "'wc-completed', 'wc-processing', 'wc-on-hold'";
        //    AND p.post_status IN ( $orders_statuses )
        // Return an array of Orders IDs for the given product ID
        return $orders_ids;
     }
 ?>
 
	<?php if(have_posts()) while(have_posts()): the_post(); ?>
		
		<div class="hero">
			<?php $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'single-post-thumbnail'); ?>
			<?php if($image): ?>
				<img src="<?php echo $image[0]; ?>" alt="hero" />
			<?php else: ?>
				<img src="/wp-content/themes/lskc/images/hero.png" alt="hero" />
			<?php endif; ?>
		</div>
		
		<div class="body">
			<div class="container">
				<div class="row">
					<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
						<div class="content">
						    
						    
<?php if(is_user_logged_in()): ?>
    <?php $user = wp_get_current_user();
    if((in_array( 'administrator', (array) $user->roles ) 
      ||in_array( 'editor', (array) $user->roles )
	  ||in_array( 'shop_manager', (array) $user->roles ))): ?>


    	<h1><?php the_title(); ?></h1>
    	<?php the_content(); ?>
    	
  
<?php 
							
$product_id = trim($_GET["product_id"]);
$product = wc_get_product( $product_id );
$valid_product_id = true;
if (!$product)
{
    $valid_product_id = false;
	$product = wc_get_product( $product_id );
}
							
$display_range = trim($_GET["display_from"]);
$show_all = false;
if ($display_range == "all")
{
	$show_all = true;
}
					
$include_cancellations_string = trim($_GET["include_cancellations"]);		
$include_cancellations = false;
if ($include_cancellations_string)
{
	$include_cancellations = true;
}
						
if ($valid_product_id)							
{
	$product_is_variable = $product->is_type('variable');
	$product_variants = [];
	$product_is_booking = $product->is_type('booking');
	$today_date = date('Y-m-d H:i:s', time());

	// We get all the Orders for the given product ID in an arrray
	$orders_ids_array = retrieve_orders_ids_from_a_product_id( $product_id );
	echo '<h1>' . $product->get_title() . '</h1>';
	echo '<p> URL:';
	echo '<a href="' . esc_url( get_permalink( $product_id  ) ) . '">' . esc_url( get_permalink( $product_id  ) ) . '</a>';
	echo '</p>';
	
	// Bookable product
	// Work through and aggregate orders for a specific date
	$bookings_by_date = array();
	$bookings_by_user = array();
	$date_summary = array();

	foreach ($orders_ids_array as $o)
	{
		$order = wc_get_order( $o );
		$booking_ids  = $booking_ids = WC_Booking_Data_Store::get_booking_ids_from_order_id( $o );
		// If we have bookings go through each and update the status.
		if ( is_array( $booking_ids ) && count( $booking_ids ) > 0 )
		{
			foreach ( $booking_ids as $booking_id )
			{
				$booking = get_wc_booking( $booking_id );
				$start_date = $booking->get_start_date( 'Y-m-d H:i:s' );
				
				if ($show_all || ($start_date > $today_date))
				{
					$booking_user = $booking->get_order()->get_user_id();
					if ($booking->status != 'trash')
					{
						if (!array_key_exists($start_date, $bookings_by_date))
						{
							$display_date = $booking->get_start_date('d-m-Y ', 'H:i');
							$num_bookings = 0;
							$date_summary[$start_date] = array('display_date' => $display_date, 'num_bookings' => $num_bookings);
							$bookings_by_date[$start_date] = array();
						}
						if ( $include_cancellations || !in_array($booking->status, array( 'trash', 'failed', 'cancelled' )))
						{
							$date_summary[$start_date]['num_bookings'] = $date_summary[$start_date]['num_bookings'] + 1;
						}
						array_push($bookings_by_date[$start_date], array('booking' => $booking, 'order' => $order));
						if (!array_key_exists($booking_user, $bookings_by_user))
						{
							$bookings_by_user[$booking_user] = array();
						}			
						array_push($bookings_by_user[$booking_user], $booking);
					}
				}
			}
		}
	}

	if ($valid_product_id && sizeof($bookings_by_date) == 0)
	{
		echo '<p>No bookings received</p>';
	}
	
	ksort($date_summary);
	echo '<h2>Availablity Summary</h2>';
	echo '<table id="pool_session_summary" class="table table-striped table-hover">';
    echo '<thead><th>Date</th><th>Num Booked</th></tr></thead>';
	echo '<tbody>';
	foreach ($date_summary as $bk_date => $summary)
	{
		echo '<tr>';
		echo '<td>' . $summary['display_date'] . '</td>';
		echo '<td>' . $summary['num_bookings'] . '</td>';
		echo '</tr>';
	}
	echo '</tbody>';
	echo '</table>';
	
	// Now output a table for each date, order by date ascending
	ksort($bookings_by_date);
	foreach ($bookings_by_date as $bk_date => $booking_array)
	{
		echo '<h2>' . $date_summary[$bk_date]['display_date'] . '</h2>';

		echo '<table id="pool_session_summary" class="table table-striped table-hover">';
		echo '<thead><th>Order ID</th><th>First Name</th><th>Last Name</th><th>Email</th><th>Phone</th><th>Payment Method</th><th>Order Status</th><th>Booking Status</th><th>Edit Order</th></tr>';
		echo '<tbody>';
		foreach ($booking_array as $booking_obj)
		{
			$order = $booking_obj['order'];
			$order_data = $order->get_data();
			$booking = $booking_obj['booking'];
			$phone_no = get_field('mobile_number', 'user_'.$order->get_user_id());
			$person_type = ( 0 < $id ) ? get_the_title( $id ) : __( 'Person(s)', 'woocommerce-bookings' );
			$payment_method = $order_data['payment_method'];
			if ($payment_method == 'wc-booking-gateway')
			{
				$payment_method = ''; // Blank if not confirmed & paid
			}
			
			echo '<tr>';
			echo '<td>' . $order_data['id'] . '</td>';
			echo '<td>' . $order_data['billing']['first_name'] . '</td>';
			echo '<td>' . $order_data['billing']['last_name'] . '</td>';
			echo '<td>' . $order_data['billing']['email'] . '</td>';
			echo '<td>' . $phone_no . '</td>';
			echo '<td>' . $payment_method . '</td>';
			echo '<td>' . $order->status . '</td>';
			echo '<td>' . $booking->status . '</td>';
			echo '<td><a href="'.$order->get_edit_order_url().'">Edit Order</a></td>';
			echo '</tr>';
		}
		echo '</tbody>';
		echo '</table>';
	}
	
	echo '<h2>User Summary</h2>';
	echo '<table id="pool_user_summary" class="table table-striped table-hover">';
	echo '<thead><th>First Name</th><th>Last Name</th><th>Email</th><th>Mobile</th><th>Total Sessions</th></thead>';
	echo '<tbody> ';
	foreach ($bookings_by_user as $bk_user => $booking_array)
	{
	    $phone_no = get_field('mobile_number', 'user_'.$bk_user);
	    if (empty($phone_no)) {$phone_no = get_field('phone_number', 'user_'.$bk_user);}
	    $user_details = get_userdata($bk_user);
		echo '<tr>';
	    echo '<td>' . $user_details->first_name . '</td>';
	    echo '<td>' . $user_details->last_name . '</td>';
	    echo '<td>' . $user_details->user_email . '</td>';
	    echo '<td>' . $phone_no . '</td>';
	    echo '<td>' . count($booking_array) . '</td>';
		echo '</tr>';
	}
	echo '</tbody> ';
	echo '</table>';
}
else
{
	if ($product_id == "")
	{
		echo '<p>No session selected, choose from menu</p>';
	}
	else
	{
		echo '<p>Invalid product selected, choose valid product from meu</p>';
	}
}


?> 

    <?php else: ?>
	    <h1>Admins Only</h1>
	    <p>Sorry, you must be a Lothian Sea Kayak Club administrator to view this page.</p>
    <?php endif; ?>
    
<?php else: ?>
	<h1>Members Only</h1>
	<p>Sorry you must be a Lothian Sea Kayak Club member to view this page.</p>
<?php endif; ?>


						</div>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
							<div class="sidebar">
								<?php if(is_user_logged_in()): ?>
									<div class="widget">
									<h3>Pool Sessions</h3>
									<?php
									   $args = array( 'post_type' => 'product', 'posts_per_page' => -1, 'product_cat' => 'pool', 'orderby' => 'modified' , 'date_query' => array(
        array(
            'after'     => array('year'  => date('Y', strtotime('-2 year')),
            'before'    => array('year'  => date('Y', strtotime('+2 year'))),
            'inclusive' => true,
        ),
    )));
                                       $loop = new WP_Query( $args );
									   echo '<ul class="menu">';
                                       while ( $loop->have_posts() ) {
										   $loop->the_post(); global $product;
										   echo '<li class="menu-item"><a href="https://www.lskc.org/pool-summary/?display_from=all&product_id=' . $loop->post->ID . '">' . $loop->post->post_title . '</a></li>';
									   }
									   echo '</ul>';
									?>
								</div>
									</div>
								<?php else: ?>
									<div class="widget">
										<h3>Members Login</h3>
										<p>If you are an existing member of the Lothian Sea Kayak Club you can login to your 
										portal below:</p>
										<?php
											$args = array(
												'echo'           => true,
												'remember'       => false,
											    'redirect'       => site_url( $_SERVER['REQUEST_URI'] ),
												'form_id'        => 'loginform',
												'id_username'    => 'user_login',
												'id_password'    => 'user_pass',
												'id_remember'    => 'rememberme',
												'id_submit'      => 'wp-submit',
												'label_username' => __( 'Email Address' ),
												'label_password' => __( 'Password' ),
												'label_remember' => __( 'Remember Me' ),
												'label_log_in'   => __( 'Log In' ),
												'value_username' => '',
												'value_remember' => false
											);
											wp_login_form($args);
										?>
									</div>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php endwhile; ?>
	
<?php get_footer(); ?>
