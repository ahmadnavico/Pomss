<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class PostLogsService
{
    // Method to create a log for a post
    public function createLog($postId, $action, $userId)
    {
        // Define the directory path
        $directoryPath = "posts/";

        // Create the directory if it doesn't exist
        if (!Storage::exists($directoryPath)) {
            Storage::makeDirectory($directoryPath);
        }

        // Define the log file path
        $logFilePath = "{$directoryPath}/postdata_" . $postId . ".json";

        // Prepare the log data
       
        // Read existing logs
        $logs = [];
        if (Storage::exists($logFilePath)) {
            $postData = json_decode(Storage::get($logFilePath), true);
        } else {
            $postData = [];
        }
        $id = count($postData['logs'] ?? []) + 1;

        // Prepare the log data with the ID first
        $logData = [
            'id' => $id, // Set ID first
            'action' => $action,
            'user_id' => $userId,
            'timestamp' => now()->toDateTimeString(),
        ];

        // $logs = isset($postData['logs']) ? $postData['logs'] : [];
        // Append the new log to the existing logs
        // $logs[] = $logData;
        // Prepare the final data to be saved
        $postData['logs'][] = $logData;

        // Save the logs back to the file
        Storage::put($logFilePath, json_encode($postData, JSON_PRETTY_PRINT));
    }
}
