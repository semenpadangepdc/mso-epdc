<?php
namespace App\Services;

use Google\Client as GoogleClient;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use Illuminate\Support\Facades\Storage;

class DriveService
{
    protected $client;
    protected $drive;
    protected $folderId;

    public function __construct()
    {
        $jsonPath = base_path(config('services.google.credentials') ?: env('GOOGLE_APPLICATION_CREDENTIALS','storage/epdc-mso-2b92e23964cf.json'));
        $this->folderId = env('GOOGLE_DRIVE_FOLDER_ID');
        $client = new GoogleClient();
        $client->setAuthConfig($jsonPath);
        $client->addScope(Drive::DRIVE);
        $this->client = $client;
        $this->drive = new Drive($client);
    }

    /**
     * Upload binary content (string) with filename to Drive under configured folder.
     * Returns Drive file id.
     */
    public function uploadFromString(string $contents, string $filename, string $mime = 'image/jpeg')
    {
        $fileMetadata = new DriveFile([
            'name' => $filename,
            'parents' => [$this->folderId]
        ]);

        $result = $this->drive->files->create($fileMetadata, [
            'data' => $contents,
            'mimeType' => $mime,
            'uploadType' => 'multipart'
        ]);

        // Make file accessible within domain if required (skip public unless needed)
        return $result->id;
    }

    /**
     * Upload from local path
     */
    public function uploadFromPath(string $path, string $filename = null)
    {
        $contents = file_get_contents($path);
        $mime = mime_content_type($path) ?: 'application/octet-stream';
        $filename = $filename ?? basename($path);
        return $this->uploadFromString($contents, $filename, $mime);
    }
}
