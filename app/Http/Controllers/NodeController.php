<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateFolderRequest;
use App\Http\Requests\FileUploadRequest;
use App\Http\Requests\MoveRequest;
use App\Http\Requests\UpdateRequest;
use App\Http\Resources\ApiResource;
use App\Services\NodeService;
use Illuminate\Http\Request;

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
     * @param Request $request
     * @param $parentId
     * @return ApiResource
     */
    public function index(Request $request, $parentId = null): ApiResource
    {
        return new ApiResource(
            $this->service->listNodes($request->user()->id, $parentId)
        );
    }

    /**
     * Move a node to a new parent.
     * @param MoveRequest $request
     * @return ApiResource
     */
    public function move(MoveRequest $request): ApiResource
    {
        $validated = $request->validated();

        return new ApiResource(
            $this->service->moveNode($request->user()->id,
                $validated['nodeId'],
                $validated['targetId']
            )
        );
    }

    /**
     * Create a new folder.
     * @param CreateFolderRequest $request
     * @return ApiResource
     */
    public function createFolder(CreateFolderRequest $request): ApiResource
    {
        $validated = $request->validated();

        return new ApiResource(
            $this->service->createFolder(
                $request->user()->id,
                $validated['name'],
                $validated['targetId']
            )
        );
    }

    /**
     * Upload a new file.
     * @param FileUploadRequest $request
     * @return ApiResource
     */
    public function uploadFile(FileUploadRequest $request): ApiResource
    {
        $validated = $request->validated();

        return new ApiResource(
            $this->service->uploadFile(
                $request->user()->id,
                $validated['file'],
                $validated['targetId']
            )
        );
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
     * @param Request $request
     * @param string $id
     * @return ApiResource
     */
    public function destroy(Request $request, string $id): ApiResource
    {
        return new ApiResource(
            $this->service->deleteNode($request->user()->id, $id)
        );
    }
}
