<?php

namespace App\Helpers;

class FileHelper
{
    /**
     * Get file icon based on extension
     */
    public static function getFileIcon($extension)
    {
        $iconMap = [
            // Images
            'jpg' => 'image', 'jpeg' => 'image', 'png' => 'image', 'gif' => 'image', 'webp' => 'image',
            // Documents
            'pdf' => 'file-pdf', 'doc' => 'file-doc', 'docx' => 'file-doc', 'txt' => 'file-text',
            'xls' => 'file-xls', 'xlsx' => 'file-xls', 'ppt' => 'file-ppt', 'pptx' => 'file-ppt',
            // Audio
            'mp3' => 'music-note', 'wav' => 'music-note', 'flac' => 'music-note', 'aac' => 'music-note',
            // Video
            'mp4' => 'video', 'avi' => 'video', 'mov' => 'video', 'wmv' => 'video', 'flv' => 'video',
            // Archives
            'zip' => 'file-zip', 'rar' => 'file-zip', '7z' => 'file-zip', 'tar' => 'file-zip',
            // Code
            'html' => 'file-code', 'css' => 'file-code', 'js' => 'file-code', 'php' => 'file-code',
            'py' => 'file-code', 'java' => 'file-code', 'cpp' => 'file-code', 'c' => 'file-code'
        ];
        
        return $iconMap[strtolower($extension)] ?? 'file';
    }

    /**
     * Get file type description based on extension
     */
    public static function getFileType($extension)
    {
        $typeMap = [
            // Images
            'jpg' => 'Image', 'jpeg' => 'Image', 'png' => 'Image', 'gif' => 'Image', 'webp' => 'Image',
            // Documents
            'pdf' => 'PDF Document', 'doc' => 'Word Document', 'docx' => 'Word Document', 'txt' => 'Text File',
            'xls' => 'Excel Spreadsheet', 'xlsx' => 'Excel Spreadsheet', 'ppt' => 'PowerPoint', 'pptx' => 'PowerPoint',
            // Audio
            'mp3' => 'Audio File', 'wav' => 'Audio File', 'flac' => 'Audio File', 'aac' => 'Audio File',
            // Video
            'mp4' => 'Video File', 'avi' => 'Video File', 'mov' => 'Video File', 'wmv' => 'Video File', 'flv' => 'Video File',
            // Archives
            'zip' => 'Archive', 'rar' => 'Archive', '7z' => 'Archive', 'tar' => 'Archive',
            // Code
            'html' => 'HTML File', 'css' => 'CSS File', 'js' => 'JavaScript File', 'php' => 'PHP File',
            'py' => 'Python File', 'java' => 'Java File', 'cpp' => 'C++ File', 'c' => 'C File'
        ];
        
        return $typeMap[strtolower($extension)] ?? 'File';
    }

    /**
     * Check if file is an image
     */
    public static function isImage($extension)
    {
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg'];
        return in_array(strtolower($extension), $imageExtensions);
    }

    /**
     * Check if file is a document
     */
    public static function isDocument($extension)
    {
        $documentExtensions = ['pdf', 'doc', 'docx', 'txt', 'xls', 'xlsx', 'ppt', 'pptx'];
        return in_array(strtolower($extension), $documentExtensions);
    }

    /**
     * Check if file is audio
     */
    public static function isAudio($extension)
    {
        $audioExtensions = ['mp3', 'wav', 'flac', 'aac', 'ogg', 'm4a'];
        return in_array(strtolower($extension), $audioExtensions);
    }

    /**
     * Check if file is video
     */
    public static function isVideo($extension)
    {
        $videoExtensions = ['mp4', 'avi', 'mov', 'wmv', 'flv', 'mkv', 'webm'];
        return in_array(strtolower($extension), $videoExtensions);
    }

    /**
     * Format file size in human readable format
     */
    public static function formatFileSize($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Get file category based on extension
     */
    public static function getFileCategory($extension)
    {
        $extension = strtolower($extension);
        
        if (self::isImage($extension)) {
            return 'image';
        } elseif (self::isDocument($extension)) {
            return 'document';
        } elseif (self::isAudio($extension)) {
            return 'audio';
        } elseif (self::isVideo($extension)) {
            return 'video';
        } elseif (in_array($extension, ['zip', 'rar', '7z', 'tar'])) {
            return 'archive';
        } elseif (in_array($extension, ['html', 'css', 'js', 'php', 'py', 'java', 'cpp', 'c'])) {
            return 'code';
        }
        
        return 'file';
    }
}