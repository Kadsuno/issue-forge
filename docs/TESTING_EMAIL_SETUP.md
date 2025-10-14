# Testing Email Setup

This document provides instructions for testing the email configuration after the SMTP provider setup.

## Prerequisites

- DDEV must be running properly
- If you encounter router issues, try:
    ```bash
    ddev poweroff
    docker stop ddev-router && docker rm ddev-router
    ddev start
    ```

## Running Tests

### 1. PHP Linting (Pint)

Check code formatting:

```bash
ddev exec vendor/bin/pint --test
```

Auto-fix formatting issues:

```bash
ddev exec vendor/bin/pint
```

### 2. Run Email Configuration Tests

Run all email-related tests:

```bash
ddev exec vendor/bin/phpunit --filter EmailConfigurationTest --colors=always
```

Run specific test:

```bash
ddev exec vendor/bin/phpunit tests/Feature/EmailConfigurationTest.php --colors=always
```

### 3. Run All Feature Tests

```bash
ddev exec vendor/bin/phpunit --colors=always
```

### 4. Manual Email Testing

#### Test with Mailpit (Development)

1. Ensure DDEV is running:

    ```bash
    ddev start
    ```

2. Access Mailpit UI:

    ```
    http://issue-forge.ddev.site:8025
    ```

3. Send a test email via Tinker:

    ```bash
    ddev exec php artisan tinker
    ```

    Then in Tinker:

    ```php
    $user = App\Models\User::first();
    $ticket = App\Models\Ticket::first();
    $comment = $ticket->comments()->first();

    $user->notify(new App\Notifications\TicketCommentAdded($ticket, $comment, 'Test User'));
    ```

4. Check Mailpit UI to see the email

#### Test with Brevo (Staging/Production)

1. Configure `.env` with Brevo credentials (see `docs/EMAIL_SETUP.md`)

2. Clear config cache:

    ```bash
    php artisan config:clear
    ```

3. Send test email:

    ```bash
    php artisan tinker
    ```

    ```php
    Mail::raw('Test email from IssueForge', function ($message) {
        $message->to('your-verified-email@example.com')
                ->subject('IssueForge Test Email');
    });
    ```

4. Check:
    - Your email inbox
    - Brevo dashboard (Statistics → Email)
    - Laravel logs: `tail -f storage/logs/laravel.log`

## Troubleshooting

### DDEV Router Issues

If you see "ddev-router failed to become ready":

```bash
# Check router logs
docker logs ddev-router

# Remove old router configs
docker stop ddev-router && docker rm ddev-router

# Power off all DDEV projects
ddev poweroff

# Restart just your project
ddev start
```

### Configuration Cache Issues

Always clear cache after changing `.env`:

```bash
ddev exec php artisan config:clear
```

### Test Database Issues

Refresh the test database:

```bash
ddev exec php artisan migrate:fresh --seed --env=testing
```

## Expected Test Results

All tests in `EmailConfigurationTest.php` should pass:

- ✅ Mail configuration loads from environment
- ✅ SMTP mailer has security settings
- ✅ From address and name are configured
- ✅ Brevo service configuration exists
- ✅ Email notification uses correct from address
- ✅ Notification checks mail configuration
- ✅ Notification skips mail when not configured
- ✅ Notification skips mail when user has no email
- ✅ Development mail configuration
- ✅ Mail encryption is valid
- ✅ Mail port is valid

## Known Issues

1. **DDEV Router Timeout**: If you see "EntryPoint doesn't exist" errors in router logs, there may be conflicting DDEV project configurations. Try `ddev poweroff` to clear all projects.

2. **Port Conflicts**: Ensure ports 80, 443, 8025, and 5173 are available.

3. **Email Not Sending in Tests**: Use `Mail::fake()` in tests to avoid actual email sending.

## Next Steps

After all tests pass:

1. Configure DNS records for your domain (SPF, DKIM, DMARC) - see `docs/EMAIL_SETUP.md`
2. Set up Brevo account and obtain SMTP credentials
3. Configure production environment variables
4. Test email delivery in staging environment
5. Monitor email deliverability in Brevo dashboard
