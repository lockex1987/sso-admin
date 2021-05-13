<?php

namespace App\Models;

class Attachment extends BaseModel
{
    public static function insertAttachment(String $type, $objectId, $path, $file)
    {
        // Lưu DB
        $attachment = new Attachment();
        $attachment->type = $type;
        $attachment->object_id = $objectId;
        $attachment->size = $file->getSize(); // đơn vị là bytes (B)
        $attachment->file_name = $file->getClientOriginalName();
        $attachment->path = $path;
        $attachment->save();
    }

    public static function getAttachments(String $type, $objectId)
    {
        return Attachment::where('type', $type)
            ->where('object_id', $objectId)
            ->get();
    }

    public static function deleteAttachments(String $type, $objectId)
    {
        Attachment::where('type', $type)
            ->where('object_id', $objectId)
            ->delete();
    }
}
