# Domain Reseller API - Client Module Installation Guide

## Overview

This registrar module allows your WHMCS installation to resell domains from a Domain Reseller API provider. Your customers can order domains through your WHMCS, and the orders are processed through the provider's API.

---

## Requirements

- WHMCS 7.0 or higher
- PHP 7.4 or higher
- cURL enabled
- Valid Domain Reseller API credentials (API Key + Secret)
- Active reseller account with the provider

---

## Installation

### Step 1: Upload Files

1. **Upload the module files** to your WHMCS installation:
   ```
   /modules/registrars/domainreseller/domainreseller.php
   /modules/registrars/domainreseller/clientarea.tpl
   ```

2. **Upload hooks** (optional but recommended):
   ```
   /includes/hooks/balance_monitor.php
   ```

3. **Set file permissions** to 644:
   ```bash
   chmod 644 /modules/registrars/domainreseller/*.php
   chmod 644 /modules/registrars/domainreseller/*.tpl
   ```

---

### Step 2: Activate Module

1. Login to your WHMCS admin panel
2. Go to **Setup → Products/Services → Domain Registrars**
3. Find **"Domain Reseller API"** in the list
4. Click **"Activate"**

---

### Step 3: Configure API Credentials

1. After activation, you'll see the configuration form
2. Enter the following details:

**Required Settings:**

| Field | Value | Example |
|-------|-------|---------|
| **API URL** | Full URL to the provider's API endpoint | `https://provider.com/modules/addons/domain_reseller/api.php` |
| **API Key** | Your API Key from the provider | `abc123def456...` (64 characters) |
| **API Secret** | Your API Secret from the provider | `xyz789uvw012...` (64 characters) |

**Optional Settings:**

| Field | Description | Default |
|-------|-------------|---------|
| **Test Mode** | Logs all API calls for debugging | Unchecked |
| **Debug Mode** | Enables detailed logging | Unchecked |

3. Click **"Save Changes"**

---

### Step 4: Test Connection

1. After saving, click the **"Test Connection"** button
2. You should see: **"Connection successful! Balance: $X.XX"**
3. If you see an error, verify your API credentials

---

### Step 5: Import TLD Pricing

1. Go to **Setup → Products/Services → Domain Pricing**
2. Select **"Domain Reseller API"** from the registrar dropdown
3. Click **"Import Pricing"**
4. The system will automatically import all available TLDs with pricing from the provider
5. **Important:** Adjust your profit margins by adding markup to the imported prices

---

### Step 6: Set as Active Registrar

For each TLD you want to sell:

1. Go to **Setup → Products/Services → Domain Pricing**
2. Click on the TLD (e.g., .com)
3. Set **"Auto Registration"** to **"Domain Reseller API"**
4. Configure pricing (add your markup)
5. Save changes

---

## Configuration Details

### API URL Format

The API URL should be the complete path to the API file on the provider's server:

✅ **Correct:**
```
https://yourdomain.com/modules/addons/domain_reseller/api.php
```

❌ **Incorrect:**
```
https://yourdomain.com/
https://yourdomain.com/modules/addons/domain_reseller/
https://yourdomain.com/api.php
```

### Getting API Credentials

To get your API credentials:

1. Login to the **provider's WHMCS** (where the Domain Reseller addon is installed)
2. Go to your **client area**
3. Navigate to **Addons → Domain Reseller**
4. You'll see:
   - **API Key** (visible - copy this)
   - **API Secret** (copy this immediately when shown, as it's only displayed once)
   
**Note:** The API Secret is only shown when first created. If you lose it, you'll need to regenerate it.

---

## Features

### Supported Operations

✅ **Domain Registration** - Register new domains  
✅ **Domain Transfer** - Transfer domains with EPP code  
✅ **Domain Renewal** - Renew expiring domains  
✅ **Nameserver Management** - Update domain nameservers  
✅ **Domain Lock** - Lock/unlock domains  
✅ **EPP Code Retrieval** - Get EPP/transfer codes  
✅ **Domain Sync** - Sync expiry dates and status  
✅ **Availability Check** - Real-time domain availability  
✅ **Balance Check** - View your reseller account balance  

### Admin Features

- **Balance Widget** - Shows your account balance on admin dashboard
- **Low Balance Alert** - Warning when balance is below $50
- **Test Connection** - Verify API connectivity
- **Check Balance** - Quick balance check button
- **Debug Logging** - Optional detailed logging for troubleshooting

### Client Features

- **Seamless Experience** - Customers don't know domains are from a reseller
- **Standard WHMCS Flow** - Normal domain ordering process
- **Automatic Processing** - Orders processed automatically via API
- **Real-time Updates** - Instant domain status updates

---

## Pricing Configuration

### Understanding Pricing

The provider sets their wholesale prices. You can add your markup in WHMCS.

**Example:**
- Provider's price for .com registration: **$10.00**
- Your markup: **$2.00**
- Your selling price to customers: **$12.00**
- Your profit per domain: **$2.00**

### Setting Markup

1. Go to **Setup → Products/Services → Domain Pricing**
2. Click on a TLD
3. Under pricing, you'll see the imported cost
4. Set your selling price (cost + markup)
5. You can set different prices for:
   - Registration
   - Transfer
   - Renewal
   - Different year periods (1yr, 2yr, etc.)

---

## Balance Management

### Checking Your Balance

**Method 1: Admin Dashboard**
- Widget shows current balance on homepage

**Method 2: Client Area**
- Go to **Addons → Registrars → Domain Reseller API**
- Click **"View Client Area"**
- Balance is displayed

**Method 3: Registrar Settings**
- Go to registrar configuration
- Click **"Check Balance"** button

### Adding Funds

**You cannot add funds through your WHMCS.** To add funds:

1. Login to the **provider's WHMCS** (where you have your reseller account)
2. Go to **Billing → Add Funds**
3. Add credits using any available payment method
4. Credits will be available immediately

### Low Balance Alerts

- Admin dashboard shows warning when balance < $50
- All domain operations require sufficient balance
- If balance is insufficient, domain orders will fail with an error

---

## Troubleshooting

### "API Connection Error"

**Possible Causes:**
- Incorrect API URL
- Server firewall blocking outbound connections
- SSL certificate issues

**Solutions:**
1. Verify API URL is correct and accessible
2. Test URL in browser: `https://provider.com/modules/addons/domain_reseller/api.php?action=balance`
3. Ensure your server can make outbound HTTPS requests
4. Check PHP cURL is enabled: `php -m | grep curl`

### "Authentication Failed"

**Possible Causes:**
- Incorrect API Key
- Incorrect API Secret
- Reseller account suspended

**Solutions:**
1. Verify API Key is correct (64 characters)
2. Regenerate API Secret if unsure
3. Contact provider to verify account status

### "Insufficient Credit Balance"

**Cause:**
- Your reseller account balance is too low

**Solution:**
1. Check balance via "Check Balance" button
2. Login to provider's WHMCS and add funds

### "Domain registration failed"

**Possible Causes:**
- Domain already registered
- Insufficient balance
- Invalid nameservers
- TLD not supported by provider

**Solutions:**
1. Check domain availability first
2. Verify sufficient balance
3. Ensure nameservers are valid
4. Check TLD is in provider's supported list

### Debugging

Enable debug mode to see detailed API logs:

1. Go to registrar configuration
2. Enable **"Debug Mode"**
3. Reproduce the issue
4. Check logs in: **Utilities → Logs → Module Log**
5. Look for entries starting with "domainreseller"

---

## Security Best Practices

1. **Keep API Secret Confidential**
   - Never share your API Secret
   - Don't store it in plain text files
   - Regenerate if compromised

2. **Use HTTPS**
   - Always use HTTPS for API URL
   - Ensure SSL certificates are valid

3. **Restrict Access**
   - Only enable debug mode when needed
   - Limit admin access to registrar configuration

4. **Monitor Balance**
   - Keep track of your spending
   - Set up low balance alerts
   - Add funds before running out

5. **Regular Updates**
   - Keep WHMCS updated
   - Update module when new versions are released

---

## Support

### Getting Help

1. **Provider Support**
   - Contact your domain provider for:
     - API credential issues
     - Balance/billing questions
     - Account management

2. **WHMCS Support**
   - For WHMCS-specific issues
   - Module compatibility
   - General WHMCS questions

3. **Module Issues**
   - Check debug logs first
   - Test connection
   - Verify configuration

### Useful WHMCS Pages

- **Module Log:** Utilities → Logs → Module Log
- **Activity Log:** Utilities → Logs → Activity Log
- **System Health:** Utilities → System → System Health
- **Domain Pricing:** Setup → Products/Services → Domain Pricing
- **Registrar Config:** Setup → Products/Services → Domain Registrars

---

## FAQ

**Q: Where is my API Secret displayed?**
A: The API Secret is stored securely in the registrar configuration (encrypted). It's not displayed in the client area for security reasons. Only the API Key is shown.

**Q: Can I test without using real balance?**
A: Enable "Test Mode" which will log all API calls. However, actual domain operations will still use real balance.

**Q: How often should I check my balance?**
A: The admin dashboard widget shows your balance automatically. Set up low balance alerts to be notified.

**Q: Can I use multiple providers?**
A: No, WHMCS only supports one configuration per registrar module. If you need multiple providers, contact WHMCS support.

**Q: What happens if I run out of balance mid-order?**
A: The domain order will fail with an "insufficient balance" error. The customer's order will show as failed, and you'll need to add funds and retry.

**Q: Can customers see my API credentials?**
A: No, API credentials are only visible in the admin area and are never exposed to clients.

---

## Changelog

### Version 1.0
- Initial release
- Full domain lifecycle management
- Balance monitoring
- Admin dashboard widget
- Debug logging

---

**Module Version:** 1.0  
**WHMCS Compatibility:** 7.0+  
**Last Updated:** November 2024
