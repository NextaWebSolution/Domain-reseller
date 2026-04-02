<?php
/**
 * Admin Custom Functions for Domain Reseller
 * Adds balance monitoring and alerts
 */

if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

use WHMCS\Database\Capsule;

/**
 * Check balance and show warning if low
 */
add_hook('AdminHomeWidgets', 1, function() {
    // Get registrar configuration
    $config = Capsule::table('tblregistrars')
        ->where('registrar', 'domainreseller')
        ->first();
    
    if (!$config) {
        return;
    }
    
    // Get balance using the module function
    $params = [
        'ApiUrl' => $config->value ?? '',
        'ApiKey' => '', // Get from tblregistrars
        'ApiSecret' => '',
        'DebugMode' => 'off',
    ];
    
    // Load actual config values
    $settings = Capsule::table('tblregistrars')
        ->where('registrar', 'domainreseller')
        ->get();
    
    foreach ($settings as $setting) {
        if ($setting->setting === 'ApiUrl') {
            $params['ApiUrl'] = $setting->value;
        } elseif ($setting->setting === 'ApiKey') {
            $params['ApiKey'] = $setting->value;
        } elseif ($setting->setting === 'ApiSecret') {
            $params['ApiSecret'] = decrypt($setting->value);
        }
    }
    
    // Make API call
    require_once ROOTDIR . '/modules/registrars/domainreseller/domainreseller.php';
    $balance = domainreseller_GetBalance($params);
    
    if (!isset($balance['error'])) {
        $balanceAmount = $balance['balance'];
        $currency = $balance['currency'] ?? 'USD';
        
        // Show warning if balance is low (less than $50)
        if ($balanceAmount < 50) {
            return new \WHMCS\Module\Widget\Dashboard([
                'title' => 'Domain Reseller Balance - LOW!',
                'html' => '
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Low Balance Warning!</strong><br>
                        Your domain reseller balance is low: <strong>$' . number_format($balanceAmount, 2) . ' ' . $currency . '</strong><br>
                        <small>Please add funds to avoid service interruption.</small>
                    </div>
                ',
                'order' => 1,
                'width' => 'half',
            ]);
        } else {
            return new \WHMCS\Module\Widget\Dashboard([
                'title' => 'Domain Reseller Balance',
                'html' => '
                    <div class="text-center" style="padding: 20px;">
                        <h2 class="text-success">$' . number_format($balanceAmount, 2) . '</h2>
                        <p class="text-muted">' . $currency . ' Available</p>
                    </div>
                ',
                'order' => 5,
                'width' => 'quarter',
            ]);
        }
    }
});

/**
 * Add balance check to domain order process
 */
add_hook('ShoppingCartCheckoutCompletePage', 1, function($vars) {
    // Check if any domains were ordered with domainreseller
    $orderId = $vars['orderid'];
    
    $domains = Capsule::table('tbldomains')
        ->where('orderid', $orderId)
        ->where('registrar', 'domainreseller')
        ->count();
    
    if ($domains > 0) {
        // Verify balance is still sufficient
        // This is done automatically by the API, but good to log
        logActivity('Domain order #' . $orderId . ' processed with Domain Reseller API (' . $domains . ' domains)');
    }
});
