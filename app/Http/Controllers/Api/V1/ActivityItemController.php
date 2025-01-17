<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreActivityItem;
use App\Http\Requests\V1\UpdateActivityItem;
use App\Http\Resources\V1\ActivityItemResource;
use App\Models\ActivityItem;
use App\Models\Organization;
use App\Repositories\ActivityItem\ActivityItemRepositoryInterface;
use App\Repositories\ActivityType\ActivityTypeRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

/**
 * @group 8. Activity Item
 *
 * APIs for activity item management
 */
class ActivityItemController extends Controller
{
    private $activityItemRepository;
    private $activityTypeRepository;

    /**
     * ActivityItemController constructor.
     *
     * @param ActivityItemRepositoryInterface $activityItemRepository
     * @param ActivityTypeRepositoryInterface $activityTypeRepository
     */
    public function __construct(
        ActivityItemRepositoryInterface $activityItemRepository,
        ActivityTypeRepositoryInterface $activityTypeRepository
    ) {
        $this->activityItemRepository = $activityItemRepository;
        $this->activityTypeRepository = $activityTypeRepository;

        // $this->authorizeResource(ActivityItem::class, 'activityItem');
    }

    /**
     * Get Activity Items
     *
     * Get a list of the activity items.
     *
     * @responseFile responses/activity-item/activity-items.json
     *
     * @return Response
     */
    public function index()
    {
        return response([
            'activityItems' => ActivityItemResource::collection($this->activityItemRepository->all()),
        ], 200);
    }

    /**
     * Get Activity Items with pagination and search
     *
     * Get a list of the activity items.
     *
     * @urlParam suborganization required The Id of a suborganization Example: 1
     * 
     * @responseFile responses/activity-item/activity-items.json
     *
     * @param Request $request
     * @param Organization $suborganization
     *
     * @return Response
     */
    public function getItems(Request $request, Organization $suborganization)
    {
        return  ActivityItemResource::collection($this->activityItemRepository->getAll($suborganization, $request->all()));
    }

    /**
     * Get Specific Activity Items
     *
     * Get the Specific Activity layouts data.
     *
     * @responseFile responses/admin/activity-item/activity-items.json
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function activityLayouts()
    {
        $collections = $this->activityItemRepository->getActivityLayouts();

        return ActivityItemResource::collection($collections);
    }

    /**
     * Upload Thumbnail
     *
     * Upload thumbnail image for a activity item
     *
     * @bodyParam thumb image required Thumbnail image
     *
     * @response {
     *   "thumbUrl": "/storage/activity-items/1fqwe2f65ewf465qwe46weef5w5eqwq.png"
     * }
     *
     * @response 400 {
     *   "errors": [
     *     "Invalid image."
     *   ]
     * }
     *
     * @param Request $request
     * @return Response
     */
    public function uploadImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image',
        ]);

        if ($validator->fails()) {
            return response([
                'errors' => ['Invalid image.']
            ], 400);
        }

        $path = $request->file('image')->store('/public/activity-items');

        return response([
            'image' => Storage::url($path),
        ], 200);
    }

    /**
     * Create Activity Item
     *
     * Create a new activity item.
     *
     * @urlParam suborganization required The Id of a suborganization Example: 1
     * @bodyParam title string required The title of a activity item Example: Audio Recorder
     * @bodyParam description string required The description of a activity item Example: Record your voice and play back or download a .wav file of your recording.
     * @bodyParam order integer The order number of a activity item Example: 1
     * @bodyParam activity_type_id integer The Id of a activity type Example: 1
     * @bodyParam type any required The type of a activity item Example: h5p
     * @bodyParam h5pLib any required The H5pLib of a activity item Example: H5P.AudioRecorder 1.0
     * @bodyParam image string The image url of a activity item Example: /storage/activity-items/zGUwGiarxX5Xt0UDFMMHtJ3ICGy1F9W68cO0Ukm6.png
     * @bodyParam demo_activity_id string required The image url of a activity item Example: 1
     * @bodyParam demo_video_id string string The image url of a activity item Example: 1
     * @bodyParam organization_id integer The Id of an organization Example: 1
     *
     * @responseFile 201 responses/activity-item/activity-item.json
     *
     * @response 400 {
     *   "errors": [
     *     "Invalid activity type id."
     *   ]
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Could not create activity item. Please try again later."
     *   ]
     * }
     *
     * @param StoreActivityItem $request
     * @param Organization $suborganization
     *
     * @return Response
     */
    public function store(StoreActivityItem $request, Organization $suborganization)
    {
        $data = $request->validated();
        $activityItem = $this->activityItemRepository->create($data);

        if ($activityItem) {
            return response([
                'activityItem' => new ActivityItemResource($activityItem),
            ], 201);
        }

        return response([
            'errors' => ['Could not create activity item. Please try again later.'],
        ], 500);
    }

    /**
     * Get Activity Item
     *
     * Get the specified activity item.
     *
     * @urlParam suborganization required The Id of a suborganization Example: 1
     * @urlParam activityItem integer required The Id of a activity item Example: 1
     *
     * @responseFile responses/activity-item/activity-item.json
     *
     * @param Organization $suborganization
     * @param ActivityItem $activityItem
     *
     * @return Response
     */
    public function show(Organization $suborganization, ActivityItem $activityItem)
    {
        return response([
            'activityItem' => new ActivityItemResource($activityItem),
        ], 200);
    }

    /**
     * Update Activity Item
     *
     * Update the specified activity item.
     *
     * @urlParam suborganization required The Id of a suborganization Example: 1
     * @urlParam activityItem required The Id of a activity item Example: 1
     * @bodyParam title string required The title of a activity item Example: Audio Recorder
     * @bodyParam description string required The description of a activity item Example: Record your voice and play back or download a .wav file of your recording.
     * @bodyParam order int The order number of a activity item Example: 1
     * @bodyParam activity_type_id int The Id of a activity type Example: 1
     * @bodyParam type any required The type of a activity item Example: h5p
     * @bodyParam h5pLib any required The H5pLib of a activity item Example: H5P.AudioRecorder 1.0
     * @bodyParam image string The image url of a activity item Example: /storage/activity-items/zGUwGiarxX5Xt0UDFMMHtJ3ICGy1F9W68cO0Ukm6.png
     * @bodyParam demo_activity_id string required The image url of a activity item Example: 1
     * @bodyParam demo_video_id string string The image url of a activity item Example: 1
     * @bodyParam organization_id integer The Id of an organization Example: 1
     *
     * @responseFile responses/activity-item/activity-item.json
     *
     * @response 400 {
     *   "errors": [
     *     "Invalid activity type id."
     *   ]
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Failed to update activity item."
     *   ]
     * }
     *
     * @param UpdateActivityItem $request
     * @param Organization $suborganization
     * @param ActivityItem $activityItem
     *
     * @return Response
     */
    public function update(UpdateActivityItem $request, Organization $suborganization, ActivityItem $activityItem)
    {
        $data = $request->validated();
        $is_updated = $this->activityItemRepository->update($activityItem->id, $data);

        if ($is_updated) {
            return response([
                'activityItem' => new ActivityItemResource($this->activityItemRepository->find($activityItem->id)),
            ], 200);
        }

        return response([
            'errors' => ['Failed to update activity item.'],
        ], 500);
    }

    /**
     * Remove Activity Item
     *
     * Remove the specified activity item.
     *
     * @urlParam suborganization required The Id of a suborganization Example: 1
     * @urlParam activityItem required The Id of a activity item Example: 1
     *
     * @response {
     *   "message": "Activity item has been deleted successfully."
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Failed to delete activity item."
     *   ]
     * }
     *
     * @param Organization $suborganization
     * @param ActivityItem $activityItem
     *
     * @return Response
     */
    public function destroy(Organization $suborganization, ActivityItem $activityItem)
    {
        if ($suborganization->id !== $activityItem->organization_id) {
            return response([
                'message' => 'Invalid activituy item or organization',
            ], 400);
        }

        $is_deleted = $this->activityItemRepository->delete($activityItem->id);

        if ($is_deleted) {
            return response([
                'message' => 'Activity item has been deleted successfully.',
            ], 200);
        }

        return response([
            'errors' => ['Failed to delete activity item.'],
        ], 500);
    }
}
