<?php

namespace App\Http\Controllers\Api\Order;

use Str;
use Auth;
use Exception;
use Throwable;
use Carbon\Carbon;
use App\Models\Item;
use App\Models\Order;
use App\Models\Branch;
use App\Models\Setting;
use App\Models\OrderReply;
use App\Models\ItemConfirm;
use App\Models\OrderStatus;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\StoreRequest;
use App\Notifications\Order\NewOrderRequest;
use App\Http\Requests\Order\OrderReplyRequest;
use App\Http\Resources\Order\OrderListResource;
use App\Notifications\Order\AssignOrderToBranch;
use App\Http\Resources\Order\OrderManageResource;
use App\Http\Resources\Order\OrderDetailsResource;
use App\Http\Resources\Branch\BranchSelectResource;
use App\Http\Resources\OrderStatus\OrderStatusResource;
use App\Notifications\Order\NewOrderReplyFromUserToUser;
use App\Notifications\Order\NewOrderReplyFromAgentToUser;
use App\Notifications\Order\NewOrderReplyFromUserToAgent;
use App\Http\Requests\Dashboard\Order\ConfirmItemsRequest;

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
        $sort = json_decode($request->get('sort', json_encode(['order' => 'asc', 'column' => 'created_at'], JSON_THROW_ON_ERROR)), true, 512, JSON_THROW_ON_ERROR);
        $items = Order::filter($request->all())
            ->where('user_id', Auth::id())
            ->orderBy($sort['column'], $sort['order'])
            ->paginate((int) $request->get('perPage', 10));
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
    public function store(StoreRequest $request): JsonResponse
    {
        $request->validated();
        $order = new Order();
        $order->uuid = Str::uuid();
        $order->subject = $request->get('subject');
        $order->orders_status_id = 1;
        if ($request->has('branches_id')) {
            $order->branches_id = $request->get('branches_id');
        }
        $order->user_id = Auth::id();
        $order->saveOrFail();
        $orderReply = new OrderReply();
        $orderReply->user_id = Auth::id();
        $orderReply->body = $request->get('body');

        if ($request->has('orderItems')) {
            foreach ($request->get('orderItems') as $reply) {
                $orderItems = new Item();
                $orderItems->user_id = Auth::id();
                $orderItems->item = $reply['item'];
                $orderItems->item_count = $reply['item_count'];
                $orderItems->details = $reply['details'];
                $order->orderItems()->save($orderItems);

                $orderItems = new ItemConfirm(); // or the corresponding model for itemConfirms table
                $orderItems->order_id = $order->id;
                $orderItems->item = $reply['item'];
                $orderItems->item_count = $reply['item_count'];
                $orderItems->details = $reply['details'];
                $orderItems->save();
            }
        }
        if ($order->orderReplies()->save($orderReply)) {
            if ($request->has('attachments')) {
                $orderReply->orderAttachments()->sync(collect($request->get('attachments'))->map(function ($attachment) {
                    return $attachment['id'];
                }));
            }
            $order->user->notify((new NewOrderRequest($order))->locale(Setting::getDecoded('app_locale')));
            if ($order->branches_id !== null && $order->branches) {
            //    foreach ($order->branches as $branch) {
            //        if ($branch->agents) {
            //            foreach ($branch->agents as $agent) {
            //                $agent->notify(new AssignOrderToBranch($order, $agent));
            //            }
            //        }
            //    }
            }
            return response()->json(['message' => __('Data saved correctly'), 'order' => new OrderManageResource($order)]);
        }
        return response()->json(['message' => __('An error occurred while saving data')], 500);
    }

    /**
     * Display the specified resource.
     *
     * @param  Order  $order
     * @return JsonResponse
     */
    public function show(Order $order): JsonResponse
    {
        if ($order->user_id !== Auth::id()) {
            return response()->json(['message' => __('Not found')], 404);
        }
        return response()->json(new OrderDetailsResource($order));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  OrderReplyRequest  $request
     * @param  Order  $order confirm
     * @return JsonResponse
     */

     public function reply(Order $order, ConfirmItemsRequest $request): JsonResponse
{
    $user = Auth::user();
    if (!$order->verifyUser($user)) {
        return response()->json(['message' => __('You do not have permissions to manage this order')], 403);
    }
    $validated = $request->validated();

    DB::beginTransaction();
    try {
        if ($request->has('orderItems')) {
            foreach ($request->get('orderItems') as $item) {
                // Save to orderItems table
                $orderItem = new ItemConfirm();
                $orderItem->user_id = $user->id;
                $orderItem->item = $item['item'];
                $orderItem->item_count = $item['item_count'];
                $orderItem->details = $item['details'];
                $order->orderItems()->save($orderItem);

                // Save to itemConfirms table
                $itemConfirm = new ItemConfirm(); // or the corresponding model for itemConfirms table
                $itemConfirm->user_id = $user->id;
                $itemConfirm->item = $item['item'];
                $itemConfirm->item_count = $item['item_count'];
                $itemConfirm->details = $item['details'];
                $itemConfirm->order_id = $order->id;
                $itemConfirm->save();
            }
        }

        $order->orders_status_id = $request->get('orders_status_id');
        $order->updated_at = Carbon::now();
        $order->save();

        $order->user->notify((new NewOrderReplyFromAgentToUser($order))->locale(Setting::getDecoded('app_locale')));

        DB::commit();
        return response()->json(['message' => __('Data saved correctly'), 'order' => new OrderManageResource($order)]);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['message' => __('An error occurred while saving data')], 500);
    }
}




     /*           public function reply(OrderReplyRequest $request, Order $order): JsonResponse
                {
                    if ($order->user_id !== Auth::id()) {
                        return response()->json(['message' => __('Not found')], 404);
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
                        $order->updated_at = Carbon::now();
                        $order->save();
                        $order->user->notify((new NewOrderReplyFromUserToUser($order))->locale(Setting::getDecoded('app_locale')));
                        if ($order->agent) {
                            $order->agent->notify((new NewOrderReplyFromUserToAgent($order, $order->agent))->locale(Setting::getDecoded('app_locale')));
                        }
                        return response()->json(['message' => __('Data saved correctly'), 'order' => new OrderManageResource($order)]);
                    }
                    return response()->json(['message' => __('An error occurred while saving data')], 500);
                } */

    public function branches(Request $request): JsonResponse
    {
        $userId = Auth::id(); // Get the authenticated user's ID
        $branches = Branch::whereHas('users', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->orWhere('public', '=', true)->get();

        return response()->json(BranchSelectResource::collection($branches));
    }

    public function statuses(): JsonResponse
    {
        return response()->json(OrderStatusResource::collection(OrderStatus::all()));
    }
    // In your controller method

}
