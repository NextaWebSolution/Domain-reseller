# Domain Reseller API - Client Registrar Module

## What Is This?

This is a **WHMCS Registrar Module** that allows you to resell domains from a **Domain Reseller API provider**.

### How It Works:

```
Your Customer → Your WHMCS → Domain Reseller API → Provider's WHMCS → Domain Registry
```

1. **Customer orders domain** through your WHMCS
2. **Your WHMCS sends request** to the Domain Reseller API
3. **API processes order** and deducts from your balance
4. **Domain is registered** and details returned to your WHMCS
5. **Customer gets their domain** - seamlessly!

---

## Two Modules - Two Roles

### 1. **Provider Side** (Domain Reseller API Addon)
- Installed on the **provider's WHMCS**
- Creates reseller accounts
- Manages API access
- Handles billing via WHMCS wallet

### 2. **Client Side** (This Module)
- Installed on **your WHMCS** (the reseller)
- Connects to provider's API
- Processes domain orders
- Shows API credentials and balance

**You are installing the CLIENT SIDE module.**

---

## Quick Start

### 1. Get API Credentials from Provider

Login to your provider's WHMCS and get:
- ✅ API URL
- ✅ API Key  
- ✅ API Secret

### 2. Install Module

```bash
# Upload to your WHMCS
/modules/registrars/domainreseller/domainreseller.php
/modules/registrars/domainreseller/clientarea.tpl
```

### 3. Activate & Configure

1. WHMCS Admin → **Setup → Domain Registrars**
2. Find **"Domain Reseller API"** → **Activate**
3. Enter API URL, Key, and Secret
4. Click **"Test Connection"**

### 4. Import Pricing

1. **Setup → Domain Pricing**
2. Select registrar: **"Domain Reseller API"**
3. Click **"Import Pricing"**
4. Add your markup to prices

### 5. Start Selling!

Set domains to auto-register with "Domain Reseller API"

---

## Features

| Feature | Status |
|---------|--------|
| Domain Registration | ✅ |
| Domain Transfer | ✅ |
| Domain Renewal | ✅ |
| Nameserver Management | ✅ |
| Domain Lock/Unlock | ✅ |
| EPP Code Retrieval | ✅ |
| Domain Sync | ✅ |
| Availability Check | ✅ |
| Balance Monitoring | ✅ |
| Low Balance Alerts | ✅ |

---

## What Customers See

Your customers see a **standard WHMCS domain ordering experience**:

1. Search for domain → Shows as available
2. Add to cart → Standard checkout
3. Pay you → Invoice through your WHMCS
4. Get domain → Managed in their WHMCS client area

**They never know the domain comes from a reseller API!**

---

## What You See

### Admin Dashboard:
- Balance widget showing your credits
- Low balance alerts (< $50)
- "Test Connection" button
- "Check Balance" button

### Client Area (Registrars):
- API URL (visible)
- API Key (visible, copyable)
- **API Secret (hidden for security)**
- Current balance

### Why API Secret is Hidden:

The API Secret is **encrypted and stored securely** in the database. For security reasons, it's not displayed anywhere after initial entry. Only the API Key is shown for reference.

If you need to verify or change your API Secret:
1. Login to provider's WHMCS
2. Regenerate credentials
3. Update in registrar configuration

---

## Pricing & Profits

### How Pricing Works:

1. **Provider sets wholesale prices**
   - Example: .com = $10.00

2. **You import pricing** into WHMCS
   - Imported at wholesale price

3. **You add markup**
   - Your price: $12.00
   - Your profit: $2.00 per domain

4. **Customer pays you**
   - Invoice in your WHMCS: $12.00

5. **Provider deducts wholesale**
   - Deducted from your balance: $10.00

6. **You keep the difference**
   - Your profit: $2.00

---

## Balance Management

### Checking Balance:

**Method 1:** Admin dashboard widget  
**Method 2:** Client area page  
**Method 3:** "Check Balance" button  

### Adding Funds:

**You CANNOT add funds through your WHMCS.**

To add funds:
1. Login to **provider's WHMCS**
2. Go to **Billing → Add Funds**
3. Pay using available payment methods
4. Balance updates immediately

### Low Balance:

- Warning shows when balance < $50
- Domain orders fail if insufficient balance
- Monitor your usage and add funds proactively

---

## Security

### API Credentials:

✅ **API Key:** Visible (safe to see, used for identification)  
✅ **API Secret:** Hidden (encrypted, never shown)  

### Best Practices:

1. Keep API Secret confidential
2. Don't share credentials
3. Use HTTPS for API URL
4. Enable debug mode only when needed
5. Regenerate credentials if compromised

---

## Files Included

```
domain_reseller_client_module/
├── domainreseller.php          # Main registrar module
├── clientarea.tpl              # Client area template
├── hooks/
│   └── balance_monitor.php     # Balance monitoring hook
├── INSTALLATION.md             # Detailed installation guide
└── README.md                   # This file
```

---

## Installation

See **[INSTALLATION.md](INSTALLATION.md)** for complete step-by-step instructions.

**Quick install:**
1. Upload `domainreseller.php` to `/modules/registrars/domainreseller/`
2. Upload `clientarea.tpl` to same directory
3. Activate in WHMCS admin
4. Configure API credentials
5. Import TLD pricing

---

## Troubleshooting

### "API Connection Error"
- Verify API URL is correct
- Check API credentials
- Ensure cURL is enabled

### "Authentication Failed"
- Verify API Key and Secret
- Check account isn't suspended
- Try regenerating credentials

### "Insufficient Credit Balance"
- Add funds via provider's WHMCS
- Check balance via "Check Balance" button

### "Where is my API Secret?"
- It's stored encrypted in the database
- Not shown for security
- Regenerate if you need to change it

### Enable Debug Logging:
1. Registrar config → Enable "Debug Mode"
2. Reproduce issue
3. Check: **Utilities → Logs → Module Log**

---

## Support

**For API/Balance Issues:**
Contact your domain provider

**For WHMCS Issues:**
WHMCS support or documentation

**For Module Issues:**
1. Check debug logs
2. Verify configuration
3. Test connection

---

## Requirements

- WHMCS 7.0+
- PHP 7.4+
- cURL enabled
- Valid reseller account with provider
- Active API credentials

---

## FAQ

**Q: Where do I get API credentials?**  
A: From your reseller account on the provider's WHMCS.

**Q: Why can't I see my API Secret?**  
A: Security. It's encrypted in the database and never displayed.

**Q: How do I add funds?**  
A: Login to provider's WHMCS and use "Add Funds".

**Q: Can customers see I'm a reseller?**  
A: No, it looks like standard WHMCS domain registration.

**Q: What happens if I run out of balance?**  
A: Domain orders will fail until you add funds.

**Q: Can I test without spending real money?**  
A: Enable "Test Mode" for logging, but operations still use real balance.

---

## Version

**Version:** 1.0  
**WHMCS Compatibility:** 7.0+  
**Release Date:** November 2024  
**Status:** Production Ready ✅

---

## License

MIT License - Free to use and modify

---

**Ready to start reselling domains? Install now and start earning!** 🚀
