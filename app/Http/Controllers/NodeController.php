<?php

namespace App\Http\Controllers;

use App\Http\Requests\Node\AncestorsRequest;
use App\Http\Requests\Node\CreateFolderRequest;
use App\Http\Requests\Node\DeleteRequest;
use App\Http\Requests\Node\FileUploadRequest;
use App\Http\Requests\Node\IndexRequest;
use App\Http\Requests\Node\MoveRequest;
use App\Http\Requests\Node\UpdateRequest;
use App\Http\Resources\ApiResource;
use App\Services\NodeService;
use App\Exceptions\StorageException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class NodeController extends Controller
{

    /**
     * NodeController constructor.
     * @param NodeService $service
     */
    public function __construct(private readonly NodeService $service)
    {

    }

    /**
     * Display a listing of the resource.
     * @param IndexRequest $request
     * @param $parentId
     * @return ApiResource
     */
    public function index(IndexRequest $request, $parentId = null): ApiResource
    {
        return new ApiResource(
            $this->service->listNodes($request->user()->id, $parentId)
        );
    }

    /**
     * Return the parents list of a node.
     * @param AncestorsRequest $request
     * @param $id
     * @return ApiResource
     */
    public function getNodeAncestors(AncestorsRequest $request, $id): ApiResource
    {
        return new ApiResource(
            $this->service->getNodeAncestors($id, $request->user()->id)
        );
    }

    /**
     * Move a node to a new parent.
     * @param MoveRequest $request
     * @return ApiResource
     */
    public function moveNode(MoveRequest $request): ApiResource
    {
        $validated = $request->validated();

        try {
            return new ApiResource(
                $this->service->moveNode($request->user()->id,
                    $validated['nodeId'],
                    $validated['targetId'] ?? null
                )
            );
        } catch (\InvalidArgumentException $e) {
            throw new HttpResponseException(
                response()->json(['message' => $e->getMessage()], 400)
            );
        }

    }

    /**
     * Create a new folder.
     * @param CreateFolderRequest $request
     * @return JsonResponse
     */
    public function createFolder(CreateFolderRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $resource = new ApiResource(
            $this->service->createFolder(
                $request->user()->id,
                $validated['name'],
                $validated['targetId'] ?? null
            )
        );

        return $resource->response()->setStatusCode(201);
    }

    /**
     * Upload a new file.
     * @param FileUploadRequest $request
     * @return JsonResponse
     */
    public function uploadFile(FileUploadRequest $request): JsonResponse
    {
        $validated = $request->validated();

        try {
            $resource = new ApiResource(
                $this->service->uploadFile(
                    $request->user()->id,
                    $validated['file'],
                    $validated['targetId'] ?? null
                )
            );

            return $resource->response()->setStatusCode(201);
        } catch (StorageException $e) {
            throw new HttpResponseException(
                response()->json(['message' => $e->getMessage()], 500)
            );
        }
    }

    /**
     * Update the specified resource in storage.
     * @param UpdateRequest $request
     * @param $id
     * @return ApiResource
     */
    public function update(UpdateRequest $request, $id): ApiResource
    {
        $validated = $request->validated();

        return new ApiResource(
            $this->service->updateNode($request->user()->id, $id, $validated['name'])
        );
    }

    /**
     * Remove the specified resource from storage.
     * @param DeleteRequest $request
     * @param string $id
     * @return ApiResource
     */
    public function destroy(DeleteRequest $request, string $id): ApiResource
    {
        try {
            return new ApiResource(
                $this->service->deleteNode($request->user()->id, $id)
            );
        } catch (StorageException $e) {
            throw new HttpResponseException(
                response()->json(['message' => $e->getMessage()], 500)
            );
        }
    }
}
