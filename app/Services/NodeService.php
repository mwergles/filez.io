<?php

namespace App\Services;

use App\Enums\NodeType;
use App\Exceptions\StorageException;
use App\Models\Node;
use Illuminate\Database\Eloquent\Collection;
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
     * @return Collection
     */
    public function listNodes(string $userId, ?string $parentId = null): Collection
    {
        $nodes = Node::where('user_id', $userId)
            ->where('parent_id', $parentId)
            ->orderByRaw('CASE type WHEN \'' . NodeType::FOLDER->value . '\' THEN 1 ELSE 2 END')
            ->orderBy('created_at')
            ->get();

        $temporaryUrlExpirationTime = $this->getSessionRemainingTime($userId);

        foreach ($nodes as $node) {
            $node->url = Storage::temporaryUrl($node->id, $temporaryUrlExpirationTime, [
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
     * @return Node
     */
    public function moveNode(string $userId, string $nodeId, string $targetId): Node
    {
        $targetNode = Node::where('user_id', $userId)
            ->where('id', $targetId)
            ->first();

        if ($targetNode->type !== NodeType::FOLDER->value) {
            abort(400, 'Target node is not a folder.');
        }

        Node::where('user_id', $userId)
            ->where('id', $nodeId)
            ->update(['parent_id' => $targetId]);

        return Node::where('user_id', $userId)
            ->where('id', $nodeId)
            ->first();
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
     * @throws StorageException
     * @return Node
     */
    public function uploadFile(string $userId, UploadedFile $file, ?string $targetId = null): Node
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
                throw new StorageException('Could not store file');
            }

            return $node;
        });
    }

    /**
     * Update a node name.
     * @param string $userId
     * @param string $nodeId
     * @param string $name
     * @return Node
     */
    public function updateNode(string $userId, string $nodeId, string $name): Node
    {
        Node::where('user_id', $userId)
            ->where('id', $nodeId)
            ->update(['name' => $name]);

        return Node::where('user_id', $userId)
            ->where('id', $nodeId)
            ->first();
    }

    /**
     * Delete a node.
     * @param string $userId
     * @param string $nodeId
     * @throws StorageException
     * @return Node
     */
    public function deleteNode(string $userId, string $nodeId): Node
    {
        $node = Node::where('id', $nodeId)
            ->where('user_id', $userId)
            ->first();

        return DB::transaction(function () use ($node) {
            if (!Storage::delete($node->id)) {
                throw new StorageException('Could not delete node');
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
    private function getSessionRemainingTime(string $userId): Carbon
    {
        $session = DB::table('sessions')
            ->where('user_id', '=', $userId)
            ->get('last_activity');

        $sessionLifeTime = Config::get('session.lifetime') * 60;
        $remainingSessionTime = $session->first()->last_activity + $sessionLifeTime - time();

        return now()->addSeconds($remainingSessionTime);
    }
}
