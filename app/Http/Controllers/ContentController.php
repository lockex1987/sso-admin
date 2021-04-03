<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\Attachment;
use App\Models\SystemLog;
use App\Helpers\HtmlPurifier;
use App\Cache\Auth;
use Illuminate\Http\Request;
use Log;
use Storage;
use Str;
use \DOMDocument;

class ContentController extends Controller
{
    /**
     * Tìm kiếm danh sách.
     */
    public function search(Request $request)
    {
        $search = '%' . $request->search . '%';
        $status = $request->status;
        $type = $request->type;
        $size = $request->size;

        $query = Content::where(function ($query) use ($search) {
            $query->where('title', 'like', $search)
                ->orWhere('description', 'like', $search);
        });

        if (!empty($status)) {
            $query->where('status', intval($status));
        }

        if (!empty($type)) {
            $query->where('type', intval($type));
        }

        $pagi = $query
            ->orderBy('id', 'desc')
            ->paginate($size);
        return $pagi;
    }

    /**
     * Thêm mới hoặc cập nhật.
     */
    public function store(Request $request)
    {
        $id = $request->id;
        $rules = [
            'title' => 'required | unique:content,title' . (empty($id) ? '' : ',' . $id),
            'attachments.*' => 'file | mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx | max:' . (100 * 1000), // size theo kB
            'thumbnail' => 'image | mimes:jpeg,png,jpg,gif,svg | max:' . (100 * 1000) // size theo kB
        ];
        $request->validate($rules);

        if (empty($id)) {
            $content = new Content();
        } else {
            $content = Content::find($id);
        }

        $content->title = $request->title;
        $content->slug = Str::slug($request->title, '-');
        $content->description = $request->description;
        $content->status = $request->status;
        if ($content->status == 1) {
            $content->published_date = null;
        } else {
            $content->published_date = now();
        }
        $content->type = $request->type;
        $content->content = HtmlPurifier::purifyHtml($request->content);

        $content->save();

        // File đính kèm mới
        $attachments = $request->attachments;
        if (!empty($attachments)) {
            foreach ($attachments as $file) {
                // Lưu file ra ổ cứng
                $attachmentsFolder = 'attachments/content/' . $content->id;
                $path = $file->store($attachmentsFolder);

                // Lưu DB
                Attachment::insertAttachment('content', $content->id, $path, $file);
            }
        }

        // Ảnh đại diện
        if ($request->hasFile('thumbnail')) {
            // Xóa file cũ
            if ($content->thumbnail) {
                Storage::delete($content->thumbnail);
            }

            // Lưu file mới
            $thumbnail = $request->thumbnail;
            $thumbnailFileName = $content->id . '.' . $thumbnail->getClientOriginalExtension();
            $thumbnailFolder = 'attachments/content/' . $content->id . '/thumbnail';
            $path = $thumbnail->storeAs($thumbnailFolder, $thumbnailFileName);

            // Cập nhật DB
            $content->thumbnail = '/storage/' . $thumbnailFolder . '/' . $thumbnailFileName;
            $content->save();
        }

        // Xóa file đính kèm cũ
        $deletedAttachments = $request->deletedAttachments;
        if (!empty($deletedAttachments)) {
            foreach ($deletedAttachments as $attachmentId) {
                $attachment = Attachment::find($attachmentId);

                // Xóa file
                Storage::delete($attachment->path);

                // Xóa DB
                $attachment->delete();
            }
        }

        // Lưu các ảnh trong bài viết
        list($hasChange, $newHtml) = $this->saveImages($content->content, $content->id);
        if ($hasChange) {
            $content->content = $newHtml;
            $content->save();
        }

        SystemLog::insertSystemLog(
            $request,
            empty($id) ? 'create_content' : 'update_content',
            Auth::user()->id,
            'Tiêu đề "' . $content->title . '"'
        );

        return [
            'code' => 0,
            'message' => 'Stored'
        ];
    }

    /**
     * Lấy ra dữ liệu của một nội dung, bao gồm cả trường content.
     */
    public function getOne(Content $content)
    {
        $content->makeVisible(['content']);
        $content->attachments = Attachment::getAttachments('content', $content->id);
        // $content->images = array_values(array_diff(scandir(storage_path('app/public/attachments/content/' . $content->id . '/images')), ['.', '..']));
        $content->images = Storage::files('attachments/content/' . $content->id . '/images');
        return $content;
    }

    /**
     * Xóa.
     */
    public function destroy(Content $content)
    {
        // Xóa thư mục chứa file đính kèm
        $attachmentsFolder = 'attachments/content/' . $content->id;
        Storage::deleteDirectory($attachmentsFolder);

        // Xóa các bản ghi đính kèm
        Attachment::deleteAttachments('content', $content->id);

        $content->delete();
        return [
            'code' => 0,
            'message' => 'Deleted'
        ];
    }

    /**
     * Cập nhật trạng thái.
     */
    public function changeStatus(Content $content, Request $request)
    {
        $status = intval($request->status);
        $content->status = $status;
        if ($status == 1) {
            $content->published_date = null;
        } else {
            $content->published_date = now();
        }
        $content->save();
        return [
            'code' => 0,
            'message' => 'Updated'
        ];
    }

    /**
     * Xóa ảnh trong bài viết.
     */
    public function deleteImage(Request $request)
    {
        // $contentId = $request->contentId;
        $image = $request->image;
        // $path = 'attachments/content/' . $contentId . '/images/' . $image;
        // Storage::delete($path);
        Storage::delete($image);
        return [
            'code' => 0,
            'message' => 'Deleted'
        ];
    }

    /**
     * Xử lý ảnh.
     */
    private function saveImages($html, $contentId)
    {
        $doc = new DOMDocument();
		@ $doc->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
        

        // Lặp qua các ảnh, decode src dạng base64 và lưu ảnh lên server
        // Thay thế src dạng base64 thành dạng URL
        // Cần quản lý các ảnh này
        // - Khi xóa bài viết thì cũng xóa ảnh
        // - Lưu ảnh của bài viết ở attachments/content/<content_id>/images
        // Liệt kê các ảnh cũ của bài viết, cho phép xóa
        $hasChange = false;
        $images = $doc->getElementsByTagName('img');
		foreach ($images as $k => $img){
            $src = $img->getAttribute('src');
            
            if (strpos($src, ';') !== false) {
                $hasChange = true;
                list(, $base64) = explode(';', $src);
                list(, $data) = explode(',', $base64);

                $tempPath = 'attachments/content/' . $contentId . '/images';
                $imageName = $tempPath . '/' . Str::uuid() . Str::random(10) . '.png';
                Storage::makeDirectory($tempPath);

                // $path = storage_path('app/public/' . $imageName);
                // file_put_contents($path, base64_decode($data));
                Storage::put($imageName, base64_decode($data));

                $img->removeAttribute('src');
                $img->setAttribute('src', '/storage/' . $imageName);
            }
        }

        $newHtml = $hasChange ? HtmlPurifier::getBodyHtml($doc) : '';

        return [
            $hasChange,
            $newHtml
        ];
    }
}
