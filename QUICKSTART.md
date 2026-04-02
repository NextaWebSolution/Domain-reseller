# Quick Start Guide - Domain Reseller Client Module

## 5-Minute Setup

### Step 1: Get Your API Credentials (2 minutes)

1. Login to your **provider's WHMCS** (where you have reseller account)
2. Go to client area → **Addons → Domain Reseller**
3. Copy these 3 items:
   - ✅ API URL (something like: `https://provider.com/modules/addons/domain_reseller/api.php`)
   - ✅ API Key (64 character code)
   - ✅ API Secret (64 character code - copy NOW, shown only once!)

---

### Step 2: Upload Module (1 minute)

Upload these files to your WHMCS:

```
/modules/registrars/domainreseller/domainreseller.php
/modules/registrars/domainreseller/clientarea.tpl
```

Optional (for balance monitoring):
```
/includes/hooks/balance_monitor.php
```

---

### Step 3: Activate (1 minute)

1. WHMCS Admin → **Setup → Products/Services → Domain Registrars**
2. Find **"Domain Reseller API"**
3. Click **"Activate"**
4. Enter:
   - API URL
   - API Key
   - API Secret
5. Click **"Save Changes"**
6. Click **"Test Connection"** → Should say "Connection successful!"

---

### Step 4: Import Pricing (1 minute)

1. **Setup → Products/Services → Domain Pricing**
2. Registrar dropdown → Select **"Domain Reseller API"**
3. Click **"Import Pricing"**
4. All TLDs imported with wholesale prices!
5. **Important:** Add your markup to each TLD

---

### Step 5: Enable Auto-Registration (30 seconds)

For each TLD you want to sell:

1. Click on the TLD in Domain Pricing
2. Set "Auto Registration" → **"Domain Reseller API"**
3. Save

---

## You're Done! 🎉

Your customers can now:
- Search for domains
- Add to cart
- Checkout
- Get registered automatically via the API

---

## Quick Reference

### Where is my API Secret shown?
**Nowhere (for security).** It's encrypted in the database. Only API Key is visible.

### How do I add funds?
Login to **provider's WHMCS** → **Billing → Add Funds**

### Where do I check my balance?
1. Admin dashboard widget
2. Registrars → Domain Reseller API → View Client Area
3. "Check Balance" button in config

### What's my profit?
Your selling price - provider's wholesale price = your profit

---

## Troubleshooting

**"Connection failed"**
→ Check API URL, Key, and Secret are correct

**"Authentication failed"**  
→ Regenerate credentials from provider

**"Insufficient balance"**
→ Add funds via provider's WHMCS

---

## Next Steps

1. ✅ Set your pricing (add markup)
2. ✅ Monitor your balance
3. ✅ Test with a domain registration
4. ✅ Start selling!

---

**That's it! You're ready to resell domains!** 🚀
