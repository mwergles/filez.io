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
        $nodes = Node::select('nodes.*', DB::raw('COUNT(child_nodes.id) AS length'))
            ->leftJoin('nodes AS child_nodes', 'nodes.id', '=', 'child_nodes.parent_id')
            ->where('nodes.user_id', $userId)
            ->where('nodes.parent_id', $parentId)
            ->groupBy('nodes.id')
            ->orderByRaw('CASE WHEN nodes.type = ? THEN 1 ELSE 2 END', [NodeType::FOLDER->value])
            ->orderBy('nodes.created_at')
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
     * @param string|null $targetId
     * @return Node
     */
    public function moveNode(string $userId, string $nodeId, ?string $targetId = null): Node
    {
        $node = $this->getNode($nodeId, $userId);

        // If the target is null, we move the node to the parent's parent
        // in other words, we move the node up one level
        $targetNode = $targetId ? $this->getNode($targetId, $userId) : $node->parent?->parent;

        if ($targetNode && $targetNode->type !== NodeType::FOLDER->value) {
            throw new \InvalidArgumentException('Target node is not a folder.');
        }

        $node->parent_id = $targetNode?->id;
        $node->save();

        return $node;
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
        $node = $this->getNode($nodeId, $userId);

        $node->name = $name;
        $node->save();

        return $node;
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
        $node = $this->getNode($nodeId, $userId);

        return DB::transaction(function () use ($node) {
            $deleted = Storage::delete($node->id);

            if (!$deleted) {
                throw new StorageException('Could not delete node');
            }

            $node->delete();

            return $node;
        });
    }

    /**
     * Get the ancestors of a node.
     * @param string $nodeId
     * @param string $userId
     * @return array
     */
    public function getNodeAncestors(string $nodeId, string $userId): array
    {
        $node = $this->getNode($nodeId, $userId);

        $ancestors = [];
        $currentNode = $node;
        while ($currentNode->parent) {
            $ancestor = [
                'id' => $currentNode->parent->id,
                'name' => $currentNode->parent->name,
            ];

            array_unshift($ancestors, $ancestor);
            $currentNode = $currentNode->parent;
        }

        if ($node->type === NodeType::FOLDER->value) {
            $ancestors[] = [
                'id' => $node->id,
                'name' => $node->name,
            ];
        }

        return $ancestors;
    }

    /**
     * Get the node of a user by id.
     * @param string $id
     * @param string $userId
     * @return mixed
     */
    private function getNode(string $id, string $userId): mixed
    {
        return Node::where('id', $id)
            ->where('user_id', $userId)
            ->first();
    }

    /**
     * Get the expiration time of the session.
     * @param string $userId
     * @return Carbon
     */
    private function getSessionRemainingTime(string $userId): Carbon
    {
        $sessionLastActivity = DB::table('sessions')
            ->where('user_id', '=', $userId)
            ->value('last_activity');

        $sessionLifeTime = Config::get('session.lifetime') * 60;
        $remainingSessionTime = $sessionLastActivity + $sessionLifeTime - time();

        return now()->addSeconds($remainingSessionTime);
    }
}
