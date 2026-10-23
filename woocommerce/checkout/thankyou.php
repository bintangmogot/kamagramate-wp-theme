<?php
/**
 * Thankyou page - Waklert Australia Modern Redesign
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.1.0
 *
 * @var WC_Order $order
 */

defined( 'ABSPATH' ) || exit;

// Dynamic options with rock-solid Australian defaults
$contact_email = function_exists( 'get_field' ) ? get_field( 'contact_email', 'option' ) : '';
if ( empty( $contact_email ) || strpos( $contact_email, 'support@' ) !== false || strpos( $contact_email, 'modafinil' ) !== false ) {
    $contact_email = 'orders@waklertaustralia.com.au';
} else {
    $contact_email = sanitize_email( $contact_email );
}

$phone_display = function_exists( 'get_field' ) ? ( get_field( 'whatsapp_button_text', 'option' ) ?: '0488 841 833' ) : '0488 841 833';
$phone_raw     = preg_replace( '/[^0-9]/', '', $phone_display );
$whatsapp_url  = 'https://wa.me/61' . ltrim( $phone_raw, '0' );

// Bank account details
$bacs_bank     = 'Commonwealth Bank';
$bacs_name     = 'Steven Waldberg';
$bacs_bsb      = '062 948';
$bacs_acc      = '48880531';
?>

<div class="woocommerce-order max-w-3xl mx-auto py-8 sm:py-12 px-4 sm:px-6">

	<?php if ( $order ) : ?>

		<?php do_action( 'woocommerce_before_thankyou', $order->get_id() ); ?>

		<?php if ( $order->has_status( 'failed' ) ) : ?>

            <!-- Order Failed State -->
            <div class="text-center bg-card border border-destructive/20 rounded-3xl p-8 sm:p-12 shadow-sm mb-10">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-destructive/10 text-destructive mb-6 ring-8 ring-destructive/5">
                    <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                </div>
                <h1 class="font-heading text-3xl sm:text-4xl font-extrabold text-foreground mb-3"><?php esc_html_e( 'Order Payment Failed', 'woocommerce' ); ?></h1>
                <p class="text-muted-foreground text-base sm:text-lg max-w-xl mx-auto mb-8"><?php esc_html_e( 'Unfortunately your order payment could not be completed. Please re-attempt your payment or select direct bank transfer.', 'woocommerce' ); ?></p>
                
                <div class="flex flex-wrap items-center justify-center gap-4">
                    <a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="inline-flex items-center justify-center px-8 py-3.5 rounded-full bg-primary text-white font-bold hover:bg-primary-dark transition-all shadow-md hover:shadow-lg"><?php esc_html_e( 'Retry Payment', 'woocommerce' ); ?></a>
                    <?php if ( is_user_logged_in() ) : ?>
                        <a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="inline-flex items-center justify-center px-8 py-3.5 rounded-full border-2 border-border text-foreground font-bold hover:bg-muted transition-all"><?php esc_html_e( 'My Account', 'woocommerce' ); ?></a>
                    <?php endif; ?>
                </div>
            </div>

		<?php else : ?>

            <?php
            $is_bacs = ( $order->get_payment_method() === 'bacs' );
            $order_num = $order->get_order_number();
            $order_total = $order->get_formatted_order_total();
            $email_subject = rawurlencode( 'Payment Receipt - Order #' . $order_num );
            $email_body    = rawurlencode( "Hi,\n\nI have completed the bank transfer for Order #" . $order_num . " (" . wp_strip_all_tags( $order_total ) . ").\n\nPlease find my transaction receipt attached.\n\nThank you,\n" . $order->get_billing_first_name() );
            $mailto_url    = 'mailto:' . esc_attr( $contact_email ) . '?subject=' . $email_subject . '&body=' . $email_body;
            $wa_text       = rawurlencode( 'Hi, here is my payment receipt for Order #' . $order_num . ' (' . wp_strip_all_tags( $order_total ) . ').' );
            $wa_full_url   = $whatsapp_url . '?text=' . $wa_text;
            ?>

            <!-- Hero Header Card -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm font-semibold mb-4 shadow-xs">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <?php echo $is_bacs ? 'Order Placed • Awaiting Bank Transfer' : 'Order Placed Successfully'; ?>
                </div>
                
                <h1 class="font-heading text-3xl sm:text-4xl md:text-5xl font-extrabold text-foreground tracking-tight mb-3">
                    Thank You for Your Order!
                </h1>
                
                <p class="text-muted-foreground text-base sm:text-lg max-w-xl mx-auto leading-relaxed">
                    <?php if ( $is_bacs ) : ?>
                        Your order has been safely received and reserved. Please complete your direct bank transfer below for <strong class="text-foreground">same-day express dispatch</strong>.
                    <?php else : ?>
                        Your order has been received and is now being prepared for express delivery.
                    <?php endif; ?>
                </p>
            </div>

            <!-- Order Key Summary Grid -->
            <div class="bg-card border border-border/80 rounded-2xl sm:rounded-3xl p-5 sm:p-7 shadow-xs mb-8">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6 divide-y md:divide-y-0 md:divide-x divide-border/60">
                    <div class="flex flex-col pt-2 md:pt-0">
                        <span class="text-xs uppercase tracking-wider font-semibold text-muted-foreground mb-1">Order Number</span>
                        <strong class="text-foreground text-lg sm:text-xl font-bold font-mono">#<?php echo esc_html( $order_num ); ?></strong>
                    </div>

                    <div class="flex flex-col pt-3 md:pt-0 md:pl-6">
                        <span class="text-xs uppercase tracking-wider font-semibold text-muted-foreground mb-1">Date</span>
                        <strong class="text-foreground text-base sm:text-lg font-bold"><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></strong>
                    </div>

                    <div class="flex flex-col pt-3 md:pt-0 md:pl-6">
                        <span class="text-xs uppercase tracking-wider font-semibold text-muted-foreground mb-1">Total Due</span>
                        <strong class="text-primary text-xl sm:text-2xl font-extrabold"><?php echo wp_kses_post( $order_total ); ?></strong>
                    </div>

                    <div class="flex flex-col pt-3 md:pt-0 md:pl-6">
                        <span class="text-xs uppercase tracking-wider font-semibold text-muted-foreground mb-1">Payment Method</span>
                        <strong class="text-foreground text-sm sm:text-base font-bold leading-tight"><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></strong>
                    </div>
                </div>

                <?php if ( $order->get_billing_email() ) : ?>
                    <div class="mt-5 pt-4 border-t border-border/60 flex flex-wrap items-center justify-between text-xs sm:text-sm text-muted-foreground gap-2">
                        <div>
                            <span>Confirmation sent to: </span>
                            <strong class="text-foreground"><?php echo esc_html( $order->get_billing_email() ); ?></strong>
                        </div>
                        <span class="inline-flex items-center gap-1.5 text-emerald-700 font-medium">
                            <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            Tax Invoice Generated
                        </span>
                    </div>
                <?php endif; ?>
            </div>

            <?php if ( $is_bacs ) : ?>
                <!-- Commonwealth Bank Transfer Card -->
                <div class="bg-gradient-to-b from-[#FAF5E8]/60 to-[#FFFDF9] border-2 border-[#EAB308]/40 rounded-3xl p-6 sm:p-8 shadow-sm mb-8 relative overflow-hidden">
                    <!-- Brand Top Bar -->
                    <div class="flex flex-wrap items-center justify-between gap-3 pb-5 mb-6 border-b border-[#EAB308]/30">
                        <div class="flex items-center gap-3">
                            <!-- Commonwealth Bank Yellow Diamond Badge -->
                            <div class="w-11 h-11 rounded-xl bg-[#FFCC00] flex items-center justify-center shadow-xs border border-amber-300">
                                <svg class="w-6 h-6 text-[#1A1A1A]" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 2L2 12l10 10 10-10L12 2zm0 4.2L17.8 12 12 17.8 6.2 12 12 6.2z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h2 class="font-heading text-lg sm:text-xl font-extrabold text-[#1A1A1A] leading-tight">Commonwealth Bank</h2>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold bg-[#FFCC00]/40 text-[#854D0E] border border-[#EAB308]/40">Verified</span>
                                </div>
                                <p class="text-xs text-muted-foreground">Official Direct Bank Transfer (EFT / NetBank / PayID)</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-semibold text-muted-foreground uppercase tracking-wider block">Amount to Transfer</span>
                            <span class="text-2xl sm:text-3xl font-extrabold text-foreground font-heading"><?php echo wp_kses_post( $order_total ); ?></span>
                        </div>
                    </div>

                    <!-- Interactive Banking Fields Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 mb-6">
                        
                        <!-- Account Name -->
                        <div class="bg-white/90 border border-border/80 rounded-2xl p-4 flex items-center justify-between gap-3 shadow-xs hover:border-primary/50 transition-colors">
                            <div>
                                <span class="text-[11px] uppercase tracking-wider font-bold text-muted-foreground block mb-0.5">Account Name</span>
                                <span class="text-base sm:text-lg font-bold text-foreground select-all"><?php echo esc_html( $bacs_name ); ?></span>
                            </div>
                            <button type="button" onclick="copyValue('<?php echo esc_js( $bacs_name ); ?>', this)" class="copy-btn inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-border text-xs font-bold text-foreground bg-muted/40 hover:bg-muted transition-all active:scale-95 cursor-pointer">
                                <svg class="w-3.5 h-3.5 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                <span>Copy</span>
                            </button>
                        </div>

                        <!-- Bank Name -->
                        <div class="bg-white/90 border border-border/80 rounded-2xl p-4 flex items-center justify-between gap-3 shadow-xs">
                            <div>
                                <span class="text-[11px] uppercase tracking-wider font-bold text-muted-foreground block mb-0.5">Bank</span>
                                <span class="text-base sm:text-lg font-bold text-foreground"><?php echo esc_html( $bacs_bank ); ?></span>
                            </div>
                            <span class="text-xs font-semibold text-muted-foreground px-2.5 py-1 bg-muted/40 rounded-lg">Australia</span>
                        </div>

                        <!-- BSB -->
                        <div class="bg-white/90 border border-border/80 rounded-2xl p-4 flex items-center justify-between gap-3 shadow-xs hover:border-primary/50 transition-colors">
                            <div>
                                <span class="text-[11px] uppercase tracking-wider font-bold text-muted-foreground block mb-0.5">BSB Code</span>
                                <span class="text-xl sm:text-2xl font-mono font-extrabold text-foreground tracking-wider select-all"><?php echo esc_html( $bacs_bsb ); ?></span>
                            </div>
                            <button type="button" onclick="copyValue('<?php echo esc_js( str_replace(' ', '', $bacs_bsb) ); ?>', this)" class="copy-btn inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-border text-xs font-bold text-foreground bg-muted/40 hover:bg-muted transition-all active:scale-95 cursor-pointer">
                                <svg class="w-3.5 h-3.5 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                <span>Copy</span>
                            </button>
                        </div>

                        <!-- Account Number -->
                        <div class="bg-white/90 border border-border/80 rounded-2xl p-4 flex items-center justify-between gap-3 shadow-xs hover:border-primary/50 transition-colors">
                            <div>
                                <span class="text-[11px] uppercase tracking-wider font-bold text-muted-foreground block mb-0.5">Account Number</span>
                                <span class="text-xl sm:text-2xl font-mono font-extrabold text-foreground tracking-wider select-all"><?php echo esc_html( $bacs_acc ); ?></span>
                            </div>
                            <button type="button" onclick="copyValue('<?php echo esc_js( $bacs_acc ); ?>', this)" class="copy-btn inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-border text-xs font-bold text-foreground bg-muted/40 hover:bg-muted transition-all active:scale-95 cursor-pointer">
                                <svg class="w-3.5 h-3.5 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                <span>Copy</span>
                            </button>
                        </div>

                    </div>

                    <!-- Transfer Reference Warning Box -->
                    <div class="bg-white/95 border border-[#EAB308]/60 rounded-2xl p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-amber-100 text-amber-800 flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            </div>
                            <div>
                                <span class="text-xs uppercase tracking-wider font-extrabold text-foreground block">Transfer Reference / Description:</span>
                                <p class="text-sm text-foreground mb-0">
                                    Please enter only: <strong class="text-primary font-mono text-base font-extrabold">Order #<?php echo esc_html( $order_num ); ?></strong>
                                    <span class="block text-xs text-muted-foreground mt-0.5">Do NOT reference medicine or website names to prevent bank delays.</span>
                                </p>
                            </div>
                        </div>
                        <button type="button" onclick="copyValue('Order <?php echo esc_js( $order_num ); ?>', this)" class="copy-btn inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-xl border border-primary/30 text-xs font-bold text-primary bg-primary-softer hover:bg-primary-soft transition-all active:scale-95 shrink-0 cursor-pointer">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                            <span>Copy Reference</span>
                        </button>
                    </div>
                </div>

                <!-- 3-Step Next Steps Guide & Instant Send CTAs -->
                <div class="bg-[#F0F7FF] border border-[#BDE0FF] rounded-3xl p-6 sm:p-8 shadow-xs mb-8">
                    <h3 class="font-heading text-lg sm:text-xl font-extrabold text-foreground mb-4 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-primary text-white text-xs flex items-center justify-center font-bold">!</span>
                        Payment Instructions & Dispatch Steps
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div class="bg-white/80 border border-blue-100 rounded-2xl p-4">
                            <div class="w-7 h-7 rounded-full bg-primary/10 text-primary font-bold text-xs flex items-center justify-center mb-2">1</div>
                            <h4 class="font-bold text-sm text-foreground mb-1">Make Transfer</h4>
                            <p class="text-xs text-muted-foreground mb-0">Log in to your banking app and transfer <?php echo wp_strip_all_tags( $order_total ); ?> to Commonwealth Bank above.</p>
                        </div>

                        <div class="bg-white/80 border border-blue-100 rounded-2xl p-4">
                            <div class="w-7 h-7 rounded-full bg-primary/10 text-primary font-bold text-xs flex items-center justify-center mb-2">2</div>
                            <h4 class="font-bold text-sm text-foreground mb-1">Send Receipt</h4>
                            <p class="text-xs text-muted-foreground mb-0">Take a screenshot of the completed transfer and email it to <strong class="text-foreground"><?php echo esc_html( $contact_email ); ?></strong>.</p>
                        </div>

                        <div class="bg-white/80 border border-blue-100 rounded-2xl p-4">
                            <div class="w-7 h-7 rounded-full bg-emerald-100 text-emerald-800 font-bold text-xs flex items-center justify-center mb-2">3</div>
                            <h4 class="font-bold text-sm text-foreground mb-1">Fast Dispatch</h4>
                            <p class="text-xs text-muted-foreground mb-0">Once verified, your order is dispatched via Australia Post with tracking sent straight to your email.</p>
                        </div>
                    </div>

                    <!-- Quick Action Buttons -->
                    <div class="pt-2 border-t border-blue-200/60 flex flex-col sm:flex-row items-center justify-between gap-3">
                        <p class="text-xs text-foreground/80 mb-0">Click below to send receipt with pre-filled order details:</p>
                        <div class="flex flex-wrap items-center gap-2.5 w-full sm:w-auto">
                            <a href="<?php echo esc_url( $mailto_url ); ?>" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-full bg-primary text-white text-xs sm:text-sm font-bold hover:bg-primary-dark transition-all shadow-xs hover:shadow-sm w-full sm:w-auto">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                <span>Email Receipt (<?php echo esc_html( $contact_email ); ?>)</span>
                            </a>
                            <a href="<?php echo esc_url( $wa_full_url ); ?>" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-full bg-[#25D366] text-white text-xs sm:text-sm font-bold hover:bg-[#1EBE5D] transition-all shadow-xs hover:shadow-sm w-full sm:w-auto">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.099.824z"/></svg>
                                <span>WhatsApp / SMS Proof</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Australia Post Reassurance Card -->
                <div class="bg-card border border-border/80 rounded-2xl p-5 mb-8 flex flex-col sm:flex-row items-center gap-4 sm:gap-6 shadow-xs">
                    <div class="w-12 h-12 rounded-2xl bg-[#DC2626]/10 text-[#DC2626] flex items-center justify-center shrink-0">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    </div>
                    <div class="flex-1 text-center sm:text-left">
                        <h4 class="font-heading font-extrabold text-foreground text-sm sm:text-base mb-1">Domestic Australia Post Express Dispatch</h4>
                        <p class="text-xs sm:text-sm text-muted-foreground mb-0">Shipped domestically in discreet plain packaging. No medicine or website names appear on the parcel. Delivery typically within 1-3 business days once payment clears.</p>
                    </div>
                    <div class="shrink-0 text-center sm:text-right">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-muted text-foreground">AusPost Express</span>
                    </div>
                </div>

            <?php endif; ?>

            <!-- WooCommerce Hook Outputs (Styled cleanly) -->
            <div class="thank-you-hooks-wrapper">
                <style>
                    /* Hide redundant duplicate plain BACS and old note */
                    .custom-bacs-details,
                    .woocommerce-bacs-bank-details,
                    .shipping-notice-thankyou { 
                        display: none !important; 
                    }
                    /* Modern order items table styling */
                    .woocommerce-order-details {
                        background: #FFFFFF;
                        border: 1px solid #E2E8F0;
                        border-radius: 1.5rem;
                        padding: 1.5rem;
                        margin-bottom: 2rem;
                        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
                    }
                    @media (min-width: 640px) {
                        .woocommerce-order-details { padding: 2rem; }
                    }
                    .woocommerce-order-details h2.woocommerce-order-details__title {
                        font-family: var(--font-heading, inherit);
                        font-size: 1.25rem !important;
                        font-weight: 800 !important;
                        color: #0F172A !important;
                        margin-top: 0 !important;
                        margin-bottom: 1.25rem !important;
                        padding-bottom: 1rem !important;
                        border-bottom: 1px solid #E2E8F0 !important;
                    }
                    .woocommerce-table--order-details {
                        width: 100% !important;
                        border-collapse: collapse !important;
                    }
                    .woocommerce-table--order-details th,
                    .woocommerce-table--order-details td {
                        padding: 0.875rem 0.5rem !important;
                        border-bottom: 1px solid #F1F5F9 !important;
                        font-size: 0.875rem !important;
                    }
                    .woocommerce-table--order-details tfoot th {
                        font-weight: 600 !important;
                        color: #64748B !important;
                        text-align: left !important;
                    }
                    .woocommerce-table--order-details tfoot td {
                        text-align: right !important;
                        font-weight: 700 !important;
                        color: #0F172A !important;
                    }
                    .woocommerce-customer-details {
                        width: 100% !important;
                        margin-top: 2rem !important;
                        margin-bottom: 2rem !important;
                    }
                    /* Customer addresses layout: Force 1 column on mobile, 2 columns on desktop */
                    .woocommerce .woocommerce-customer-details .col2-set,
                    .woocommerce-page .woocommerce-customer-details .col2-set,
                    .woocommerce-customer-details .col2-set,
                    .woocommerce-customer-details .woocommerce-columns--addresses,
                    section.woocommerce-columns--addresses {
                        display: flex !important;
                        flex-direction: column !important;
                        gap: 1.5rem !important;
                        width: 100% !important;
                    }
                    .woocommerce .woocommerce-customer-details .col-1,
                    .woocommerce .woocommerce-customer-details .col-2,
                    .woocommerce-page .woocommerce-customer-details .col-1,
                    .woocommerce-page .woocommerce-customer-details .col-2,
                    .woocommerce .col2-set .col-1,
                    .woocommerce .col2-set .col-2,
                    .woocommerce-page .col2-set .col-1,
                    .woocommerce-page .col2-set .col-2,
                    .woocommerce-customer-details .col-1,
                    .woocommerce-customer-details .col-2,
                    .woocommerce-customer-details .woocommerce-column,
                    div.woocommerce-column--billing-address,
                    div.woocommerce-column--shipping-address {
                        float: none !important;
                        width: 100% !important;
                        max-width: 100% !important;
                        flex: 1 1 100% !important;
                        box-sizing: border-box !important;
                        clear: both !important;
                    }
                    @media (max-width: 767px) {
                        .woocommerce-customer-details .col2-set,
                        .woocommerce-customer-details .woocommerce-columns--addresses,
                        section.woocommerce-columns--addresses {
                            display: flex !important;
                            flex-direction: column !important;
                            gap: 1.5rem !important;
                        }
                        .woocommerce-customer-details .col-1,
                        .woocommerce-customer-details .col-2,
                        div.woocommerce-column--billing-address,
                        div.woocommerce-column--shipping-address {
                            float: none !important;
                            width: 100% !important;
                            clear: both !important;
                            display: block !important;
                            margin-bottom: 1.5rem !important;
                        }
                    }
                    @media (min-width: 768px) {
                        .woocommerce .woocommerce-customer-details .col2-set,
                        .woocommerce-page .woocommerce-customer-details .col2-set,
                        .woocommerce-customer-details .col2-set,
                        .woocommerce-customer-details .woocommerce-columns--addresses,
                        section.woocommerce-columns--addresses {
                            display: grid !important;
                            grid-template-columns: 1fr 1fr !important;
                            gap: 1.5rem !important;
                        }
                        .woocommerce-customer-details .col-1,
                        .woocommerce-customer-details .col-2,
                        div.woocommerce-column--billing-address,
                        div.woocommerce-column--shipping-address {
                            margin-bottom: 0 !important;
                        }
                    }
                    .woocommerce-customer-details address {
                        display: block !important;
                        background: #FFFFFF !important;
                        border: 1px solid #E2E8F0 !important;
                        border-radius: 1.25rem !important;
                        padding: 1.5rem !important;
                        font-style: normal !important;
                        font-size: 0.9375rem !important;
                        line-height: 1.65 !important;
                        color: #334155 !important;
                        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.04) !important;
                        width: 100% !important;
                        box-sizing: border-box !important;
                    }
                    .woocommerce-customer-details address p {
                        display: flex !important;
                        align-items: center !important;
                        gap: 0.5rem !important;
                        margin-top: 0.75rem !important;
                        margin-bottom: 0 !important;
                        color: #475569 !important;
                        font-size: 0.875rem !important;
                        word-break: break-all !important;
                    }
                    .woocommerce-customer-details h2.woocommerce-column__title {
                        font-family: var(--font-heading, inherit);
                        font-size: 1.125rem !important;
                        font-weight: 800 !important;
                        color: #0F172A !important;
                        margin-top: 0 !important;
                        margin-bottom: 0.75rem !important;
                        padding-bottom: 0.5rem !important;
                        border-bottom: 2px solid #E2E8F0 !important;
                    }
                </style>
                <?php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>
                <?php do_action( 'woocommerce_thankyou', $order->get_id() ); ?>
            </div>

            <!-- Return to Home CTA -->
            <div class="text-center mt-10 pt-6 border-t border-border/60">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="inline-flex items-center gap-2 px-8 py-3.5 rounded-full border border-border text-foreground font-bold hover:bg-muted transition-all shadow-xs hover:shadow-sm text-sm">
                    <svg class="w-4 h-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    <span>Return to Shop Homepage</span>
                </a>
            </div>

		<?php endif; ?>

	<?php else : ?>

		<?php wc_get_template( 'checkout/order-received.php', array( 'order' => false ) ); ?>

	<?php endif; ?>

</div>

<!-- Copy to Clipboard Interactive Script -->
<script>
function copyValue(text, btn) {
    if (!navigator.clipboard) {
        var textarea = document.createElement("textarea");
        textarea.value = text;
        document.body.appendChild(textarea);
        textarea.select();
        try {
            document.execCommand("copy");
            showCopied(btn);
        } catch (err) {}
        document.body.removeChild(textarea);
        return;
    }
    navigator.clipboard.writeText(text).then(function() {
        showCopied(btn);
    });
}

function showCopied(btn) {
    var originalHTML = btn.innerHTML;
    btn.innerHTML = '<svg class="w-3.5 h-3.5 text-emerald-600 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg><span class="text-emerald-700 font-bold">Copied!</span>';
    btn.classList.add('border-emerald-300', 'bg-emerald-50');
    setTimeout(function() {
        btn.innerHTML = originalHTML;
        btn.classList.remove('border-emerald-300', 'bg-emerald-50');
    }, 2000);
}
</script>
