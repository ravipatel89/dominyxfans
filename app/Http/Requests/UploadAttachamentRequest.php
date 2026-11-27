<?php

namespace App\Http\Requests;

use App\Providers\AttachmentServiceProvider;
use Illuminate\Foundation\Http\FormRequest;

class UploadAttachamentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $type = $this->route('type') ?: request()->route('type');
        $allowedExtensions = AttachmentServiceProvider::getAllowedUploadExtensions($type);

        return [
            'file' => 'required|mimes:'.$allowedExtensions.'|max:'.(string) ((int) getSetting('media.max_file_upload_size') * 1024),
        ];
    }
}
