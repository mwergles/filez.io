<?php

namespace App\Services;

use App\Enums\NodeType;
use App\Models\Node;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class NodeService
{

    /**
     * List all nodes of a user.
     * @param string $userId
     * @param string|null $parentId
     * @return mixed
     */
    public function listNodes(string $userId, ?string $parentId = null)
    {
        $nodes = Node::where('user_id', $userId)
            ->where('parent_id', $parentId)
            ->orderByRaw('CASE type WHEN \'' . NodeType::FOLDER->value . '\' THEN 1 ELSE 2 END')
            ->orderBy('created_at')
            ->get();

        $expirationTime = $this->getExpirationTime($userId);

        foreach ($nodes as $node) {
            $node->url = Storage::temporaryUrl($node->id, $expirationTime, [
                'ResponseContentType' => $node->mime_type,
                'ResponseContentDisposition' => "attachment; filename=$node->name",
            ]);
        }

        return $nodes;
    }


    /**
     * Move a node to a new parent.
     * @param string $userId
     * @param string $nodeId
     * @param string $targetId
     * @return int
     */
    public function moveNode(string $userId, string $nodeId, string $targetId): int
    {
        return Node::where('user_id', $userId)
            ->where('id', $nodeId)
            ->update(['parent_id' => $targetId]);
    }

    /**
     * Create a new folder.
     * @param string $userId
     * @param string $name
     * @param string|null $targetId
     * @return Node
     */
    public function createFolder(string $userId, string $name, ?string $targetId = null): Node
    {
        return Node::create([
            'name' => $name,
            'type' => NodeType::FOLDER,
            'user_id' => $userId,
            'parent_id' => $targetId
        ]);
    }

    /**
     * Upload a file.
     * @param string $userId
     * @param UploadedFile $file
     * @param string|null $targetId
     * @return int
     */
    public function uploadFile(string $userId, UploadedFile $file, ?string $targetId = null): int
    {
        return DB::transaction(function () use ($userId, $file, $targetId) {
            $node = Node::create([
                'name' => $file->getClientOriginalName(),
                'type' => NodeType::FILE,
                'user_id' => $userId,
                'parent_id' => $targetId,
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
            ]);

            if (!Storage::put($node->id, $file->getContent())) {
                abort(500);
            }

            return $node;
        });
    }

    /**
     * Update a node name.
     * @param string $userId
     * @param string $nodeId
     * @param string $name
     * @return int
     */
    public function updateNode(string $userId, string $nodeId, string $name): int
    {
        return Node::where('user_id', $userId)
            ->where('id', $nodeId)
            ->update(['name' => $name]);
    }

    /**
     * Delete a node.
     * @param string $userId
     * @param string $nodeId
     * @return Node
     */
    public function deleteNode(string $userId, string $nodeId): int
    {
        $node = Node::where('id', $nodeId)
            ->where('user_id', $userId)
            ->first();

        if (!$node) {
            abort(404);
        }

        return DB::transaction(function () use ($node) {
            if (!Storage::delete($node->id)) {
                abort(500);
            }

            $node->delete();

            return $node;
        });
    }

    /**
     * Get the expiration time of the session.
     * @param string $userId
     * @return Carbon
     */
    private function getExpirationTime(string $userId): Carbon
    {
        $session = DB::table('sessions')
            ->where('user_id', '=', $userId)
            ->get('last_activity');

        $sessionLifeTime = Config::get('session.lifetime') * 60;

        $remainingSessionTime = $session->first()->last_activity + $sessionLifeTime - time();

        return now()->addSeconds($remainingSessionTime);
    }
}
