<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\WorkflowStateController;
use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TicketCommentController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TicketWatcherController;
use App\Http\Controllers\TimeEntryController;
use App\Models\Project;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Legal pages (public) - English routes
Route::view('/imprint', 'legal.imprint')->name('imprint');
Route::view('/privacy', 'legal.privacy')->name('privacy');

// Legacy German routes → redirect to English
Route::redirect('/impressum', '/imprint');
Route::redirect('/datenschutz', '/privacy');

Route::get('/dashboard', function () {
    // Get recent activity data
    $recentProjects = Project::with('user')
        ->latest()
        ->take(3)
        ->get();

    $recentTickets = Ticket::with(['project', 'user', 'assignedUser'])
        ->latest()
        ->take(5)
        ->get();

    // Get stats
    $stats = [
        'total_projects' => Project::count(),
        'total_tickets' => Ticket::count(),
        'open_tickets' => Ticket::where('status', '!=', 'closed')->count(),
        'my_tickets' => Ticket::where('assigned_to', Auth::id())->count(),
    ];

    return view('dashboard', compact('recentProjects', 'recentTickets', 'stats'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/design-system', function () {
    return view('components.design-system');
})->middleware(['auth', 'verified'])->name('design-system');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Project routes
    Route::resource('projects', ProjectController::class)->parameters([
        'projects' => 'project:slug',
    ]);

    // Ticket routes (bind tickets by slug)
    Route::resource('projects.tickets', TicketController::class)->parameters([
        'tickets' => 'ticket:slug',
    ])->shallow();
    Route::post('tickets/{ticket}/watchers', [TicketWatcherController::class, 'store'])->name('tickets.watchers.store');
    Route::delete('tickets/{ticket}/watchers/{user}', [TicketWatcherController::class, 'destroy'])->name('tickets.watchers.destroy');
    Route::post('tickets/{ticket}/time-entries', [TimeEntryController::class, 'store'])->name('tickets.time.store');
    Route::patch('time-entries/{timeEntry}', [TimeEntryController::class, 'update'])->name('tickets.time.update');
    Route::delete('time-entries/{timeEntry}', [TimeEntryController::class, 'destroy'])->name('tickets.time.destroy');
    Route::post('tickets/{ticket}/comments', [TicketCommentController::class, 'store'])
        ->name('tickets.comments.store');
    Route::get('comments/{comment}/edit', [TicketCommentController::class, 'edit'])
        ->name('comments.edit');
    Route::patch('comments/{comment}', [TicketCommentController::class, 'update'])
        ->name('tickets.comments.update');
    Route::delete('comments/{comment}', [TicketCommentController::class, 'destroy'])
        ->name('tickets.comments.destroy');
    Route::get('/my-tickets', [TicketController::class, 'myTickets'])->name('tickets.mine');

    // Search
    Route::get('/search', SearchController::class)->name('search');

    // Reports
    Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');

    // Notifications (JSON)
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.readAll');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');

    // Attachments
    Route::post('/attachments', [AttachmentController::class, 'store'])->name('attachments.store');
    Route::get('/attachments/{attachment}/download', [AttachmentController::class, 'download'])->name('attachments.download');
    Route::delete('/attachments/{attachment}', [AttachmentController::class, 'destroy'])->name('attachments.destroy');

    // Admin routes (policy-protected in controller)
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', UserController::class)->parameters([
            'users' => 'user:slug',
        ]);

        // Workflow management
        Route::resource('workflows', WorkflowStateController::class)
            ->middleware('can:workflow.manage');
    });
});

require __DIR__.'/auth.php';
