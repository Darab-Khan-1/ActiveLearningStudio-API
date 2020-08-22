<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\ProjectResource;
use App\Models\Project;
use App\Repositories\Project\ProjectRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    private $projectRepository;

    /**
     * ProjectController constructor.
     *
     * @param ProjectRepositoryInterface $projectRepository
     */
    public function __construct(ProjectRepositoryInterface $projectRepository)
    {
        $this->projectRepository = $projectRepository;
    }

    /**
     * Display a listing of the project.
     *
     * @return Response
     */
    public function index()
    {
        $authenticated_user = auth()->user();

        if ($authenticated_user->role === 'admin') {
            return response([
                'projects' => ProjectResource::collection($this->projectRepository->all()),
            ], 200);
        }

        return response([
            'projects' => $authenticated_user->projects,
        ], 200);
    }

    /**
     * Upload thumb image for project
     *
     * @param Request $request
     * @return Response
     */
    public function uploadThumb(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'thumb' => 'required|image',
        ]);

        if ($validator->fails()) {
            return response([
                'errors' => ['Invalid image.']
            ], 400);
        }

        $path = $request->file('thumb')->store('/public/projects');

        return response([
            'thumbUrl' => Storage::url($path),
        ], 200);
    }

    /**
     * Store a newly created project in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'thumb_url' => 'required',
        ]);

        $authenticated_user = auth()->user();
        $project = $authenticated_user->projects()->create($data, ['role' => 'owner']);

        if ($project) {
            return response([
                'project' => $project,
            ], 201);
        }

        return response([
            'errors' => ['Could not create project. Please try again later.'],
        ], 500);
    }

    /**
     * Display the specified project.
     *
     * @param Project $project
     * @return Response
     */
    public function show(Project $project)
    {
        $allowed = $this->checkPermission($project);

        if (!$allowed) {
            return response([
                'errors' => ['Forbidden. You are trying to access other user\'s project.'],
            ], 403);
        }

        return response([
            'project' => new ProjectResource($project),
        ], 200);
    }

    /**
     * Share the specified project.
     *
     * @param Request $request
     * @param Project $project
     * @return Response
     */
    public function share(Request $request, Project $project)
    {
        $allowed = $this->checkPermission($project);

        if (!$allowed) {
            return response([
                'errors' => ['Forbidden. You are trying to change other user\'s project.'],
            ], 403);
        }

        $is_updated = $this->projectRepository->update([
            'shared' => true,
        ], $project->id);

        if ($is_updated) {
            return response([
                'project' => new ProjectResource($this->projectRepository->find($project->id)),
            ], 200);
        }

        return response([
            'errors' => ['Failed to share project.'],
        ], 500);
    }

    /**
     * Remove share specified project.
     *
     * @param Request $request
     * @param Project $project
     * @return Response
     */
    public function removeShare(Request $request, Project $project)
    {
        $allowed = $this->checkPermission($project);

        if (!$allowed) {
            return response([
                'errors' => ['Forbidden. You are trying to change other user\'s project.'],
            ], 403);
        }

        $is_updated = $this->projectRepository->update([
            'shared' => false,
        ], $project->id);

        if ($is_updated) {
            return response([
                'project' => new ProjectResource($this->projectRepository->find($project->id)),
            ], 200);
        }

        return response([
            'errors' => ['Failed to remove share project.'],
        ], 500);
    }

    /**
     * Update the specified project in storage.
     *
     * @param Request $request
     * @param Project $project
     * @return Response
     */
    public function update(Request $request, Project $project)
    {
        $allowed = $this->checkPermission($project);

        if (!$allowed) {
            return response([
                'errors' => ['Forbidden. You are trying to change other user\'s project.'],
            ], 403);
        }

        $is_updated = $this->projectRepository->update($request->only([
            'name',
            'description',
            'thumb_url',
        ]), $project->id);

        if ($is_updated) {
            return response([
                'project' => new ProjectResource($this->projectRepository->find($project->id)),
            ], 200);
        }

        return response([
            'errors' => ['Failed to update project.'],
        ], 500);
    }

    /**
     * Remove the specified project from storage.
     *
     * @param Project $project
     * @return Response
     */
    public function destroy(Project $project)
    {
        $allowed = $this->checkPermission($project);

        if (!$allowed) {
            return response([
                'errors' => ['Forbidden. You are trying to delete other user\'s project.'],
            ], 403);
        }

        $is_deleted = $this->projectRepository->delete($project->id);

        if ($is_deleted) {
            return response([
                'message' => 'Project is deleted successfully.',
            ], 200);
        }

        return response([
            'errors' => ['Failed to delete project.'],
        ], 500);
    }

    private function checkPermission(Project $project)
    {
        $authenticated_user = auth()->user();

        $allowed = $authenticated_user->role === 'admin';
        if (!$allowed) {
            $project_users = $project->users;
            foreach ($project_users as $user) {
                if ($user->id === $authenticated_user->id) {
                    $allowed = true;
                }
            }
        }

        return $allowed;
    }
}