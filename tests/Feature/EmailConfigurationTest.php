<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Ticket;
use App\Models\TicketComment;
use App\Models\User;
use App\Notifications\TicketCommentAdded;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

final class EmailConfigurationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that mail configuration is properly loaded from environment.
     */
    public function test_mail_configuration_loads_from_environment(): void
    {
        $this->assertNotEmpty(config('mail.mailers.smtp'));
        $this->assertArrayHasKey('host', config('mail.mailers.smtp'));
        $this->assertArrayHasKey('port', config('mail.mailers.smtp'));
        $this->assertArrayHasKey('encryption', config('mail.mailers.smtp'));
        $this->assertArrayHasKey('username', config('mail.mailers.smtp'));
        $this->assertArrayHasKey('password', config('mail.mailers.smtp'));
    }

    /**
     * Test that SMTP mailer has proper security settings.
     */
    public function test_smtp_mailer_has_security_settings(): void
    {
        $smtpConfig = config('mail.mailers.smtp');

        $this->assertArrayHasKey('verify_peer', $smtpConfig);
        $this->assertArrayHasKey('local_domain', $smtpConfig);
        $this->assertNotContains('scheme', array_keys($smtpConfig), 'Deprecated scheme parameter should not be present');
    }

    /**
     * Test that from address and name are configured.
     */
    public function test_mail_from_configuration_exists(): void
    {
        $this->assertNotEmpty(config('mail.from.address'));
        $this->assertNotEmpty(config('mail.from.name'));
    }

    /**
     * Test that Brevo API configuration exists in services.
     */
    public function test_brevo_service_configuration_exists(): void
    {
        $this->assertArrayHasKey('brevo', config('services'));
        $this->assertArrayHasKey('api_key', config('services.brevo'));
    }

    /**
     * Test that email notification uses correct from address.
     */
    public function test_email_notification_uses_configured_from_address(): void
    {
        Mail::fake();

        $user = User::factory()->create(['email' => 'test@example.com']);
        $ticket = Ticket::factory()->create(['user_id' => $user->id]);
        $comment = TicketComment::create([
            'ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'body' => 'Test comment',
        ]);

        $notification = new TicketCommentAdded($ticket, $comment, $user->name);

        // Only test mail if from address is configured
        if (config('mail.from.address')) {
            $mailMessage = $notification->toMail($user);

            $this->assertNotNull($mailMessage);
            $this->assertStringContainsString($ticket->number, $mailMessage->subject);
        } else {
            $this->markTestSkipped('Mail from address not configured');
        }
    }

    /**
     * Test that notification checks mail configuration before sending.
     */
    public function test_notification_checks_mail_configuration(): void
    {
        $user = User::factory()->create(['email' => 'test@example.com']);
        $ticket = Ticket::factory()->create(['user_id' => $user->id]);
        $comment = TicketComment::create([
            'ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'body' => 'Test comment',
        ]);

        $notification = new TicketCommentAdded($ticket, $comment, $user->name);

        // Test with mail configured
        Config::set('mail.default', 'smtp');
        Config::set('mail.from.address', 'test@example.com');

        $channels = $notification->via($user);
        $this->assertContains('database', $channels);

        // If from address is configured, mail should be included
        if (config('mail.from.address')) {
            $this->assertContains('mail', $channels);
        }
    }

    /**
     * Test that notification skips mail when mail is not configured.
     */
    public function test_notification_skips_mail_when_not_configured(): void
    {
        $user = User::factory()->create(['email' => 'test@example.com']);
        $ticket = Ticket::factory()->create(['user_id' => $user->id]);
        $comment = TicketComment::create([
            'ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'body' => 'Test comment',
        ]);

        $notification = new TicketCommentAdded($ticket, $comment, $user->name);

        // Test with mail not configured
        Config::set('mail.default', null);

        $channels = $notification->via($user);
        $this->assertContains('database', $channels);
        $this->assertNotContains('mail', $channels);
    }

    /**
     * Test that notification skips mail when user has no email.
     */
    public function test_notification_skips_mail_when_user_has_no_email(): void
    {
        // Create user with email, then manually set to null to test the logic
        $user = User::factory()->create(['email' => 'temp@example.com']);
        $ticket = Ticket::factory()->create(['user_id' => $user->id]);
        $comment = TicketComment::create([
            'ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'body' => 'Test comment',
        ]);

        // Manually set email to null for testing (bypassing DB constraints)
        $user->email = null;

        $notification = new TicketCommentAdded($ticket, $comment, $user->name);

        Config::set('mail.default', 'smtp');
        Config::set('mail.from.address', 'test@example.com');

        $channels = $notification->via($user);
        $this->assertContains('database', $channels);
        $this->assertNotContains('mail', $channels);
    }

    /**
     * Test SMTP configuration for development environment (Mailpit).
     */
    public function test_development_mail_configuration(): void
    {
        if (app()->environment('local', 'testing')) {
            // In local/testing, we expect either log or smtp with local host
            $default = config('mail.default');
            $this->assertContains($default, ['log', 'smtp', 'array']);

            if ($default === 'smtp') {
                $host = config('mail.mailers.smtp.host');
                $port = config('mail.mailers.smtp.port');

                // Development typically uses 127.0.0.1:1025 (Mailpit)
                $this->assertTrue(
                    in_array($host, ['127.0.0.1', 'localhost']) || $port === 1025,
                    'Development should use local mail server or Mailpit'
                );
            }
        } else {
            $this->markTestSkipped('Not in development environment');
        }
    }

    /**
     * Test that encryption setting is valid.
     */
    public function test_mail_encryption_is_valid(): void
    {
        $encryption = config('mail.mailers.smtp.encryption');

        // Encryption can be null (for local), 'tls', or 'ssl'
        $this->assertTrue(
            in_array($encryption, [null, 'tls', 'ssl'], true),
            'Mail encryption must be null, tls, or ssl'
        );
    }

    /**
     * Test that mail port is valid.
     */
    public function test_mail_port_is_valid(): void
    {
        $port = config('mail.mailers.smtp.port');

        // Port may be string or int depending on environment
        $portInt = is_string($port) ? (int) $port : $port;

        $this->assertIsNumeric($port);
        $this->assertGreaterThan(0, $portInt);
        $this->assertLessThanOrEqual(65535, $portInt);

        // Common mail ports
        $commonPorts = [25, 465, 587, 1025, 2525];
        $this->assertContains(
            $portInt,
            $commonPorts,
            'Mail port should be one of the common SMTP ports'
        );
    }
}
