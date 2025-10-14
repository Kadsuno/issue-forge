# Email Setup Guide

Complete guide for configuring transactional email in IssueForge using Brevo (formerly Sendinblue).

## Overview

IssueForge uses different email configurations for each environment:

- **Development (DDEV)**: Mailpit for local email testing (no external emails sent)
- **Staging/Production**: Brevo SMTP for reliable transactional email delivery

## Selected Provider: Brevo (Sendinblue)

**Why Brevo?**
- Free tier: 300 emails/day (~9,000/month)
- EU-based (France) with full GDPR compliance
- Supports both SMTP and HTTP API
- TLS/STARTTLS encryption
- Comprehensive dashboard for deliverability, bounces, and logs
- Supports custom From name, reply-to addresses, and metadata/tags
- Excellent domain verification with SPF/DKIM guidance

**Target volume**: 100–1,000 emails/month with burst capacity

---

## 1. Brevo Account Setup

### Step 1: Create Account

1. Go to [https://www.brevo.com](https://www.brevo.com)
2. Sign up for a free account
3. Verify your email address
4. Complete the account setup wizard

### Step 2: Generate SMTP Credentials

1. Log into your Brevo dashboard
2. Navigate to **Settings** → **SMTP & API**
3. Click **SMTP** tab
4. Note your **SMTP server** credentials:
   - **Server**: `smtp-relay.brevo.com`
   - **Port**: `587` (recommended) or `465` (SSL)
   - **Login**: Your Brevo email address
   - **SMTP Key**: Generate a new SMTP key (this is your password)

5. **Important**: Save the SMTP key immediately - it's only shown once!

---

## 2. Domain Verification

To ensure high deliverability and avoid spam filters, verify your sending domain.

### Step 1: Add Your Domain in Brevo

1. Go to **Settings** → **Senders, Domains & Dedicated IPs**
2. Click **Domains** tab
3. Click **Add a domain**
4. Enter your domain (e.g., `issueforge.com`)

### Step 2: Configure DNS Records

Brevo will provide you with DNS records to add to your domain. You'll need to add:

#### SPF Record
Authenticates your domain to send emails via Brevo.

**Type**: TXT  
**Name**: `@` (or your domain root)  
**Value**: `v=spf1 include:spf.brevo.com ~all`

If you already have an SPF record, add `include:spf.brevo.com` to the existing record:
```
v=spf1 include:spf.brevo.com include:other-service.com ~all
```

#### DKIM Record
Adds a digital signature to verify email authenticity.

**Type**: TXT  
**Name**: `mail._domainkey` (Brevo will provide the exact name)  
**Value**: Brevo will provide the full DKIM key (starts with `v=DKIM1`)

#### DMARC Record (Recommended)
Protects your domain from spoofing.

**Type**: TXT  
**Name**: `_dmarc`  
**Value**: `v=DMARC1; p=none; rua=mailto:dmarc@yourdomain.com`

For stricter policy after testing:
```
v=DMARC1; p=quarantine; rua=mailto:dmarc@yourdomain.com; pct=100
```

### Step 3: Verify DNS Records

After adding DNS records (propagation can take 24-48 hours):

1. Return to Brevo dashboard
2. Click **Verify** next to your domain
3. Brevo will check the DNS records

**Verify DNS propagation** using command line:
```bash
# Check SPF
dig TXT yourdomain.com +short

# Check DKIM
dig TXT mail._domainkey.yourdomain.com +short

# Check DMARC
dig TXT _dmarc.yourdomain.com +short
```

Or use online tools:
- [MXToolbox](https://mxtoolbox.com/SuperTool.aspx)
- [DNS Checker](https://dnschecker.org/)

---

## 3. Environment Configuration

### Development (DDEV with Mailpit)

Mailpit is pre-configured in DDEV and runs automatically.

**Environment variables** (set in `.ddev/config.yaml`):
```yaml
web_environment:
  - MAIL_MAILER=smtp
  - MAIL_HOST=127.0.0.1
  - MAIL_PORT=1025
  - MAIL_ENCRYPTION=null
  - MAIL_FROM_ADDRESS=dev@issue-forge.ddev.site
  - MAIL_FROM_NAME=IssueForge Dev
```

**Access Mailpit UI**: [http://issue-forge.ddev.site:8025](http://issue-forge.ddev.site:8025)

All emails sent during development are captured by Mailpit and can be viewed in the web interface.

### Staging/Production (Brevo SMTP)

Create or update your `.env` file with:

```env
# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp-relay.brevo.com
MAIL_PORT=587
MAIL_USERNAME=your-brevo-login-email@example.com
MAIL_PASSWORD=your-brevo-smtp-key-here
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=no-reply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"

# Optional: Brevo API Key (for HTTP fallback)
BREVO_API_KEY=your-api-key-here
```

**Security Notes**:
- Never commit `.env` to version control
- Use your CI/CD platform's secrets management for these values
- Rotate SMTP keys periodically
- Use different SMTP keys for staging and production

**After updating `.env`**, clear the config cache:
```bash
php artisan config:clear
```

Or in DDEV:
```bash
ddev exec php artisan config:clear
```

---

## 4. Testing Email Delivery

### Test in Development (Mailpit)

1. Start DDEV: `ddev start`
2. Send a test email using Tinker:
   ```bash
   ddev exec php artisan tinker
   ```

3. In Tinker, send a test notification:
   ```php
   $user = App\Models\User::first();
   $ticket = App\Models\Ticket::first();
   $comment = $ticket->comments()->first();
   
   $user->notify(new App\Notifications\TicketCommentAdded($ticket, $comment, 'Test User'));
   ```

4. Open Mailpit: [http://issue-forge.ddev.site:8025](http://issue-forge.ddev.site:8025)
5. Verify the email appears with correct content and dark theme styling

### Test in Staging/Production

**Important**: Only send test emails to verified email addresses to avoid high bounce rates.

1. Ensure `.env` is configured with Brevo credentials
2. Clear config cache: `php artisan config:clear`
3. Send a test email:
   ```bash
   php artisan tinker
   ```
   ```php
   Mail::raw('Test email from IssueForge', function ($message) {
       $message->to('your-verified-email@example.com')
               ->subject('IssueForge Test Email');
   });
   ```

4. Check your inbox for the email
5. Verify in Brevo dashboard:
   - Go to **Statistics** → **Email**
   - Check delivery status, opens, clicks

### View Logs

**Laravel logs**:
```bash
# DDEV
ddev exec tail -f storage/logs/laravel.log

# Production
tail -f storage/logs/laravel.log
```

**Brevo dashboard**:
- **Statistics** → **Email** for delivery metrics
- **Logs** → **Email Activity** for detailed send logs

---

## 5. CI/CD Secrets Configuration

Add these secrets to your CI/CD platform (GitHub Actions, GitLab CI, etc.):

| Secret Name | Description | Example Value |
|-------------|-------------|---------------|
| `MAIL_USERNAME` | Brevo SMTP login email | `your-email@example.com` |
| `MAIL_PASSWORD` | Brevo SMTP key | `xkeysib-...` |
| `MAIL_FROM_ADDRESS` | Verified sender email | `no-reply@yourdomain.com` |
| `MAIL_FROM_NAME` | Sender name | `IssueForge` |
| `BREVO_API_KEY` | (Optional) Brevo API key | `xkeysib-...` |

**GitHub Actions Example**:
```yaml
env:
  MAIL_MAILER: smtp
  MAIL_HOST: smtp-relay.brevo.com
  MAIL_PORT: 587
  MAIL_USERNAME: ${{ secrets.MAIL_USERNAME }}
  MAIL_PASSWORD: ${{ secrets.MAIL_PASSWORD }}
  MAIL_ENCRYPTION: tls
  MAIL_FROM_ADDRESS: ${{ secrets.MAIL_FROM_ADDRESS }}
  MAIL_FROM_NAME: IssueForge
```

---

## 6. Troubleshooting

### Email Not Sending

**Check 1: Config Cache**
```bash
ddev exec php artisan config:clear
```

**Check 2: Environment Variables**
```bash
ddev exec php artisan tinker
```
```php
config('mail.default');
config('mail.from.address');
config('mail.mailers.smtp');
```

**Check 3: Laravel Logs**
```bash
ddev exec tail -n 50 storage/logs/laravel.log
```

**Check 4: Brevo Dashboard**
- Go to **Logs** → **Email Activity**
- Check for rejected or bounced emails
- Verify SMTP credentials are correct

### High Bounce Rate

- Ensure all DNS records (SPF, DKIM, DMARC) are properly configured
- Only send to verified, valid email addresses
- Avoid sending to role-based addresses (e.g., admin@, noreply@)
- Check email content for spam triggers
- Warm up your domain (start with low volume, gradually increase)

### Emails Going to Spam

- Complete domain verification (SPF, DKIM, DMARC)
- Add a plain-text version of emails (Laravel does this automatically)
- Include a physical address in footer (GDPR/CAN-SPAM compliance)
- Avoid spam trigger words in subject lines
- Maintain good sender reputation (low bounce/complaint rates)

### Authentication Failed

- Verify MAIL_USERNAME and MAIL_PASSWORD in `.env`
- Ensure you're using the SMTP key, not your account password
- Check that SMTP key hasn't expired or been revoked
- Generate a new SMTP key in Brevo dashboard if needed

### Connection Timeout

- Check firewall rules allow outbound SMTP (port 587)
- Try alternative port (465 with SSL encryption)
- Verify server can reach `smtp-relay.brevo.com`
  ```bash
  telnet smtp-relay.brevo.com 587
  ```

### Local Development Issues

- Ensure DDEV is running: `ddev start`
- Check Mailpit is accessible: [http://issue-forge.ddev.site:8025](http://issue-forge.ddev.site:8025)
- Verify `.ddev/config.yaml` has correct mail environment variables
- Restart DDEV: `ddev restart`

---

## 7. Email Templates

IssueForge uses custom dark-themed email templates located in `resources/views/emails/`.

**Current templates**:
- `ticket_commented.blade.php` - Notification when a comment is added to a ticket

**Template features**:
- Modern dark theme consistent with website design
- Responsive layout
- Inline CSS for maximum email client compatibility
- Includes ticket metadata (status, priority, assignee, etc.)
- Call-to-action button with gradient styling

**Testing templates**:
1. Send test emails in development via Mailpit
2. Preview in multiple email clients (Gmail, Outlook, Apple Mail, etc.)
3. Use [Litmus](https://www.litmus.com/) or [Email on Acid](https://www.emailonacid.com/) for comprehensive testing

---

## 8. Rate Limits and Quotas

### Brevo Free Tier

- **Daily limit**: 300 emails/day
- **Monthly estimate**: ~9,000 emails/month
- **API calls**: Unlimited
- **Contact limit**: 300 contacts
- **No credit card required**

### Monitoring Usage

1. Log into Brevo dashboard
2. Go to **Settings** → **Account**
3. View **Usage** section for:
   - Daily emails sent
   - Remaining quota
   - Historical usage stats

### Upgrading

If you exceed the free tier:
- **Lite plan**: $25/month for 10,000 emails/month
- **Premium plan**: Custom pricing for higher volumes
- Pay-as-you-go available

---

## 9. GDPR Compliance

Brevo is GDPR-compliant with EU data centers. Additional steps:

1. **Privacy Policy**: Update your privacy policy to mention email communications
2. **Consent**: Ensure users opt-in to email notifications
3. **Unsubscribe**: Include unsubscribe links (Laravel handles this automatically)
4. **Data Processing Agreement**: Sign Brevo's DPA in dashboard settings
5. **Data Retention**: Configure retention policies in Brevo settings

---

## 10. Alternative Configuration (Brevo API)

**Use the Brevo API when SMTP ports (587/465) are blocked by your hosting provider.**

The API transport bypasses all firewall restrictions and is recommended for production environments with strict outbound port filtering.

### Step 1: Get Your Brevo API Key

1. Log into [Brevo dashboard](https://app.brevo.com)
2. Go to **Settings** → **SMTP & API**
3. Click **API Keys** tab
4. Click **Generate a new API key**
5. Give it a name (e.g., "IssueForge Production")
6. Copy the API key (starts with `xkeysib-`)

### Step 2: Configure Environment

Update your `.env` file:

```env
# Change mailer from smtp to brevo
MAIL_MAILER=brevo

# Add your Brevo API key
BREVO_API_KEY=xkeysib-your-api-key-here

# Keep these for sender information
MAIL_FROM_ADDRESS=support@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"
```

**Important:** Remove or comment out SMTP-specific variables:

```env
# These are not needed when using API
# MAIL_HOST=smtp-relay.brevo.com
# MAIL_PORT=587
# MAIL_USERNAME=...
# MAIL_PASSWORD=...
# MAIL_ENCRYPTION=tls
```

### Step 3: Clear Configuration Cache

```bash
php artisan config:clear
```

### Step 4: Test Email Sending

```bash
php artisan tinker
```

Then in Tinker:

```php
Mail::raw('Test email from IssueForge via API', function ($message) {
    $message->to('your-email@example.com')
            ->subject('IssueForge API Test Email');
});
```

You should receive the email within seconds. Check Brevo dashboard for delivery status.

### Advantages of API vs SMTP

✅ **No Firewall Issues** - Works on any server, no port restrictions  
✅ **Faster** - Direct API calls, no SMTP handshake overhead  
✅ **More Reliable** - Better error handling and retry logic  
✅ **Detailed Logs** - Better tracking in Brevo dashboard  
✅ **No Authentication** - Uses API key only, simpler configuration

### Production Recommendation

For production deployments, **always use the Brevo API** (`MAIL_MAILER=brevo`) instead of SMTP. It's more reliable and bypasses common hosting restrictions.

### API Usage in Code

The API transport is fully compatible with Laravel's Mail facade - no code changes needed! All your existing mail/notification code works exactly the same:

```php
// Notifications work seamlessly
$user->notify(new TicketCommentAdded($ticket, $comment, $actorName));

// Mail facade works the same
Mail::to($user)->send(new WelcomeEmail());

// Queue emails work too
Mail::to($user)->queue(new NewsletterEmail());
```

### Troubleshooting API

**Invalid API Key Error:**
- Verify your API key is correct in `.env`
- Ensure no extra spaces or quotes
- Check key starts with `xkeysib-`
- Generate a new key if needed

**Sender Domain Not Verified:**
- Complete domain verification in Brevo dashboard
- Add SPF, DKIM, and DMARC records
- Wait up to 48 hours for DNS propagation

**Rate Limits:**
- Free tier: 300 emails/day
- Check current usage in Brevo dashboard
- Upgrade if you need more capacity

---

## 11. Additional Resources

- **Brevo Documentation**: [https://developers.brevo.com/](https://developers.brevo.com/)
- **Laravel Mail Documentation**: [https://laravel.com/docs/mail](https://laravel.com/docs/mail)
- **DDEV Mail Configuration**: [https://ddev.readthedocs.io/en/stable/users/usage/developer-tools/#email-capture-and-review-mailpit](https://ddev.readthedocs.io/en/stable/users/usage/developer-tools/#email-capture-and-review-mailpit)
- **SPF Record Checker**: [https://mxtoolbox.com/spf.aspx](https://mxtoolbox.com/spf.aspx)
- **DKIM Validator**: [https://mxtoolbox.com/dkim.aspx](https://mxtoolbox.com/dkim.aspx)
- **Email Testing Tools**: [https://www.mail-tester.com/](https://www.mail-tester.com/)

---

## Summary

✅ **Development**: Use Mailpit (automatic with DDEV)  
✅ **Staging/Production**: Use Brevo SMTP  
✅ **Free tier**: 300 emails/day (~9,000/month)  
✅ **EU-based**: GDPR-compliant  
✅ **Security**: TLS encryption, SPF/DKIM/DMARC  
✅ **Monitoring**: Dashboard, logs, deliverability metrics  

For questions or issues, refer to the troubleshooting section or contact the development team.

