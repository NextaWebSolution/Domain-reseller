<style>
/* ── The PowerHost — Domain Reseller v2.1.7 ── */
.tph-wrap {
    font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
    color: #1a1a2e;
}
.tph-header {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
    border-radius: 12px 12px 0 0;
    padding: 28px 32px 22px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
}
.tph-logo svg { height: 52px; width: auto; }
.tph-badge {
    background: rgba(255,255,255,0.08);
    border: 1px solid rgba(255,255,255,0.15);
    color: #fff;
    padding: 4px 14px;
    border-radius: 20px;
    font-size: 12px;
    letter-spacing: 0.5px;
}
.tph-badge span { color: #e91e8c; font-weight: 700; }
.tph-body {
    background: #f8f9fc;
    border: 1px solid #e2e6f0;
    border-top: none;
    border-radius: 0 0 12px 12px;
    padding: 28px 32px;
}

/* alert */
.tph-alert-warning {
    background: linear-gradient(135deg, #fff8e1, #fff3cd);
    border-left: 4px solid #f5a623;
    border-radius: 8px;
    padding: 12px 18px;
    margin-bottom: 24px;
    font-size: 13.5px;
    color: #7a5800;
}

/* cards */
.tph-cards { display: flex; gap: 20px; flex-wrap: wrap; margin-bottom: 24px; }
.tph-card {
    flex: 1;
    min-width: 240px;
    background: #fff;
    border: 1px solid #e2e6f0;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}
.tph-card-head {
    background: linear-gradient(90deg, #7b2fff22, #e91e8c11);
    border-bottom: 1px solid #e2e6f0;
    padding: 11px 18px;
    font-weight: 700;
    font-size: 13px;
    color: #16213e;
    display: flex;
    align-items: center;
    gap: 8px;
}
.tph-card-head .dot {
    width: 8px; height: 8px; border-radius: 50%;
    background: linear-gradient(135deg, #7b2fff, #e91e8c);
}
.tph-card-body { padding: 18px; }

/* table */
.tph-table { width: 100%; border-collapse: collapse; font-size: 13.5px; }
.tph-table tr td { padding: 8px 4px; vertical-align: middle; }
.tph-table tr td:first-child { color: #666; white-space: nowrap; width: 90px; font-weight: 600; }
.tph-table code {
    background: #f0f2f8;
    border: 1px solid #dde1ee;
    border-radius: 5px;
    padding: 2px 8px;
    font-size: 12px;
    color: #333;
    word-break: break-all;
}
.tph-copy-btn {
    margin-left: 8px;
    background: linear-gradient(135deg, #7b2fff, #e91e8c);
    color: #fff;
    border: none;
    border-radius: 5px;
    padding: 3px 10px;
    font-size: 11px;
    cursor: pointer;
    transition: opacity 0.2s;
}
.tph-copy-btn:hover { opacity: 0.85; }

/* balance */
.tph-balance-wrap { text-align: center; padding: 10px 0; }
.tph-balance-amount {
    font-size: 42px;
    font-weight: 800;
    background: linear-gradient(135deg, #7b2fff, #e91e8c);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    line-height: 1.1;
}
.tph-balance-label { color: #888; font-size: 13px; margin-top: 6px; }
.tph-balance-note { font-size: 12px; color: #aaa; margin-top: 8px; }

/* info boxes */
.tph-info-box {
    background: #fff;
    border: 1px solid #e2e6f0;
    border-radius: 10px;
    margin-bottom: 20px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}
.tph-info-head {
    background: linear-gradient(90deg, #7b2fff22, #e91e8c11);
    border-bottom: 1px solid #e2e6f0;
    padding: 11px 18px;
    font-weight: 700;
    font-size: 13px;
    color: #16213e;
    display: flex;
    align-items: center;
    gap: 8px;
}
.tph-info-head .dot {
    width: 8px; height: 8px; border-radius: 50%;
    background: linear-gradient(135deg, #7b2fff, #e91e8c);
}
.tph-info-body { padding: 18px 20px; font-size: 13.5px; line-height: 1.8; color: #444; }
.tph-info-body ol { padding-left: 20px; margin: 0; }
.tph-info-body li { margin-bottom: 6px; }
.tph-info-body p { margin: 0 0 8px; }

/* footer */
.tph-footer {
    margin-top: 24px;
    text-align: center;
    font-size: 12px;
    color: #aaa;
}
.tph-footer a { color: #7b2fff; text-decoration: none; font-weight: 600; }
.tph-footer a:hover { color: #e91e8c; }

/* error */
.tph-error {
    background: #fff5f5;
    border-left: 4px solid #e53e3e;
    border-radius: 8px;
    padding: 14px 18px;
    color: #c53030;
    font-size: 13.5px;
}
</style>

<div class="tph-wrap">

    {{* ── Header with Logo ── *}}
    <div class="tph-header">
        <div class="tph-logo">
            {{* TPHOST logo — inline SVG recreation matching brand colours *}}
            <svg viewBox="0 0 520 110" xmlns="http://www.w3.org/2000/svg" aria-label="The PowerHost">
                <defs>
                    <linearGradient id="tph-g1" x1="0%" y1="0%" x2="100%" y2="0%">
                        <stop offset="0%"   stop-color="#7b2fff"/>
                        <stop offset="50%"  stop-color="#c42d8e"/>
                        <stop offset="100%" stop-color="#e91e8c"/>
                    </linearGradient>
                    <linearGradient id="tph-g2" x1="0%" y1="0%" x2="100%" y2="0%">
                        <stop offset="0%"   stop-color="#e91e8c"/>
                        <stop offset="100%" stop-color="#ff4b6e"/>
                    </linearGradient>
                </defs>
                {{* T *}}
                <rect x="2"  y="6"  width="68" height="16" rx="3" fill="url(#tph-g1)"/>
                <rect x="26" y="6"  width="20" height="98" rx="3" fill="url(#tph-g1)"/>
                {{* P *}}
                <rect x="78" y="6"  width="16" height="98" rx="3" fill="url(#tph-g1)"/>
                <path d="M78 6 h34 a24 24 0 0 1 0 48 h-34 z" rx="3" fill="url(#tph-g1)"/>
                <rect x="88" y="16" width="26" height="28" rx="12" fill="#1a1a2e"/>
                {{* H *}}
                <rect x="142" y="6"  width="16" height="98" rx="3" fill="url(#tph-g2)"/>
                <rect x="142" y="48" width="52" height="16" rx="3" fill="url(#tph-g2)"/>
                <rect x="178" y="6"  width="16" height="98" rx="3" fill="url(#tph-g2)"/>
                {{* O *}}
                <rect x="204" y="6"  width="58" height="98" rx="29" fill="url(#tph-g2)"/>
                <rect x="218" y="22" width="30" height="66" rx="15" fill="#1a1a2e"/>
                {{* S *}}
                <path d="M278 6 h52 a10 10 0 0 1 0 20 h-38 a14 14 0 0 0 0 28 h38 a10 10 0 0 1 0 20 h-52 a10 10 0 0 1 0-20 h38 a14 14 0 0 0 0-28 h-38 a10 10 0 0 1 0-20 z" fill="url(#tph-g2)"/>
                <path d="M278 54 h52 a10 10 0 0 1 0 20 h-38 a14 14 0 0 0 0 30 h38 a10 10 0 0 1 0 20 h-52 a10 10 0 0 1 0-20 h38 a14 14 0 0 0 0-30 h-38 a10 10 0 0 1 0-20 z" fill="url(#tph-g2)"/>
                <path d="M278 6 h52 q10 0 10 10 q0 10-10 10 h-38 q-14 0-14 14 q0 14 14 14 h38 q10 0 10 10 q0 10-10 10 h-52 q-14 0-14-14 v-30 q0-14 14-14 z" fill="url(#tph-g2)"/>
                <path d="M278 64 h52 q10 0 10 10 q0 10-10 10 h-38 q-14 0-14 15 q0 15 14 15 h38 q10 0 10 10 q0 10-10 10 h-52 q-14 0-14-15 v-31 q0-14 14-14 z" fill="url(#tph-g2)"/>
                {{* T *}}
                <rect x="352" y="6"  width="78" height="16" rx="3" fill="url(#tph-g2)"/>
                <rect x="381" y="6"  width="20" height="98" rx="3" fill="url(#tph-g2)"/>
            </svg>
        </div>
        <div class="tph-badge">Domain Reseller &nbsp;|&nbsp; <span>v2.1.7</span></div>
    </div>

    <div class="tph-body">

        {{* ── Test mode warning ── *}}
        {if $test_mode}
        <div class="tph-alert-warning">
            ⚠️ &nbsp;<strong>Test Mode Enabled</strong> — All API calls are being logged for debugging.
        </div>
        {/if}

        {{* ── Error state ── *}}
        {if $error}
        <div class="tph-error">
            ⚠️ &nbsp;{$error}
        </div>
        {else}

        {{* ── API + Balance cards ── *}}
        <div class="tph-cards">

            {{* API Connection *}}
            <div class="tph-card">
                <div class="tph-card-head"><span class="dot"></span> API Connection</div>
                <div class="tph-card-body">
                    <table class="tph-table">
                        <tr>
                            <td>API URL</td>
                            <td><code>{$api_url}</code></td>
                        </tr>
                        <tr>
                            <td>API Key</td>
                            <td>
                                <code id="tphApiKey">{$api_key}</code>
                                <button class="tph-copy-btn" onclick="tphCopy('tphApiKey', this)">Copy</button>
                            </td>
                        </tr>
                        <tr>
                            <td>Secret</td>
                            <td><code style="color:#aaa;">●●●●●●●●●●●●</code></td>
                        </tr>
                    </table>
                </div>
            </div>

            {{* Balance *}}
            <div class="tph-card">
                <div class="tph-card-head"><span class="dot"></span> Account Balance</div>
                <div class="tph-card-body">
                    <div class="tph-balance-wrap">
                        <div class="tph-balance-amount">
                            {if $balance !== 'N/A'}
                                ${$balance|number_format:2}
                            {else}
                                {$balance}
                            {/if}
                        </div>
                        <div class="tph-balance-label">Available Credits</div>
                        <div class="tph-balance-note">Contact The PowerHost to top up your balance</div>
                    </div>
                </div>
            </div>

        </div>{{* /tph-cards *}}

        {{* ── How It Works ── *}}
        <div class="tph-info-box">
            <div class="tph-info-head"><span class="dot"></span> How It Works</div>
            <div class="tph-info-body">
                <ol>
                    <li><strong>Domain Registration:</strong> When your customers register domains, requests are forwarded securely to The PowerHost API.</li>
                    <li><strong>Automatic Deduction:</strong> Credits are instantly deducted from your reseller balance on registration, transfer, or renewal.</li>
                    <li><strong>Domain Management:</strong> All operations — renewals, transfers, nameserver changes, EPP codes — are handled via the API.</li>
                    <li><strong>Auto Nameserver Sync:</strong> Nameservers are pushed automatically to the registry right after every successful registration.</li>
                </ol>
            </div>
        </div>

        {{* ── Support ── *}}
        <div class="tph-info-box">
            <div class="tph-info-head"><span class="dot"></span> Need Help?</div>
            <div class="tph-info-body">
                <p><strong>Add Credits:</strong> Log in to your reseller dashboard at <a href="https://thepowerhost.in/" target="_blank" style="color:#7b2fff;font-weight:600;">thepowerhost.in</a> to top up your balance.</p>
                <p><strong>API Issues:</strong> Verify your API Key and Secret are correctly saved in the registrar configuration.</p>
                <p><strong>Low Balance:</strong> Ensure sufficient credits are available before processing domain orders to avoid failures.</p>
            </div>
        </div>

        {/if}{{* /if error *}}

        {{* ── Footer ── *}}
        <div class="tph-footer">
            Powered by <a href="https://thepowerhost.in/" target="_blank">The PowerHost</a>
            &nbsp;·&nbsp; Domain Reseller Module v2.1.7
        </div>

    </div>{{* /tph-body *}}
</div>{{* /tph-wrap *}}

<script>
function tphCopy(elementId, btn) {
    var text = document.getElementById(elementId).textContent.trim();
    var original = btn.textContent;
    if (navigator.clipboard) {
        navigator.clipboard.writeText(text).then(function() {
            btn.textContent = 'Copied!';
            setTimeout(function(){ btn.textContent = original; }, 1800);
        });
    } else {
        var ta = document.createElement('textarea');
        ta.value = text;
        document.body.appendChild(ta);
        ta.select();
        document.execCommand('copy');
        document.body.removeChild(ta);
        btn.textContent = 'Copied!';
        setTimeout(function(){ btn.textContent = original; }, 1800);
    }
}
</script>
