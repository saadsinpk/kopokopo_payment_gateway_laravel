<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Order;
use App\Repositories\UploadRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class MessageAPIController extends Controller
{

    /**
     * @var UploadRepository
     */
    private $uploadRepository;

    public function __construct(UploadRepository $uploadRepository)
    {
        $this->uploadRepository = $uploadRepository;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->validate([
            'order_id' => 'required|integer',
            'last_message_datetime' => 'nullable|date',
        ]);

        try {
            $order = Order::find($request->order_id);
            if (!isset($order) || ($order->user_id != Auth::user()->id && $order->courier->user->id != Auth::user()->id && !Auth::user()->hasRole('admin'))) {
                return $this->sendError('Order not found');
            }

            $messagesQuery = Message::where('order_id', $order->id);
            if (isset($request->last_message_datetime)) {
                $messagesQuery->where('sender_id', '!=', auth()->id())->where('created_at', '>', $request->last_message_datetime);
            }

            $messages = $messagesQuery->orderBy('id', 'asc')->get();

            return $this->sendResponse($messages, __('Messages retrieved successfully'));
        } catch (\Exception $e) {
            report($e);
            return $this->sendError(trans('error.error'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|integer',
            'message' => 'required_without:file|string',
            'file' => 'required_without:message|mimes:jpeg,bmp,png,gif,svg,pdf',
        ]);

        try {

            $order = Order::find($request->order_id);
            if (!isset($order) || ($order->user_id != Auth::user()->id && $order->courier->user->id != Auth::user()->id)) {
                return $this->sendError('Order not found');
            } elseif (!in_array($order->order_status, ['accepted', 'collected', 'delivered'])) {
                return $this->sendError(__('Order cannot receive new messages'), 403);
            }

            $message = Message::create(['order_id' => $order->id, 'sender_id' => Auth::user()->id, 'message' => $request->get('message')]);

            if (isset($request->file)) {
                $message->addMedia($request->file)->toMediaCollection('file');
            }

            $message = Message::find($message->id);

            return $this->sendResponse($message, __('Messages sended successfully'));
        } catch (\Exception $e) {
            report($e);
            return $this->sendError($e->getMessage());
        }
    }
}
