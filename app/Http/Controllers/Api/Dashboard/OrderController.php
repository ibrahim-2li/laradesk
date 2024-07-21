<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Api\File\FileController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Order\StoreRequest;
use App\Http\Requests\Dashboard\Order\OrderReplyRequest;
use App\Http\Requests\File\StoreFileRequest;
use App\Http\Resources\CannedReply\CannedReplyResource;
use App\Http\Resources\Branch\BranchSelectResource;
use App\Http\Resources\Label\LabelSelectResource;
use App\Http\Resources\Priority\PriorityResource;
use App\Http\Resources\OrderStatus\OrderStatusResource;
use App\Http\Resources\Order\OrderListResource;
use App\Http\Resources\Order\OrderManageResource;
use App\Http\Resources\User\UserDetailsResource;
use App\Models\CannedReply;
use App\Models\Branch;
use App\Models\Label;
use App\Models\Priority;
use App\Models\Setting;
use App\Models\OrderStatus;
use App\Models\Order;
use App\Models\OrderReply;
use App\Models\User;
use App\Models\UserRole;
use App\Notifications\Order\NewOrderFromAgent;
use App\Notifications\Order\NewOrderReplyFromAgentToUser;
use Auth;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Str;
use Throwable;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     * @return JsonResponse
     * @throws Exception
     */
    public function index(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();
        $sort = json_decode($request->get('sort', json_encode(['order' => 'asc', 'column' => 'created_at'], JSON_THROW_ON_ERROR)), true, 512, JSON_THROW_ON_ERROR);
        if ($user->role_id !== 1) {
            $items = Order::filter($request->all())
                ->where(function (Builder $query) use ($user) {
                    $query->where('agent_id', $user->id);
                    $query->orWhere('closed_by', $user->id);
                    $query->orWhereIn('branches_id', $user->branches()->pluck('id')->toArray());
                    $query->orWhere(function (Builder $query) use ($user) {
                        $branches = array_unique(array_merge($user->branches()->pluck('id')->toArray(), Branch::where('all_agents', 1)->pluck('id')->toArray()));
                        $query->whereNull('agent_id');
                        $query->whereIn('branches_id', $branches);
                    });
                })
                ->orderBy($sort['column'], $sort['order'])
                ->paginate((int) $request->get('perPage', 10));
        } else {
            $items = Order::filter($request->all())
                ->orderBy($sort['column'], $sort['order'])
                ->paginate((int) $request->get('perPage', 10));
        }
        return response()->json([
            'items' => OrderListResource::collection($items->items()),
            'pagination' => [
                'currentPage' => $items->currentPage(),
                'perPage' => $items->perPage(),
                'total' => $items->total(),
                'totalPages' => $items->lastPage()
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreRequest  $request
     * @return JsonResponse
     * @throws Throwable
     */
    /** public function store(StoreRequest $request): JsonResponse
   * {
    *    $request->validated();
     *   $order = new Order();
      *  $order->uuid = Str::uuid();
       * $order->subject = $request->get('subject');
*        $order->status_id = $request->get('status_id');
 *       $order->priority_id = $request->get('priority_id');
  *      $order->branches_id = $request->get('branches_id');
   *     $order->user_id = $request->get('user_id');
    *    $order->agent_id = $request->get('agent_id');
     *   $order->saveOrFail();
      *  $orderReply = new OrderReply();
       * $orderReply->user_id = Auth::id();
        *$orderReply->body = $request->get('body');
*        if ($order->orderReplies()->save($orderReply)) {
 *           if ($request->has('attachments')) {
  *              $orderReply->orderAttachments()->sync(collect($request->get('attachments'))->map(function ($attachment) {
   *                 return $attachment['id'];
    *            }));
     *       }
      *      $order->user->notify((new NewOrderFromAgent($order))->locale(Setting::getDecoded('app_locale')));
       *     return response()->json(['message' => __('Data saved correctly'), 'order' => new OrderManageResource($order)]);
 *       }
*        return response()->json(['message' => __('An error occurred while saving data')], 500);
  *  }
*/

public function store(StoreRequest $request): JsonResponse
{
    $request->validated();

    // Create a new order instance
    $order = new Order();
    $order->uuid = Str::uuid();
    $order->subject = $request->get('subject');
    $order->order_status_id = $request->get('order_status_id');
    $order->priority_id = $request->get('priority_id');
    $order->branches_id = $request->get('branches_id');
    $order->user_id = $request->get('user_id');
    $order->agent_id = $request->get('agent_id'); // Assign the agent_id from the request

    // Save the order
    try {
        $order->saveOrFail();
    } catch (\Exception $e) {
        // Handle any errors during order saving
        return response()->json(['message' => __('An error occurred while saving order')], 500);
    }

    // Create a new order reply instance
    $orderReply = new OrderReply();
    $orderReply->user_id = Auth::id();
    $orderReply->body = $request->get('body');

    // Save the order reply
    if ($order->orderReplies()->save($orderReply)) {
        // Attach attachments if present
        if ($request->has('attachments')) {
            $orderReply->orderAttachments()->sync(
                collect($request->get('attachments'))->pluck('id')
            );
        }

        // Notify user about the new order
        $order->user->notify((new NewOrderFromAgent($order))->locale(Setting::getDecoded('app_locale')));

        return response()->json([
            'message' => __('order created successfully'),
            'order' => new OrderManageResource($order)
        ]);
    }

    return response()->json(['message' => __('An error occurred while saving order reply')], 500);
}


    /**
     * Display the specified resource.
     *
     * @param  Order  $order
     * @return JsonResponse
     */
    public function show(Order $order): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$order->verifyUser($user)) {
            return response()->json(['message' => __('You do not have permissions to manage this order')], 403);
        }
        return response()->json(new OrderManageResource($order));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  OrderReplyRequest  $request
     * @param  Order  $order
     * @return JsonResponse
     */
    public function reply(Order $order, OrderReplyRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$order->verifyUser($user)) {
            return response()->json(['message' => __('You do not have permissions to manage this order')], 403);
        }
        $request->validated();
        $orderReply = new OrderReply();
        $orderReply->user_id = Auth::id();
        $orderReply->body = $request->get('body');
        if ($order->orderReplies()->save($orderReply)) {
            if ($request->has('attachments')) {
                $orderReply->orderAttachments()->sync(collect($request->get('attachments'))->map(function ($attachment) {
                    return $attachment['id'];
                }));
            }
            $order->status_id = $request->get('status_id');
            $order->updated_at = Carbon::now();
            $order->save();
            $order->user->notify((new NewOrderReplyFromAgentToUser($order))->locale(Setting::getDecoded('app_locale')));
            return response()->json(['message' => __('Data saved correctly'), 'order' => new OrderManageResource($order)]);
        }
        return response()->json(['message' => __('An error occurred while saving data')], 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Order  $order
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Order $order): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$order->verifyUser($user)) {
            return response()->json(['message' => __('You do not have permissions to manage this order')], 403);
        }
        $order->labels()->sync([]);
        foreach ($order->orderReplies()->get() as $orderReply) {
            $orderReply->orderAttachments()->sync([]);
        }
        $order->orderReplies()->delete();
        if ($order->delete()) {
            return response()->json(['message' => 'Data deleted successfully']);
        }
        return response()->json(['message' => __('An error occurred while deleting data')], 500);
    }

    public function filters(): JsonResponse
    {
        $roles = UserRole::where('dashboard_access', true)->pluck('id');
        $agents = User::whereIn('role_id', $roles)->where('status', true)->get();

        return response()->json([
            'agents' => UserDetailsResource::collection($agents),
            'customers' => UserDetailsResource::collection(User::where('status', true)->get()),
            'branches' => BranchSelectResource::collection(Branch::all()),
            'labels' => LabelSelectResource::collection(Label::all()),
            'statuses' => OrderStatusResource::collection(OrderStatus::all()),
            'priorities' => PriorityResource::collection(Priority::orderBy('value')->get()),
        ]);
    }

    public function cannedReplies(): JsonResponse
    {
        return response()->json(CannedReplyResource::collection(CannedReply::where(function ($builder) {
            /** @var Builder|CannedReply $builder */
            $builder->where('shared', '=', true)
                ->orWhere('user_id', '=', Auth::id());
        })->get()));
    }

    /**
     * @param  Request  $request
     * @return JsonResponse
     * @throws Exception
     */
    public function quickActions(Request $request): JsonResponse
    {
        $action = $request->get('action');
        /** @var User $user */
        $user = Auth::user();
        $orders = Order::whereIn('id', $request->get('orders'));
        if ($user && $user->role_id !== 1) {
            $orders->where(function (Builder $query) use ($user) {
                $query->where('agent_id', $user->id);
                $query->orWhere('closed_by', $user->id);
                $query->orWhereIn('branches_id', $user->branches()->pluck('id')->toArray());
                $query->orWhere(function (Builder $query) use ($user) {
                    $branches = array_unique(array_merge($user->branches()->pluck('id')->toArray(), Branch::where('all_agents', 1)->pluck('id')->toArray()));
                    $query->whereNull('agent_id');
                    $query->whereIn('branches_id', $branches);
                });
            });
        }
        if ($orders->count() < 1) {
            return response()->json(['message' => __('You have not selected a order or do not have permissions to perform this action')], 403);
        }
        if ($action === 'agent') {
            $orders->update(['agent_id' => $request->get('value')]);
            return response()->json(['message' => __('orders assigned to the selected agent')]);
        }
        if ($action === 'department') {
            $orders->update(['branches_id' => $request->get('value')]);
            return response()->json(['message' => __('orders assigned to the selected department')]);
        }
        if ($action === 'label') {
            foreach ($orders->get() as $order) {
                /** @var Order $order */
                $order->labels()->syncWithoutDetaching($request->get('value'));
                $order->updated_at = Carbon::now();
                $order->save();
            }
            return response()->json(['message' => __('The label has been added to selected orders')]);
        }
        if ($action === 'priority') {
            $orders->update(['priority_id' => $request->get('value')]);
            return response()->json(['message' => __('The priority of the selected orders has been changed')]);
        }
        if ($action === 'delete') {
            foreach ($orders->get() as $order) {
                /** @var Order $order */
                $order->labels()->sync([]);
                foreach ($order->orderReplies()->get() as $orderReply) {
                    $orderReply->orderAttachments()->sync([]);
                }
                $order->orderReplies()->delete();
                $order->delete();
            }
            return response()->json(['message' => __('The selected orders have been deleted')]);
        }
        return response()->json(['message' => __('Quick action not found')], 404);
    }

    /**
     * @param  Order  $order
     * @param  Request  $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function orderQuickActions(Order $order, Request $request): JsonResponse
    {
        $value = $request->get('value');
        $action = $request->get('action');
        /** @var User $user */
        $user = Auth::user();
        if (!$order->verifyUser($user)) {
            return response()->json(['message' => __('You do not have permissions to manage this order')], 403);
        }
        if ($action === 'agent') {
            $order->agent_id = $value;
            $order->saveOrFail();
            return response()->json(['message' => __('order assigned to the selected agent'), 'order' => new OrderManageResource($order), 'access' => $order->verifyUser($user)]);
        }
        if ($action === 'department') {
            $order->branches_id = $value;
            $order->saveOrFail();
            return response()->json(['message' => __('order assigned to the selected department'), 'order' => new OrderManageResource($order), 'access' => $order->verifyUser($user)]);
        }
        if ($action === 'label') {
            $order->labels()->syncWithoutDetaching($request->get('value'));
            $order->updated_at = Carbon::now();
            $order->save();
            return response()->json(['message' => __('Label has been assigned to order'), 'order' => new OrderManageResource($order), 'access' => true]);
        }
        if ($action === 'priority') {
            $order->priority_id = $value;
            $order->saveOrFail();
            return response()->json(['message' => __('order priority has been changed'), 'order' => new OrderManageResource($order), 'access' => true]);
        }
        return response()->json(['message' => __('Quick action not found')], 404);
    }

    public function removeLabel(Order $order, Request $request): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$order->verifyUser($user)) {
            return response()->json(['message' => __('You do not have permissions to manage this order')], 403);
        }
        if ($order->labels()->detach($request->get('label'))) {
            return response()->json(['message' => __('Data saved correctly'), 'order' => new OrderManageResource($order)]);
        }
        return response()->json(['message' => __('An error occurred while saving data')], 500);
    }

    public function uploadAttachment(StoreFileRequest $request): JsonResponse
    {
        return (new FileController())->uploadAttachment($request);
    }
}
