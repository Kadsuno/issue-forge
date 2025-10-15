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

final class AttachmentApiTest extends TestCase
{
    use RefreshDatabase;

    private string $token;

    protected function setUp(): void
    {
        parent::setUp();
        $this->token = config('api.admin_token');
        Storage::fake('attachments');
    }

    private function headers(): array
    {
        return [
            'Authorization' => 'Bearer '.$this->token,
            'Accept' => 'application/json',
        ];
    }

    public function test_can_upload_attachment_via_api(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);
        $ticket = Ticket::factory()->create(['project_id' => $project->id, 'user_id' => $user->id]);

        $file = UploadedFile::fake()->image('test.jpg');

        $response = $this->postJson(
            '/api/v1/attachments',
            [
                'files' => [$file],
                'attachable_type' => Ticket::class,
                'attachable_id' => $ticket->id,
            ],
            $this->headers()
        );

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'message',
            'data' => [
                '*' => [
                    'id',
                    'file_name',
                    'file_size',
                    'mime_type',
                    'url',
                ],
            ],
        ]);

        $this->assertDatabaseHas('attachments', [
            'attachable_type' => Ticket::class,
            'attachable_id' => $ticket->id,
            'file_name' => 'test.jpg',
        ]);
    }

    public function test_can_list_attachments_for_ticket(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);
        $ticket = Ticket::factory()->create(['project_id' => $project->id, 'user_id' => $user->id]);

        $file = UploadedFile::fake()->image('test.jpg');
        $path = $file->store('', 'attachments');

        Attachment::create([
            'attachable_type' => Ticket::class,
            'attachable_id' => $ticket->id,
            'file_name' => 'test.jpg',
            'file_path' => $path,
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'uploaded_by' => $user->id,
        ]);

        $response = $this->getJson(
            "/api/v1/tickets/{$ticket->id}/attachments",
            $this->headers()
        );

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'file_name',
                    'file_size',
                    'mime_type',
                    'url',
                    'uploaded_by',
                ],
            ],
        ]);
        $response->assertJsonCount(1, 'data');
    }

    public function test_can_list_attachments_for_project(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);

        $file = UploadedFile::fake()->image('test.jpg');
        $path = $file->store('', 'attachments');

        Attachment::create([
            'attachable_type' => Project::class,
            'attachable_id' => $project->id,
            'file_name' => 'test.jpg',
            'file_path' => $path,
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'uploaded_by' => $user->id,
        ]);

        $response = $this->getJson(
            "/api/v1/projects/{$project->id}/attachments",
            $this->headers()
        );

        $response->assertOk();
        $response->assertJsonCount(1, 'data');
    }

    public function test_can_show_single_attachment(): void
    {
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

        $response = $this->getJson(
            "/api/v1/attachments/{$attachment->id}",
            $this->headers()
        );

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                'id',
                'file_name',
                'file_size',
                'mime_type',
                'url',
                'uploaded_by',
            ],
        ]);
    }

    public function test_can_delete_attachment_via_api(): void
    {
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

        $response = $this->deleteJson(
            "/api/v1/attachments/{$attachment->id}",
            [],
            $this->headers()
        );

        $response->assertOk();
        $response->assertJson(['message' => 'Attachment deleted successfully']);

        $this->assertDatabaseMissing('attachments', ['id' => $attachment->id]);
    }

    public function test_api_validates_file_size(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);
        $ticket = Ticket::factory()->create(['project_id' => $project->id, 'user_id' => $user->id]);

        $file = UploadedFile::fake()->create('large.pdf', 10241, 'application/pdf');

        $response = $this->postJson(
            '/api/v1/attachments',
            [
                'files' => [$file],
                'attachable_type' => Ticket::class,
                'attachable_id' => $ticket->id,
            ],
            $this->headers()
        );

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('files.0');
    }

    public function test_api_validates_file_type(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);
        $ticket = Ticket::factory()->create(['project_id' => $project->id, 'user_id' => $user->id]);

        $file = UploadedFile::fake()->create('script.exe', 100, 'application/x-msdownload');

        $response = $this->postJson(
            '/api/v1/attachments',
            [
                'files' => [$file],
                'attachable_type' => Ticket::class,
                'attachable_id' => $ticket->id,
            ],
            $this->headers()
        );

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('files.0');
    }

    public function test_api_requires_authentication(): void
    {
        $response = $this->postJson('/api/v1/attachments', [
            'files' => [UploadedFile::fake()->image('test.jpg')],
            'attachable_type' => Ticket::class,
            'attachable_id' => 1,
        ]);

        $response->assertStatus(401);
    }
}
