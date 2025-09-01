<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketUpdated extends Notification
{
    use Queueable;

    public function __construct(
        public Ticket $ticket,
        public string $message,
        public array $changes = [],
        public int $actorId,
        public string $actorName
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array<int, string>
     */
    public function via($notifiable): array
    {
        $channels = ['database'];
        if ($this->shouldSendMail($notifiable)) {
            $channels[] = 'mail';
        }
        return $channels;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     */
    public function toMail($notifiable): MailMessage
    {
        $ticket = $this->ticket->loadMissing(['project', 'user', 'assignedUser', 'parent']);
        $url = route('tickets.show', $ticket);
        $subject = 'Ticket updated: ' . $ticket->number . ' — ' . $ticket->title;

        $changesSummary = collect($this->changes)
            ->map(function (array $change): string {
                $field = $change['field'] ?? 'field';
                $old = array_key_exists('old', $change) ? ($change['old'] === null ? '—' : (string) $change['old']) : '—';
                $new = array_key_exists('new', $change) ? ($change['new'] === null ? '—' : (string) $change['new']) : '—';
                return "$field: $old → $new";
            })
            ->implode(', ');

        $meta = [
            'Ticket' => $ticket->number . ' — ' . $ticket->title,
            'Project' => optional($ticket->project)->name,
            'Status' => ucfirst((string) $ticket->status),
            'Priority' => ucfirst((string) $ticket->priority),
            'Type' => $ticket->type ? ucfirst((string) $ticket->type) : '—',
            'Severity' => $ticket->severity ? ucfirst((string) $ticket->severity) : '—',
            'Assignee' => optional($ticket->assignedUser)->name ?? 'Unassigned',
            'Reporter' => optional($ticket->user)->name ?? '—',
            'Due' => $ticket->due_date ? $ticket->due_date->toDateString() : '—',
            'Estimate' => $this->formatMinutes($ticket->estimate_minutes),
            'Labels' => $ticket->labels ?: '—',
            'Parent' => optional($ticket->parent)->number
                ? ($ticket->parent->number . ' — ' . $ticket->parent->title)
                : '—',
            'Updated' => optional($ticket->updated_at)?->toDayDateTimeString(),
        ];

        return (new MailMessage)
            ->subject($subject)
            ->view('emails.ticket_updated', [
                'appName' => config('app.name'),
                'notifiable' => $notifiable,
                'ticket' => $ticket,
                'messageText' => $this->message,
                'actorName' => $this->actorName,
                'url' => $url,
                'meta' => $meta,
                'changes' => $this->changes,
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     */
    public function toArray($notifiable): array
    {
        return [
            'ticket_id' => $this->ticket->id,
            'ticket_number' => $this->ticket->number,
            'ticket_title' => $this->ticket->title,
            'project_id' => $this->ticket->project_id,
            'changes' => $this->changes,
            'actor_id' => $this->actorId,
            'actor_name' => $this->actorName,
            'message' => $this->message,
            'url' => route('tickets.show', $this->ticket),
        ];
    }

    /**
     * Determine whether to include the mail channel.
     */
    protected function shouldSendMail($notifiable): bool
    {
        $default = config('mail.default');
        if (!$default) {
            return false;
        }
        $mailer = config("mail.mailers.$default");
        if (empty($mailer)) {
            return false;
        }
        if (!config('mail.from.address')) {
            return false;
        }
        $email = $notifiable->email ?? null;
        return !empty($email);
    }

    /**
     * Format minutes to H:MM.
     */
    protected function formatMinutes(?int $minutes): string
    {
        if (empty($minutes)) {
            return '—';
        }
        $h = intdiv($minutes, 60);
        $m = $minutes % 60;
        return sprintf('%d:%02d', $h, $m);
    }
}
