# The PowerHost — Domain Reseller Module

**Version:** 2.1.7 &nbsp;|&nbsp; **WHMCS:** 7.0+ &nbsp;|&nbsp; **PHP:** 7.4+ &nbsp;|&nbsp; **Status:** Production Ready ✅  
**Official Website:** [https://thepowerhost.in/](https://thepowerhost.in/)

---

## What Is This?

This is the **official WHMCS Registrar Module** by **The PowerHost**, allowing you to resell domains from The PowerHost API directly through your own WHMCS installation.

```
Your Customer → Your WHMCS → The PowerHost API → Domain Registry
```

1. Customer orders a domain through your WHMCS
2. Your WHMCS forwards the request to The PowerHost API
3. The API registers the domain and deducts from your reseller balance
4. Nameservers are automatically pushed to the registry right after registration
5. Your customer gets their domain — seamlessly and transparently

---

## Two Modules, Two Roles

| Side | Module | Installed On |
|------|--------|-------------|
| **Provider** | Domain Reseller API Addon | The PowerHost WHMCS |
| **Client** | This Module ← | **Your WHMCS** |

You are installing the **client-side module** on your own WHMCS.

---

## Features

| Feature | Status | Notes |
|---------|--------|-------|
| Domain Registration | ✅ | Full EPP contact support |
| Domain Transfer | ✅ | With EPP code |
| Domain Renewal | ✅ | |
| Nameserver Management | ✅ | Get & Save |
| **Auto Nameserver Sync** | ✅ | **Pushed instantly after registration** |
| Domain Lock / Unlock | ✅ | |
| EPP Code Retrieval | ✅ | |
| Domain Sync (Cron) | ✅ | Expiry date & status |
| Availability Check | ✅ | WHMCS 7.10+ |
| TLD Pricing Import | ✅ | Auto-imports wholesale prices |
| Balance Monitoring | ✅ | Dashboard widget |
| Low Balance Alerts | ✅ | Warning when balance < $50 |
| **Default Contact** | ✅ | **Override registrant details globally** |
| Debug / Test Mode | ✅ | Module log integration |

---

## What's New in v2.1.7

### Default Contact
A new **Default Contact** option is available in the registrar settings. When enabled, a single fixed contact (name, address, phone, email) is sent to the registry for **all** domain registrations and transfers — overriding each individual customer's own contact details. When disabled, the customer's original WHMCS contact details are used as normal.

**Use case:** Resellers who must register all domains under a single corporate contact for compliance or privacy reasons.

### Auto Nameserver Sync
After every successful domain registration, the module automatically fires a nameserver update to the API via the `AfterRegistrarRegistration` WHMCS hook — no cron configuration needed. This ensures nameservers are always pushed to the registry immediately, even if the upstream API doesn't apply them during the initial registration call.

---

## Quick Start

### Step 1 — Get API Credentials

Log in to your reseller account at [thepowerhost.in](https://thepowerhost.in/) and retrieve:
- API URL
- API Key
- API Secret

### Step 2 — Upload the Module

```
/modules/registrars/domainreseller/domainreseller.php
/modules/registrars/domainreseller/clientarea.tpl
/modules/registrars/domainreseller/hooks/balance_monitor.php
/modules/registrars/domainreseller/hooks/nameserver_autoupdate.php
```

### Step 3 — Activate & Configure

1. WHMCS Admin → **Setup → Domain Registrars**
2. Find **"The PowerHost"** → click **Activate**
3. Enter your **API URL**, **API Key**, and **API Secret**
4. Click **"Test Connection"** to verify
5. *(Optional)* Enable **Default Contact** and fill in the contact fields

### Step 4 — Import TLD Pricing

1. **Setup → Domain Pricing**
2. Select registrar: **"The PowerHost"**
3. Click **"Import Pricing"**
4. Add your markup to each TLD price

### Step 5 — Start Selling

Set your TLDs to auto-register via **"The PowerHost"** and you're live.

---

## Default Contact — Configuration

Navigate to **Setup → Domain Registrars → The PowerHost → Configure**.

| Setting | Description |
|---------|-------------|
| **Enable Default Contact** | Toggle on to activate; toggle off to use customer details |
| Default First Name | Registrant first name |
| Default Last Name | Registrant last name |
| Default Company Name | Optional company name |
| Default Address Line 1 | Street address |
| Default Address Line 2 | Suite / unit (optional) |
| Default City | City |
| Default State / Region | State or region |
| Default Postcode | Postal / ZIP code |
| Default Country Code | Two-letter ISO code (e.g. `IN`, `US`, `GB`) |
| Default Phone Number | Full international format — e.g. `+91.9876543210` |
| Default Email Address | Registrant contact email |

> **When disabled:** customer's WHMCS profile details are used for every registration and transfer (original behaviour).  
> **When enabled:** the fields above replace the customer's details for every registration and transfer.

---

## Auto Nameserver Sync — How It Works

The file `hooks/nameserver_autoupdate.php` registers a single WHMCS hook:

**`AfterRegistrarRegistration`** — fires immediately after WHMCS records a successful domain registration. It reads the nameservers stored against the domain in `tbldomains` and calls `SaveNameservers` on The PowerHost API. The result (success or failure) is written to the WHMCS activity log under the label `[The PowerHost] Auto-NS`.

No cron job or manual intervention is required. WHMCS loads all PHP files in a registrar module's `hooks/` folder automatically.

---

## Files

```
domainreseller/
├── domainreseller.php                  # Core registrar module
├── clientarea.tpl                      # Branded client area template
├── hooks/
│   ├── balance_monitor.php             # Admin dashboard balance widget & alerts
│   └── nameserver_autoupdate.php       # Auto nameserver push after registration
├── INSTALLATION.md                     # Full step-by-step installation guide
├── QUICKSTART.md                       # Quick reference card
├── CLIENT_MODULE_SUMMARY.md            # Technical function summary
└── README.md                           # This file
```

---

## Admin Panel

### Custom Buttons (per domain)

| Button | Action |
|--------|--------|
| Test Connection | Pings the API and returns current balance |
| Check Balance | Returns your live reseller balance |

### Dashboard Widget

- Displays current reseller balance
- Shows a **red warning** when balance drops below $50
- Updates on every admin dashboard load

---

## Balance Management

### Checking Your Balance
- Admin dashboard widget (auto-refreshes)
- **Check Balance** button on any domain page
- Client area page for the registrar

### Adding Funds

Funds **cannot** be added through your WHMCS. To top up:

1. Log in to [thepowerhost.in](https://thepowerhost.in/)
2. Navigate to **Billing → Add Funds**
3. Complete payment — balance updates immediately

### Low Balance Behaviour

- A warning banner appears in the admin dashboard when balance < $50
- Domain orders will fail at the API level if balance is insufficient
- Monitor your usage and top up proactively

---

## Security

| Item | Handling |
|------|----------|
| API Key | Stored in plain text — safe to display, used for identification only |
| API Secret | Stored **encrypted** in `tblregistrars` — never displayed after entry |
| HTTPS | Always use an HTTPS API URL |
| Debug Mode | Disable in production; logs may contain sensitive domain data |

If your credentials are ever compromised:
1. Log in to [thepowerhost.in](https://thepowerhost.in/)
2. Regenerate your API Key and Secret
3. Update the values in your WHMCS registrar configuration

---

## Troubleshooting

### "API Connection Error"
- Confirm the API URL is correct and reachable
- Verify cURL is enabled on your server (`phpinfo()`)
- Check for firewall rules blocking outbound HTTPS

### "Authentication Failed" / HTTP 401
- Re-enter your API Key and Secret — copy-paste directly from the portal
- Ensure no trailing spaces in the credentials
- Try regenerating credentials at [thepowerhost.in](https://thepowerhost.in/)

### "Insufficient Credit Balance" / HTTP 402
- Add funds via the reseller portal
- Use **Check Balance** to confirm current amount

### Nameservers Not Updating After Registration
- Confirm `hooks/nameserver_autoupdate.php` is present in the `hooks/` directory
- Check **Utilities → Logs → Activity Log** for `[The PowerHost] Auto-NS` entries
- Enable **Debug Mode** and check **Utilities → Logs → Module Log**

### Default Contact Not Being Applied
- Confirm **Enable Default Contact** is set to **Yes** in registrar settings
- Ensure all required fields (name, address, country, phone, email) are filled in
- Save the configuration and test a new registration

### Enabling Debug Logs
1. Registrar config → set **Debug Mode** to **Yes**
2. Reproduce the issue
3. Check: **Utilities → Logs → Module Log** — filter by `domainreseller`

---

## Requirements

- WHMCS 7.0 or higher (7.10+ for availability checking)
- PHP 7.4 or higher
- cURL extension enabled
- Active reseller account at [thepowerhost.in](https://thepowerhost.in/)
- Valid API credentials

---

## FAQ

**Q: Where do I get my API credentials?**  
A: From your reseller dashboard at [thepowerhost.in](https://thepowerhost.in/).

**Q: Can I see my API Secret after saving it?**  
A: No. It is stored encrypted and never displayed. If you lose it, regenerate a new one from the portal.

**Q: Will customers know I'm using a reseller?**  
A: No. The domain ordering and management experience is identical to any native WHMCS registrar.

**Q: What happens if my balance runs out mid-order?**  
A: The API returns an insufficient-balance error; the domain will not be registered and WHMCS will mark the order as failed.

**Q: Do I need to configure a cron job for nameserver sync?**  
A: No. Nameservers are pushed automatically via the `AfterRegistrarRegistration` hook the moment registration succeeds.

**Q: Can I use Default Contact for only some TLDs?**  
A: Not natively — the setting applies globally. If you need per-TLD behaviour, contact [The PowerHost support](https://thepowerhost.in/).

**Q: Can I test the module without spending real money?**  
A: Enable **Test Mode** to log all API calls. Note that live operations still consume real balance.

---

## Support

For any issues with this module, your reseller account, or API access:

🌐 **Website:** [https://thepowerhost.in/](https://thepowerhost.in/)

---

## License

MIT License — free to use, modify, and distribute.

---

*The PowerHost — Domain Reseller Module v2.1.7*
