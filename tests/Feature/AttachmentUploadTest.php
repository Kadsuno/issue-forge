<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Attachment;
use App\Models\Project;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

final class AttachmentUploadTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('attachments');
    }

    public function test_authenticated_user_can_upload_attachment_to_ticket(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);
        $ticket = Ticket::factory()->create(['project_id' => $project->id, 'user_id' => $user->id]);

        $file = UploadedFile::fake()->image('test.jpg', 100, 100);

        $response = $this->actingAs($user)->post(route('attachments.store'), [
            'files' => [$file],
            'attachable_type' => Ticket::class,
            'attachable_id' => $ticket->id,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('attachments', [
            'attachable_type' => Ticket::class,
            'attachable_id' => $ticket->id,
            'uploaded_by' => $user->id,
        ]);

        $attachment = Attachment::where('attachable_id', $ticket->id)->first();
        $this->assertNotNull($attachment);
        $this->assertEquals('test.jpg', $attachment->file_name);

        Storage::disk('attachments')->assertExists($attachment->file_path);
    }

    public function test_authenticated_user_can_upload_attachment_to_project(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);

        $file = UploadedFile::fake()->create('document.pdf', 100, 'application/pdf');

        $response = $this->actingAs($user)->post(route('attachments.store'), [
            'files' => [$file],
            'attachable_type' => Project::class,
            'attachable_id' => $project->id,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('attachments', [
            'attachable_type' => Project::class,
            'attachable_id' => $project->id,
            'uploaded_by' => $user->id,
        ]);
    }

    public function test_can_upload_multiple_files(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);
        $ticket = Ticket::factory()->create(['project_id' => $project->id, 'user_id' => $user->id]);

        $files = [
            UploadedFile::fake()->image('image1.jpg'),
            UploadedFile::fake()->image('image2.png'),
            UploadedFile::fake()->create('document.pdf', 100, 'application/pdf'),
        ];

        $response = $this->actingAs($user)->post(route('attachments.store'), [
            'files' => $files,
            'attachable_type' => Ticket::class,
            'attachable_id' => $ticket->id,
        ]);

        $response->assertRedirect();

        $this->assertEquals(3, Attachment::where('attachable_id', $ticket->id)->count());
    }

    public function test_validates_file_size(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);
        $ticket = Ticket::factory()->create(['project_id' => $project->id, 'user_id' => $user->id]);

        // Create a file larger than 10MB (10240 KB)
        $file = UploadedFile::fake()->create('large.pdf', 10241, 'application/pdf');

        $response = $this->actingAs($user)->post(route('attachments.store'), [
            'files' => [$file],
            'attachable_type' => Ticket::class,
            'attachable_id' => $ticket->id,
        ]);

        $response->assertSessionHasErrors('files.0');
        $this->assertEquals(0, Attachment::count());
    }

    public function test_validates_file_type(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);
        $ticket = Ticket::factory()->create(['project_id' => $project->id, 'user_id' => $user->id]);

        // Create an invalid file type
        $file = UploadedFile::fake()->create('script.exe', 100, 'application/x-msdownload');

        $response = $this->actingAs($user)->post(route('attachments.store'), [
            'files' => [$file],
            'attachable_type' => Ticket::class,
            'attachable_id' => $ticket->id,
        ]);

        $response->assertSessionHasErrors('files.0');
        $this->assertEquals(0, Attachment::count());
    }

    public function test_guest_cannot_upload_attachments(): void
    {
        $project = Project::factory()->create();
        $ticket = Ticket::factory()->create(['project_id' => $project->id]);
        $file = UploadedFile::fake()->image('test.jpg');

        $response = $this->post(route('attachments.store'), [
            'files' => [$file],
            'attachable_type' => Ticket::class,
            'attachable_id' => $ticket->id,
        ]);

        $response->assertRedirect(route('login'));
        $this->assertEquals(0, Attachment::count());
    }

    public function test_user_can_download_attachment(): void
    {
        Storage::fake('attachments');

        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);
        $ticket = Ticket::factory()->create(['project_id' => $project->id, 'user_id' => $user->id]);

        $file = UploadedFile::fake()->image('test.jpg');
        $path = $file->store('', 'attachments');

        $attachment = Attachment::create([
            'attachable_type' => Ticket::class,
            'attachable_id' => $ticket->id,
            'file_name' => 'test.jpg',
            'file_path' => $path,
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'uploaded_by' => $user->id,
        ]);

        $response = $this->actingAs($user)->get(route('attachments.download', $attachment));

        $response->assertOk();
        $response->assertDownload('test.jpg');
    }

    public function test_user_can_delete_own_attachment(): void
    {
        Storage::fake('attachments');

        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);
        $ticket = Ticket::factory()->create(['project_id' => $project->id, 'user_id' => $user->id]);

        $file = UploadedFile::fake()->image('test.jpg');
        $path = $file->store('', 'attachments');

        $attachment = Attachment::create([
            'attachable_type' => Ticket::class,
            'attachable_id' => $ticket->id,
            'file_name' => 'test.jpg',
            'file_path' => $path,
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'uploaded_by' => $user->id,
        ]);

        $response = $this->actingAs($user)->delete(route('attachments.destroy', $attachment));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('attachments', ['id' => $attachment->id]);
        Storage::disk('attachments')->assertMissing($path);
    }

    public function test_admin_can_delete_any_attachment(): void
    {
        Storage::fake('attachments');

        $admin = User::factory()->create(['is_admin' => true]);
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);
        $ticket = Ticket::factory()->create(['project_id' => $project->id, 'user_id' => $user->id]);

        $file = UploadedFile::fake()->image('test.jpg');
        $path = $file->store('', 'attachments');

        $attachment = Attachment::create([
            'attachable_type' => Ticket::class,
            'attachable_id' => $ticket->id,
            'file_name' => 'test.jpg',
            'file_path' => $path,
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'uploaded_by' => $user->id,
        ]);

        $response = $this->actingAs($admin)->delete(route('attachments.destroy', $attachment));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('attachments', ['id' => $attachment->id]);
    }

    public function test_user_cannot_delete_others_attachment(): void
    {
        Storage::fake('attachments');

        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $owner->id]);
        $ticket = Ticket::factory()->create(['project_id' => $project->id, 'user_id' => $owner->id]);

        $file = UploadedFile::fake()->image('test.jpg');
        $path = $file->store('', 'attachments');

        $attachment = Attachment::create([
            'attachable_type' => Ticket::class,
            'attachable_id' => $ticket->id,
            'file_name' => 'test.jpg',
            'file_path' => $path,
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'uploaded_by' => $owner->id,
        ]);

        $response = $this->actingAs($otherUser)->delete(route('attachments.destroy', $attachment));

        $response->assertForbidden();
        $this->assertDatabaseHas('attachments', ['id' => $attachment->id]);
    }
}
