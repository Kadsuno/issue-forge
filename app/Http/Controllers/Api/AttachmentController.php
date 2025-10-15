<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAttachmentRequest;
use App\Http\Resources\AttachmentResource;
use App\Models\Attachment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class AttachmentController extends Controller
{
    /**
     * Display a listing of attachments for a resource.
     */
    public function index(string $type, int $id): AnonymousResourceCollection
    {
        $modelClass = match ($type) {
            'tickets' => \App\Models\Ticket::class,
            'projects' => \App\Models\Project::class,
            'comments' => \App\Models\TicketComment::class,
            default => abort(404, 'Invalid resource type'),
        };

        $model = $modelClass::findOrFail($id);
        $attachments = $model->attachments()->with('uploadedBy')->latest()->get();

        return AttachmentResource::collection($attachments);
    }

    /**
     * Store a newly created attachment.
     */
    public function store(StoreAttachmentRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $attachableType = $validated['attachable_type'];
        $attachableId = $validated['attachable_id'];

        $attachable = $attachableType::findOrFail($attachableId);

        $attachments = [];

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $originalName = $file->getClientOriginalName();
                $path = $file->store('', 'attachments');

                $attachment = Attachment::create([
                    'attachable_type' => $attachableType,
                    'attachable_id' => $attachableId,
                    'file_name' => $originalName,
                    'file_path' => $path,
                    'file_size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'uploaded_by' => auth()->id() ?? 1,
                ]);

                $attachments[] = $attachment;
            }
        }

        return response()->json([
            'message' => 'Files uploaded successfully',
            'data' => AttachmentResource::collection($attachments),
        ], 201);
    }

    /**
     * Display the specified attachment.
     */
    public function show(Attachment $attachment): AttachmentResource
    {
        return new AttachmentResource($attachment->load('uploadedBy'));
    }

    /**
     * Download the specified attachment.
     */
    public function download(Attachment $attachment): StreamedResponse
    {
        // In API context with admin token, allow download
        // Otherwise, check authorization
        if (auth()->check()) {
            $this->authorize('download', $attachment);
        }

        return Storage::disk('attachments')->download(
            $attachment->file_path,
            $attachment->file_name
        );
    }

    /**
     * Remove the specified attachment.
     */
    public function destroy(Attachment $attachment): JsonResponse
    {
        // In API context with admin token, allow deletion
        // Otherwise, check authorization
        if (auth()->check()) {
            $this->authorize('delete', $attachment);
        }

        $attachment->delete();

        return response()->json([
            'message' => 'Attachment deleted successfully',
        ], 200);
    }
}
