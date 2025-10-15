@props(['attachableType', 'attachableId', 'multiple' => true])

<div x-data="{
    isDragging: false,
    files: [],
    previews: [],
    addFiles(newFiles) {
        for (let file of newFiles) {
            this.files.push(file);
            if (file.type.startsWith('image/')) {
                let reader = new FileReader();
                reader.onload = (e) => {
                    this.previews.push({ name: file.name, url: e.target.result, isImage: true });
                };
                reader.readAsDataURL(file);
            } else {
                this.previews.push({ name: file.name, url: null, isImage: false });
            }
        }
    },
    removeFile(index) {
        this.files.splice(index, 1);
        this.previews.splice(index, 1);
    },
    clearFiles() {
        this.files = [];
        this.previews = [];
    }
}" class="space-y-4">

    <!-- Drag and Drop Zone -->
    <div
        @dragover.prevent="isDragging = true"
        @dragleave.prevent="isDragging = false"
        @drop.prevent="isDragging = false; addFiles($event.dataTransfer.files)"
        :class="{ 'border-primary-500 bg-primary-500/10': isDragging }"
        class="border-2 border-dashed border-dark-400 rounded-lg p-8 text-center transition-all hover:border-primary-500 hover:bg-dark-700/50 cursor-pointer"
        @click="$refs.fileInput.click()"
    >
        <svg class="mx-auto h-12 w-12 text-dark-300" fill="none" stroke="currentColor" viewBox="0 0 48 48">
            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        <p class="mt-4 text-sm text-dark-200">
            <span class="font-semibold text-primary-400">Click to upload</span> or drag and drop
        </p>
        <p class="mt-1 text-xs text-dark-400">
            PNG, JPG, GIF, PDF, DOC, XLS, TXT, ZIP (max 10MB)
        </p>
    </div>

    <!-- Hidden File Input -->
    <input
        type="file"
        x-ref="fileInput"
        @change="addFiles($event.target.files); $event.target.value = ''"
        {{ $multiple ? 'multiple' : '' }}
        accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.xls,.xlsx,.txt,.zip,.rar"
        class="hidden"
    >

    <!-- File Previews -->
    <div x-show="previews.length > 0" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        <template x-for="(preview, index) in previews" :key="index">
            <div class="card p-3 relative group">
                <!-- Remove Button -->
                <button
                    type="button"
                    @click="removeFile(index)"
                    class="absolute -top-2 -right-2 bg-danger-500 text-white rounded-full w-6 h-6 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <!-- Image Preview -->
                <template x-if="preview.isImage">
                    <img :src="preview.url" :alt="preview.name" class="w-full h-24 object-cover rounded">
                </template>

                <!-- File Icon -->
                <template x-if="!preview.isImage">
                    <div class="w-full h-24 flex items-center justify-center bg-dark-700 rounded">
                        <svg class="w-12 h-12 text-dark-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    </div>
                </template>

                <!-- File Name -->
                <p class="mt-2 text-xs text-dark-200 truncate" :title="preview.name" x-text="preview.name"></p>
            </div>
        </template>
    </div>

    <!-- Upload Button -->
    <div x-show="files.length > 0" class="flex gap-3">
        <form action="{{ route('attachments.store') }}" method="POST" enctype="multipart/form-data" class="flex-1" x-ref="uploadForm">
            @csrf
            <input type="hidden" name="attachable_type" value="{{ $attachableType }}">
            <input type="hidden" name="attachable_id" value="{{ $attachableId }}">

            <!-- Hidden inputs for files (we'll set them via JS) -->
            <template x-for="(file, index) in files" :key="index">
                <input type="file" name="files[]" class="hidden" x-ref="'hiddenFile' + index">
            </template>

            <button
                type="button"
                @click="
                    let form = $refs.uploadForm;
                    let dt = new DataTransfer();
                    files.forEach(file => dt.items.add(file));
                    form.querySelectorAll('input[name=\'files[]\']').forEach((input, i) => {
                        let newDt = new DataTransfer();
                        if (files[i]) newDt.items.add(files[i]);
                        input.files = newDt.files;
                    });
                    form.submit();
                "
                class="btn-primary w-full"
            >
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                </svg>
                Upload <span x-text="files.length"></span> <span x-text="files.length === 1 ? 'File' : 'Files'"></span>
            </button>
        </form>

        <button
            type="button"
            @click="clearFiles()"
            class="btn-ghost"
        >
            Clear
        </button>
    </div>

    @error('files.*')
        <p class="text-danger-400 text-sm">{{ $message }}</p>
    @enderror
</div>

