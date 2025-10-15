<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreAttachmentRequest;
use App\Models\Attachment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class AttachmentController extends Controller
{
    /**
     * Store a newly created attachment.
     */
    public function store(StoreAttachmentRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $attachableType = $validated['attachable_type'];
        $attachableId = $validated['attachable_id'];

        // Get the attachable model instance
        $attachable = $attachableType::findOrFail($attachableId);

        // Handle multiple file uploads
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $originalName = $file->getClientOriginalName();
                $path = $file->store('', 'attachments');

                Attachment::create([
                    'attachable_type' => $attachableType,
                    'attachable_id' => $attachableId,
                    'file_name' => $originalName,
                    'file_path' => $path,
                    'file_size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'uploaded_by' => auth()->id(),
                ]);
            }
        }

        return back()->with('success', 'Files uploaded successfully.');
    }

    /**
     * Download the specified attachment.
     */
    public function download(Attachment $attachment): StreamedResponse
    {
        $this->authorize('download', $attachment);

        return Storage::disk('attachments')->download(
            $attachment->file_path,
            $attachment->file_name
        );
    }

    /**
     * Remove the specified attachment.
     */
    public function destroy(Attachment $attachment): RedirectResponse
    {
        $this->authorize('delete', $attachment);

        $attachment->delete();

        return back()->with('success', 'Attachment deleted successfully.');
    }
}
