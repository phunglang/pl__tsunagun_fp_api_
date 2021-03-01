<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Http\Requests\BlockRequest;
use App\Http\Requests\MessageRequest;
use App\Http\Requests\ReportRequest;
use App\Interfaces\UserRepositoryInterface;
use App\Repositories\MessageRepository;
use App\Repositories\ReportRepository;
use App\Notifications\PushChatNotification;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ChatController extends Controller
{
    private $reportRepository;
    private $userRepository;
    private $messageRepository;

    public function __construct(ReportRepository $reportRepository,UserRepositoryInterface $userRepository,MessageRepository $messageRepository) {
        $this->reportRepository = $reportRepository;
        $this->userRepository = $userRepository;
        $this->messageRepository = $messageRepository;//
    }

    /**
     * Get list user chat with mess last.
     * By Trong Luật
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $listUser = $this->userRepository->getListContact();
        return response()->json([
            'message' => 'Success',
            'data' => $listUser
        ], 200);
    }

    /**
     * count mess it not read.
     * By Trong Luật
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function countMess(Request $request)
    {
        $countMessNotRead = $this->messageRepository->countMessNotRead();
        return response()->json([
            'message' => 'Success',
            'data' => [
                'mess_not_read' =>  $countMessNotRead
            ]
        ], 200);
    }

    /**
     * report user .
     * By Trong Luật
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sendReport(ReportRequest $request)
    {
        $dataRequest = $request->only('reason','idUserReported');
        $this->reportRepository->saveReport($dataRequest);
        return response()->json([
            'message' => 'Success',
        ], 200);
    }

    /**
     * Block User .
     * By Trong Luật
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sendBlock(BlockRequest $request)
    {
        $dataRequest = $request->only('idUserReported');

        $this->userRepository->blockUser($dataRequest);
        return response()->json([
            'message' => 'Success',
        ], 200);
    }

     /**
     * send mess .
     * By Trong Luật
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sendMess(MessageRequest $request)
    {
        $dataRequest = $request->only('messageText','idUserEndPoint','imageFile');
        $message = $this->messageRepository->sendMess($dataRequest);
        return response()->json([
            'message' => 'Success',
        ], 200);
    }

    /**
     * send mess .
     * By Trong Luật
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function conversationDetail(Request $request)
    {
        $dataRequest = $request->only('idUserEndPoint');
        // $userData = $this->userRepository->find($dataRequest['idUserEndPoint']);

        $userData = $this->messageRepository->conversationDetail($dataRequest);


        return response()->json([
            'message' => 'Success',
            'data' => $userData
        ], 200);
    }


    


}
