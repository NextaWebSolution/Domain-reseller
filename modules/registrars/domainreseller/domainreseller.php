<?php

/**
 * Domain Reseller - Registrar Module (Client Side)
 * This module allows WHMCS installations to resell domains from a Domain Reseller API provider
 *
 * @copyright Copyright (c) 2024
 * @license MIT
 *
 * Changelog:
 *  - Added Default Contact feature: admin can define a fixed contact used for all
 *    domain registrations and transfers when "Use Default Contact" is enabled.
 *  - Added auto nameserver update hook (see hooks/nameserver_autoupdate.php).
 */

if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

use WHMCS\Domain\TopLevel\ImportItem;
use WHMCS\Results\ResultsList;

/**
 * Define module metadata
 */
function domainreseller_MetaData()
{
    return [
        'DisplayName'   => 'The PowerHost',
        'APIVersion'    => '1.1',
        'RequiresServer' => false,
        'Author'        => 'The PowerHost',
        'AuthorUrl'     => 'https://thepowerhost.in/',
        'Version'       => '2.1.7',
    ];
}

/**
 * Define configuration options
 *
 * NEW — Default Contact block:
 *   EnableDefaultContact  yesno  — master toggle
 *   DefaultFirstname … DefaultEmail — the contact fields that override the
 *   customer's own details when the toggle is ON.
 */
function domainreseller_getConfigArray()
{
    return [

        // ── Core credentials ──────────────────────────────────────────────
        'FriendlyName' => [
            'Type'  => 'System',
            'Value' => 'The PowerHost',
        ],
        'Description' => [
            'Type'  => 'System',
            'Value' => 'Official Domain Reseller module by The PowerHost — v2.1.7 | https://thepowerhost.in/',
        ],

        'ApiUrl' => [
            'FriendlyName' => 'API URL',
            'Type' => 'text',
            'Value' => 'https://my.thepowerhost.in/modules/addons/domain_reseller/api.php',

            'Default' => 'https://my.thepowerhost.in/modules/addons/domain_reseller/api.php',
            'Description' => 'Enter the full API URL (e.g., https://my.thepowerhost.in/modules/addons/domain_reseller/api.php)',
        ],
        'ApiKey' => [
            'FriendlyName' => 'API Key',
            'Type'         => 'text',
            'Size'         => '64',
            'Default'      => '',
            'Description'  => 'Your Domain Reseller API Key',
        ],
        'ApiSecret' => [
            'FriendlyName' => 'API Secret',
            'Type'         => 'password',
            'Size'         => '64',
            'Default'      => '',
            'Description'  => 'Your Domain Reseller API Secret (keep this confidential)',
        ],
        'TestMode' => [
            'FriendlyName' => 'Test Mode',
            'Type'         => 'yesno',
            'Description'  => 'Enable test mode (logs all API calls)',
        ],
        'DebugMode' => [
            'FriendlyName' => 'Debug Mode',
            'Type'         => 'yesno',
            'Description'  => 'Enable debug logging',
        ],

        // ── Default Contact ───────────────────────────────────────────────
        // When EnableDefaultContact = on, the fields below replace the
        // customer's own contact details on every Register / Transfer call.
        'EnableDefaultContact' => [
            'FriendlyName' => 'Enable Default Contact',
            'Type'         => 'yesno',
            'Description'  => 'When enabled, the Default Contact details below will '
                . 'be sent to the registry for all domain registrations '
                . 'and transfers instead of the customer\'s own details.',
        ],
        'DefaultFirstname' => [
            'FriendlyName' => 'Default First Name',
            'Type'         => 'text',
            'Size'         => '30',
            'Default'      => '',
            'Description'  => 'First name for the default registrant contact',
        ],
        'DefaultLastname' => [
            'FriendlyName' => 'Default Last Name',
            'Type'         => 'text',
            'Size'         => '30',
            'Default'      => '',
            'Description'  => 'Last name for the default registrant contact',
        ],
        'DefaultCompanyname' => [
            'FriendlyName' => 'Default Company Name',
            'Type'         => 'text',
            'Size'         => '50',
            'Default'      => '',
            'Description'  => 'Company name for the default registrant contact (optional)',
        ],
        'DefaultAddress1' => [
            'FriendlyName' => 'Default Address Line 1',
            'Type'         => 'text',
            'Size'         => '50',
            'Default'      => '',
            'Description'  => 'Street address line 1',
        ],
        'DefaultAddress2' => [
            'FriendlyName' => 'Default Address Line 2',
            'Type'         => 'text',
            'Size'         => '50',
            'Default'      => '',
            'Description'  => 'Street address line 2 (optional)',
        ],
        'DefaultCity' => [
            'FriendlyName' => 'Default City',
            'Type'         => 'text',
            'Size'         => '30',
            'Default'      => '',
            'Description'  => 'City for the default registrant contact',
        ],
        'DefaultState' => [
            'FriendlyName' => 'Default State / Region',
            'Type'         => 'text',
            'Size'         => '30',
            'Default'      => '',
            'Description'  => 'State or region for the default registrant contact',
        ],
        'DefaultPostcode' => [
            'FriendlyName' => 'Default Postcode',
            'Type'         => 'text',
            'Size'         => '10',
            'Default'      => '',
            'Description'  => 'Postal / ZIP code for the default registrant contact',
        ],
        'DefaultCountry' => [
            'FriendlyName' => 'Default Country Code',
            'Type'         => 'text',
            'Size'         => '2',
            'Default'      => '',
            'Description'  => 'Two-letter ISO country code (e.g. US, GB, IN)',
        ],
        'DefaultPhone' => [
            'FriendlyName' => 'Default Phone Number',
            'Type'         => 'text',
            'Size'         => '20',
            'Default'      => '',
            'Description'  => 'Phone number in full international format (e.g. +1.2125551234)',
        ],
        'DefaultEmail' => [
            'FriendlyName' => 'Default Email Address',
            'Type'         => 'text',
            'Size'         => '50',
            'Default'      => '',
            'Description'  => 'Email address for the default registrant contact',
        ],
    ];
}

// ─────────────────────────────────────────────────────────────────────────────
// Helper: resolve contact details
// Returns the default contact array when the toggle is on, or the
// customer's own data otherwise.
// ─────────────────────────────────────────────────────────────────────────────
function domainreseller_resolveContact($params)
{
    $useDefault = isset($params['EnableDefaultContact'])
        && $params['EnableDefaultContact'] === 'on';

    if ($useDefault) {
        return [
            'firstname'       => $params['DefaultFirstname']   ?? '',
            'lastname'        => $params['DefaultLastname']    ?? '',
            'companyname'     => $params['DefaultCompanyname'] ?? '',
            'address1'        => $params['DefaultAddress1']    ?? '',
            'address2'        => $params['DefaultAddress2']    ?? '',
            'city'            => $params['DefaultCity']        ?? '',
            'state'           => $params['DefaultState']       ?? '',
            'postcode'        => $params['DefaultPostcode']    ?? '',
            'country'         => $params['DefaultCountry']     ?? '',
            'fullphonenumber' => $params['DefaultPhone']       ?? '',
            'email'           => $params['DefaultEmail']       ?? '',
        ];
    }

    // Fall back to customer's own contact details (WHMCS standard params)
    return [
        'firstname'       => $params['firstname']    ?? '',
        'lastname'        => $params['lastname']     ?? '',
        'companyname'     => $params['companyname']  ?? '',
        'address1'        => $params['address1']     ?? '',
        'address2'        => $params['address2']     ?? '',
        'city'            => $params['city']         ?? '',
        'state'           => $params['state']        ?? '',
        'postcode'        => $params['postcode']     ?? '',
        'country'         => $params['country']      ?? '',
        'fullphonenumber' => $params['phonenumber']  ?? '',
        'email'           => $params['email']        ?? '',
    ];
}

// ─────────────────────────────────────────────────────────────────────────────
// Core API caller
// ─────────────────────────────────────────────────────────────────────────────
function domainreseller_api_call($params, $endpoint, $method = 'GET', $data = [])
{
    $apiUrl    = isset($params['ApiUrl'])    ? trim($params['ApiUrl'])    : '';
    $apiKey    = isset($params['ApiKey'])    ? trim($params['ApiKey'])    : '';
    $apiSecret = isset($params['ApiSecret']) ? trim($params['ApiSecret']) : '';

    if (empty($apiUrl)) {
        logModuleCall('domainreseller', 'config_error', $params, 'Missing API URL', 'FAILED');
        return ['error' => 'API URL not configured.'];
    }
    if (empty($apiKey)) {
        logModuleCall('domainreseller', 'config_error', $params, 'Missing API Key', 'FAILED');
        return ['error' => 'API Key not configured.'];
    }
    if (empty($apiSecret)) {
        logModuleCall('domainreseller', 'config_error', $params, 'Missing API Secret', 'FAILED');
        return ['error' => 'API Secret not configured.'];
    }

    $apiUrl = rtrim($apiUrl, '/');

    if (!filter_var($apiUrl, FILTER_VALIDATE_URL)) {
        return ['error' => 'Invalid API URL format: ' . $apiUrl];
    }

    $url = $apiUrl;
    if ($method === 'GET') {
        $url .= '?' . http_build_query(array_merge(['action' => $endpoint], $data));
    } else {
        $url .= '?action=' . urlencode($endpoint);
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_ENCODING, '');
    curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'X-API-Key: ' . $apiKey,
        'X-API-Secret: ' . $apiSecret,
    ]);

    if ($method === 'POST' && !empty($data)) {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }

    $response  = curl_exec($ch);
    $httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    $curlErrno = curl_errno($ch);
    curl_close($ch);

    $debugMode = isset($params['DebugMode']) && $params['DebugMode'] === 'on';
    if ($debugMode) {
        logModuleCall(
            'domainreseller',
            $endpoint,
            [
                'url'     => $url,
                'method'  => $method,
                'data'    => $data,
                'headers' => ['X-API-Key: ' . substr($apiKey, 0, 10) . '...', 'X-API-Secret: [HIDDEN]'],
            ],
            $response,
            ['http_code' => $httpCode, 'curl_errno' => $curlErrno],
            [$apiSecret]
        );
    }

    if ($curlError) {
        $msg = "API Connection Error: {$curlError} (Code: {$curlErrno})";
        logModuleCall('domainreseller', $endpoint . '_error', ['url' => $url], $msg, 'FAILED');
        return ['error' => $msg];
    }

    if (empty($response)) {
        $msg = "Empty response from API (HTTP {$httpCode})";
        logModuleCall('domainreseller', $endpoint . '_error', ['url' => $url], $msg, 'FAILED');
        return ['error' => $msg];
    }

    $result = json_decode($response, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        $msg = "Invalid JSON response: " . json_last_error_msg();
        logModuleCall('domainreseller', $endpoint . '_error', ['url' => $url], $response, 'FAILED');
        return ['error' => $msg];
    }

    if ($httpCode !== 200) {
        $msg = isset($result['error']) ? $result['error'] : "API Error (HTTP {$httpCode})";
        if ($httpCode === 401) logModuleCall('domainreseller', 'auth_error',    ['endpoint' => $endpoint], $msg, 'FAILED');
        elseif ($httpCode === 402) logModuleCall('domainreseller', 'balance_error', ['endpoint' => $endpoint], $msg, 'FAILED');
        elseif ($httpCode === 429) logModuleCall('domainreseller', 'rate_limit',    ['endpoint' => $endpoint], $msg, 'FAILED');
        return ['error' => $msg];
    }

    return $result;
}

// ─────────────────────────────────────────────────────────────────────────────
// Nameservers
// ─────────────────────────────────────────────────────────────────────────────
function domainreseller_GetNameservers($params)
{
    $result = domainreseller_api_call($params, 'nameservers', 'GET', [
        'domain' => $params['sld'] . '.' . $params['tld'],
    ]);

    if (isset($result['error'])) {
        return ['error' => $result['error']];
    }

    $nameservers = [];
    if (isset($result['nameservers']) && is_array($result['nameservers'])) {
        foreach ($result['nameservers'] as $index => $ns) {
            $nameservers['ns' . ($index + 1)] = $ns;
        }
    }

    return $nameservers;
}

function domainreseller_SaveNameservers($params)
{
    $nameservers = [];
    for ($i = 1; $i <= 5; $i++) {
        if (!empty($params['ns' . $i])) {
            $nameservers[] = $params['ns' . $i];
        }
    }

    $result = domainreseller_api_call($params, 'nameservers', 'POST', [
        'domain'      => $params['sld'] . '.' . $params['tld'],
        'nameservers' => $nameservers,
    ]);

    if (isset($result['error'])) {
        return ['error' => $result['error']];
    }

    return ['success' => true];
}

// ─────────────────────────────────────────────────────────────────────────────
// Registrar lock
// ─────────────────────────────────────────────────────────────────────────────
function domainreseller_GetRegistrarLock($params)
{
    $result = domainreseller_api_call($params, 'info', 'GET', [
        'domain' => $params['sld'] . '.' . $params['tld'],
    ]);

    if (isset($result['error'])) {
        return ['error' => $result['error']];
    }

    return $result['locked'] ? 'locked' : 'unlocked';
}

function domainreseller_SaveRegistrarLock($params)
{
    $lockStatus = $params['lockenabled'] === 'locked' ? 1 : 0;

    $result = domainreseller_api_call($params, 'lock', 'POST', [
        'domain' => $params['sld'] . '.' . $params['tld'],
        'lock'   => $lockStatus,
    ]);

    if (isset($result['error'])) {
        return ['error' => $result['error']];
    }

    return ['success' => true];
}

// ─────────────────────────────────────────────────────────────────────────────
// Register a domain
// Uses default contact when EnableDefaultContact = on
// ─────────────────────────────────────────────────────────────────────────────
function domainreseller_RegisterDomain($params)
{
    $nameservers = [];
    for ($i = 1; $i <= 5; $i++) {
        if (!empty($params['ns' . $i])) {
            $nameservers[] = $params['ns' . $i];
        }
    }

    // Resolve which contact details to use
    $contact = domainreseller_resolveContact($params);

    $postData = array_merge([
        'domain'      => $params['sld'] . '.' . $params['tld'],
        'years'       => $params['regperiod'],
        'nameservers' => $nameservers,
        'regperiod'   => $params['regperiod'],
    ], $contact);

    $result = domainreseller_api_call($params, 'register', 'POST', $postData);

    if (isset($result['error'])) {
        return ['error' => $result['error']];
    }

    return ['success' => true];
}

// ─────────────────────────────────────────────────────────────────────────────
// Transfer a domain
// Uses default contact when EnableDefaultContact = on
// ─────────────────────────────────────────────────────────────────────────────
function domainreseller_TransferDomain($params)
{
    $nameservers = [];
    for ($i = 1; $i <= 5; $i++) {
        if (!empty($params['ns' . $i])) {
            $nameservers[] = $params['ns' . $i];
        }
    }

    // Resolve which contact details to use
    $contact = domainreseller_resolveContact($params);

    $postData = array_merge([
        'domain'      => $params['sld'] . '.' . $params['tld'],
        'epp_code'    => $params['eppcode'],
        'years'       => $params['regperiod'],
        'regperiod'   => $params['regperiod'],
        'nameservers' => $nameservers,
    ], $contact);

    $result = domainreseller_api_call($params, 'transfer', 'POST', $postData);

    if (isset($result['error'])) {
        return ['error' => $result['error']];
    }

    return ['success' => true];
}

// ─────────────────────────────────────────────────────────────────────────────
// Renew
// ─────────────────────────────────────────────────────────────────────────────
function domainreseller_RenewDomain($params)
{
    $result = domainreseller_api_call($params, 'renew', 'POST', [
        'domain'    => $params['sld'] . '.' . $params['tld'],
        'regperiod' => $params['regperiod'],
        'years'     => $params['regperiod'],
    ]);

    if (isset($result['error'])) {
        return ['error' => $result['error']];
    }

    return ['success' => true];
}

// ─────────────────────────────────────────────────────────────────────────────
// EPP Code
// ─────────────────────────────────────────────────────────────────────────────
function domainreseller_GetEPPCode($params)
{
    $result = domainreseller_api_call($params, 'epp', 'GET', [
        'domain' => $params['sld'] . '.' . $params['tld'],
    ]);

    if (isset($result['error'])) {
        return ['error' => $result['error']];
    }

    return ['eppcode' => $result['epp_code'] ?? ''];
}

// ─────────────────────────────────────────────────────────────────────────────
// Sync
// ─────────────────────────────────────────────────────────────────────────────
function domainreseller_Sync($params)
{
    $result = domainreseller_api_call($params, 'info', 'GET', [
        'domain' => $params['sld'] . '.' . $params['tld'],
    ]);

    if (isset($result['error'])) {
        return ['error' => $result['error']];
    }

    $return = [];

    if (!empty($result['expiry_date'])) {
        $return['expirydate'] = date('Y-m-d', strtotime($result['expiry_date']));
    }

    if (isset($result['status'])) {
        $return['active']      = ($result['status'] === 'active');
        $return['expired']     = ($result['status'] === 'expired');
        $return['transferred'] = ($result['status'] === 'transferred');
    }

    return $return;
}

// ─────────────────────────────────────────────────────────────────────────────
// Domain availability check
// ─────────────────────────────────────────────────────────────────────────────
function domainreseller_CheckAvailability($params)
{
    $results = new ResultsList();

    foreach ($params['tlds'] as $tld) {
        foreach ($params['slds'] as $sld) {
            $domain = $sld . $tld;

            $result = domainreseller_api_call($params, 'check', 'GET', ['domain' => $domain]);

            $searchResult = new \WHMCS\Domains\DomainLookup\SearchResult($sld, $tld);

            if (isset($result['available'])) {
                $searchResult->setStatus(
                    $result['available']
                        ? \WHMCS\Domains\DomainLookup\SearchResult::STATUS_NOT_REGISTERED
                        : \WHMCS\Domains\DomainLookup\SearchResult::STATUS_REGISTERED
                );
            } else {
                $searchResult->setStatus(\WHMCS\Domains\DomainLookup\SearchResult::STATUS_UNKNOWN);
            }

            $results->append($searchResult);
        }
    }

    return $results;
}

// ─────────────────────────────────────────────────────────────────────────────
// TLD pricing
// ─────────────────────────────────────────────────────────────────────────────
function domainreseller_GetTldPricing($params)
{
    $result = domainreseller_api_call($params, 'tlds', 'GET');

    if (isset($result['error'])) {
        return ['error' => $result['error']];
    }

    $pricing = new ResultsList();

    if (is_array($result)) {
        foreach ($result as $tldData) {
            if (!isset($tldData['tld'])) continue;

            $item = (new ImportItem)
                ->setExtension($tldData['tld'])
                ->setMinYears(1)
                ->setMaxYears(10)
                ->setRegisterPrice($tldData['register_price'])
                ->setRenewPrice($tldData['renew_price'])
                ->setTransferPrice($tldData['transfer_price'])
                ->setCurrency('USD');

            $pricing->append($item);
        }
    }

    return $pricing;
}

// ─────────────────────────────────────────────────────────────────────────────
// Balance
// ─────────────────────────────────────────────────────────────────────────────
function domainreseller_GetBalance($params)
{
    $result = domainreseller_api_call($params, 'balance', 'GET');

    if (isset($result['error'])) {
        return ['error' => $result['error']];
    }

    return [
        'balance'  => $result['balance']  ?? 0,
        'currency' => $result['currency'] ?? 'USD',
    ];
}

// ─────────────────────────────────────────────────────────────────────────────
// Client area
// ─────────────────────────────────────────────────────────────────────────────
function domainreseller_ClientArea($params)
{
    if (empty($params['ApiUrl']) || empty($params['ApiKey']) || empty($params['ApiSecret'])) {
        return [
            'templatefile' => 'clientarea',
            'vars' => [
                'error'     => 'API credentials not configured. Please contact your administrator.',
                'api_url'   => '',
                'api_key'   => '',
                'balance'   => 'N/A',
                'test_mode' => false,
            ],
        ];
    }

    $balanceData = domainreseller_GetBalance($params);
    $balance = isset($balanceData['error'])
        ? 'Error: ' . $balanceData['error']
        : ($balanceData['balance'] ?? 'N/A');

    return [
        'templatefile' => 'clientarea',
        'vars' => [
            'api_url'   => $params['ApiUrl'],
            'api_key'   => $params['ApiKey'],
            'balance'   => $balance,
            'test_mode' => isset($params['TestMode']) && $params['TestMode'] === 'on',
            'error'     => '',
        ],
    ];
}

// ─────────────────────────────────────────────────────────────────────────────
// Admin custom buttons
// ─────────────────────────────────────────────────────────────────────────────
function domainreseller_AdminCustomButtonArray($params)
{
    return [
        'Test Connection' => 'TestConnection',
        'Check Balance'   => 'CheckBalance',
    ];
}

function domainreseller_TestConnection($params)
{
    $result = domainreseller_api_call($params, 'balance', 'GET');

    if (isset($result['error'])) {
        return ['status' => 'error', 'message' => 'Connection failed: ' . $result['error']];
    }

    return ['status' => 'success', 'message' => 'Connection successful! Balance: $' . $result['balance']];
}

function domainreseller_CheckBalance($params)
{
    $result = domainreseller_api_call($params, 'balance', 'GET');

    if (isset($result['error'])) {
        return ['status' => 'error', 'message' => 'Error: ' . $result['error']];
    }

    return ['status' => 'success', 'message' => 'Current Balance: $' . number_format($result['balance'], 2)];
}
