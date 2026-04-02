<?php
/**
 * The PowerHost — Domain Reseller Module v2.1.7
 * Auto Nameserver Update Hook
 *
 * Fires immediately after WHMCS records a successful domain registration
 * and pushes the nameservers to the registrar API automatically.
 *
 * Place this file in:
 *   /modules/registrars/domainreseller/hooks/nameserver_autoupdate.php
 *
 * WHMCS loads all PHP files inside a registrar module's hooks/ folder
 * automatically — no additional registration is required.
 *
 * Official website: https://thepowerhost.in/
 */

if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

use WHMCS\Database\Capsule;

// ─────────────────────────────────────────────────────────────────────────────
// Helper: load registrar params from DB
// ─────────────────────────────────────────────────────────────────────────────
function domainreseller_hook_loadParams() {
    $settings = Capsule::table('tblregistrars')
        ->where('registrar', 'domainreseller')
        ->get();

    if (!$settings) {
        return null;
    }

    $params = ['DebugMode' => 'off'];

    foreach ($settings as $row) {
        switch ($row->setting) {
            case 'ApiUrl':    $params['ApiUrl']    = $row->value; break;
            case 'ApiKey':    $params['ApiKey']    = $row->value; break;
            case 'ApiSecret': $params['ApiSecret'] = decrypt($row->value); break;
            case 'DebugMode': $params['DebugMode'] = $row->value; break;
            case 'TestMode':  $params['TestMode']  = $row->value; break;
            case 'EnableDefaultContact': $params['EnableDefaultContact'] = $row->value; break;
        }
    }

    if (empty($params['ApiUrl']) || empty($params['ApiKey']) || empty($params['ApiSecret'])) {
        return null;
    }

    return $params;
}

// ─────────────────────────────────────────────────────────────────────────────
// Helper: push nameservers for a single domain record
// ─────────────────────────────────────────────────────────────────────────────
function domainreseller_hook_pushNameservers($domain, $params) {
    require_once ROOTDIR . '/modules/registrars/domainreseller/domainreseller.php';

    $dotPos = strpos($domain->domain, '.');
    if ($dotPos === false) {
        logActivity("[The PowerHost] Auto-NS: cannot parse domain: {$domain->domain}");
        return false;
    }

    $sld = substr($domain->domain, 0, $dotPos);
    $tld = substr($domain->domain, $dotPos + 1);

    $nsParams = array_merge($params, ['sld' => $sld, 'tld' => $tld]);
    for ($i = 1; $i <= 5; $i++) {
        $col = 'ns' . $i;
        $nsParams[$col] = $domain->$col ?? '';
    }

    $result = domainreseller_SaveNameservers($nsParams);

    if (isset($result['error'])) {
        logActivity("[The PowerHost] Auto-NS FAILED for {$domain->domain}: " . $result['error']);
        return false;
    }

    logActivity("[The PowerHost] Auto-NS SUCCESS for {$domain->domain}");
    return true;
}

// ─────────────────────────────────────────────────────────────────────────────
// Hook — AfterRegistrarRegistration
// Fires immediately after WHMCS records a successful domain registration.
// Pushes the nameservers to the upstream API right away.
// ─────────────────────────────────────────────────────────────────────────────
add_hook('AfterRegistrarRegistration', 1, function($vars) {

    if (($vars['registrar'] ?? '') !== 'domainreseller') {
        return;
    }

    $params = domainreseller_hook_loadParams();
    if (!$params) {
        logActivity('[The PowerHost] Auto-NS: registrar not configured — skipping.');
        return;
    }

    $domainId = (int)($vars['domainId'] ?? 0);
    if (!$domainId) {
        logActivity('[The PowerHost] Auto-NS: no domainId in hook vars — skipping.');
        return;
    }

    $domain = Capsule::table('tbldomains')->where('id', $domainId)->first();
    if (!$domain) {
        logActivity("[The PowerHost] Auto-NS: domain #{$domainId} not found — skipping.");
        return;
    }

    logActivity("[The PowerHost] Auto-NS: pushing nameservers for {$domain->domain} after registration.");
    domainreseller_hook_pushNameservers($domain, $params);
});
