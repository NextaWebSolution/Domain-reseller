# The PowerHost — Domain Reseller Module for WHMCS

**Version:** 2.1.7 &nbsp;|&nbsp; **WHMCS:** 7.0+ &nbsp;|&nbsp; **PHP:** 7.4+  
**Website:** [https://thepowerhost.in/](https://thepowerhost.in/)

---

## Introduction

Thank you for choosing **The PowerHost Domain Reseller Module**. This module connects your WHMCS to our domain API, allowing you to sell domains to your own customers under your own brand — fully automated, no manual work required.

Once installed and configured, your WHMCS will handle domain registrations, transfers, renewals, and all management tasks automatically through our platform.

---

## What You Can Do With This Module

- Sell domain registrations, transfers, and renewals to your customers
- Manage nameservers, domain locks, and EPP codes from within WHMCS
- Import our live TLD pricing directly into your WHMCS and add your own markup
- Monitor your reseller account balance from the WHMCS admin dashboard
- Set a single default contact for all domain registrations if needed

---

## Requirements

- WHMCS 7.0 or higher
- PHP 7.4 or higher
- cURL enabled on your server
- An active reseller account at [thepowerhost.in](https://thepowerhost.in/)
- Your API Key and API Secret from our portal

---

## Installation

### Step 1 — Upload the Module Files

Upload the contents of this package to your WHMCS server, keeping the folder structure exactly as provided:

```
/modules/registrars/domainreseller/domainreseller.php
/modules/registrars/domainreseller/clientarea.tpl
/modules/registrars/domainreseller/hooks/balance_monitor.php
/modules/registrars/domainreseller/hooks/nameserver_autoupdate.php
```

### Step 2 — Activate the Module

1. Log in to your **WHMCS Admin Panel**
2. Go to **Setup → Domain Registrars**
3. Find **"The PowerHost"** and click **Activate**

### Step 3 — Enter Your API Credentials

Once activated, click **Configure** and fill in the following:

| Field | What to Enter |
|-------|---------------|
| API URL | Provided in your reseller portal |
| API Key | Provided in your reseller portal |
| API Secret | Provided in your reseller portal |

Click **"Test Connection"** — you should see a success message with your current balance.

### Step 4 — Import TLD Pricing

1. Go to **Setup → Domain Pricing**
2. Select **"The PowerHost"** as the registrar
3. Click **"Import Pricing"** to pull our latest wholesale prices
4. Set your own selling price for each TLD

### Step 5 — You're Ready

Your WHMCS is now connected. Customers can search, order, and manage domains through your site, and everything is handled automatically in the background.

---

## Configuration Options

### General Settings

| Setting | Description |
|---------|-------------|
| Test Mode | Logs all API activity for debugging without affecting live operations |
| Debug Mode | Writes detailed logs to WHMCS Module Log — disable in production |

### Default Contact (Optional)

By default, each customer's own WHMCS contact details are used when registering or transferring a domain. If you'd prefer all domains to be registered under a single contact (for example, your company's details), you can enable the Default Contact feature.

**To enable:**  
Go to **Setup → Domain Registrars → The PowerHost → Configure**  
Set **"Enable Default Contact"** to **Yes** and fill in the fields below.

| Field | Description |
|-------|-------------|
| First Name | Registrant first name |
| Last Name | Registrant last name |
| Company Name | Optional |
| Address Line 1 | Street address |
| Address Line 2 | Suite / unit (optional) |
| City | City |
| State / Region | State or province |
| Postcode | Postal / ZIP code |
| Country Code | Two-letter code — e.g. `IN`, `US`, `GB` |
| Phone Number | International format — e.g. `+91.9876543210` |
| Email Address | Contact email |

> Leave **"Enable Default Contact"** set to **No** to use each customer's own details (recommended for most resellers).

---

## Features Overview

### Auto Nameserver Sync

When a domain is successfully registered, nameservers are automatically updated on our end right away — no cron job needed. This is handled silently in the background.

### Balance Monitoring

Your reseller balance is displayed on the WHMCS admin dashboard. A warning alert will appear if your balance drops below $50 so you can top up before any orders are affected.

### Admin Quick Actions

On any domain's admin page you will find two buttons:

| Button | Action |
|--------|--------|
| Test Connection | Verifies your API connection and shows current balance |
| Check Balance | Displays your live reseller balance |

---

## Managing Your Balance

Your reseller balance is pre-funded. Each domain order deducts the wholesale cost from your balance automatically.

**To add funds:**
1. Log in to [thepowerhost.in](https://thepowerhost.in/)
2. Go to **Billing → Add Funds**
3. Complete the payment — your balance updates instantly

> Funds cannot be added through your WHMCS. Always ensure you have sufficient balance before running promotions or expecting a high volume of orders.

---

## Troubleshooting

### Test Connection fails
- Double-check your API URL, Key, and Secret — copy-paste directly from the portal
- Make sure your server can reach our API over HTTPS (port 443)

### Domains not registering
- Check your balance — go to **Check Balance** on any domain page
- Check **Utilities → Logs → Module Log** for error details
- Enable **Debug Mode** temporarily to see the full API response

### Nameservers not updating after registration
- Confirm the file `hooks/nameserver_autoupdate.php` is present
- Check **Utilities → Logs → Activity Log** and search for `The PowerHost Auto-NS`

### Default Contact not being used
- Confirm **Enable Default Contact** is set to **Yes** and saved
- Make sure all required fields (name, address, country, phone, email) are filled in

---

## Files Included

```
domainreseller/
├── domainreseller.php              # Core module
├── clientarea.tpl                  # Client area display template
├── hooks/
│   ├── balance_monitor.php         # Balance widget and low-balance alerts
│   └── nameserver_autoupdate.php   # Automatic nameserver sync after registration
├── INSTALLATION.md                 # Extended installation reference
├── QUICKSTART.md                   # Quick setup card
└── README.md                       # This file
```

---

## Support

If you need help with this module or your reseller account, please reach out to us:

🌐 **[https://thepowerhost.in/](https://thepowerhost.in/)**

---

## License

MIT License — you are free to use and modify this module for your own WHMCS installation.

---

*The PowerHost — Domain Reseller Module v2.1.7*