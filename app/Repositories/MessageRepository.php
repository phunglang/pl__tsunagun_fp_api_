<?php

namespace App\Repositories;

use App\Events\MessageSent;
use App\Helpers\FileManage;
use App\Models\Message;
use App\Models\User;
use App\Notifications\PushChatNotification;
use Illuminate\Support\Facades\Auth;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
/**
 * Class UserRepository.
 */
class MessageRepository extends BaseRepository
{

   /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return  Message::class;
    }

    public function countMessNotRead(){
       return $this->model->where('client_id', Auth::id())->where('status', 0)->get()->count();
    }

    public function sendMess($dataRequest){
        $user = Auth::user();
        $dataInsertMess = [
            'own_id' =>  Auth::id(),
            'client_id' => $dataRequest['idUserEndPoint'],
        ];
        if(!empty($dataRequest['messageText']))
        $dataInsertMess['content'] = $dataRequest['messageText'];
        $message = $this->model->create($dataInsertMess);
        $user->push('chat_user_ids',$dataRequest['idUserEndPoint'], true);
        $userClient = User::find($dataRequest['idUserEndPoint']);
        $userClient->push('chat_user_ids',Auth::id(), true);
        if(!empty($dataRequest['imageFile'])) {
            foreach ($dataRequest['imageFile'] as $item) {
                $imageFileName = time().'.'.$item->getClientOriginalExtension();
                $file = new FileManage($imageFileName, $item, 'App\Models\File', 'public', 's3', 'uploads/tsunagun_fp');
                $file->uploadFileToS3([
                    'type' => 'image',
                    'messages_id' => $message->_id
                ]);
            };
        }
        dispatch(new PushChatNotification($message));
        broadcast($message)->toOthers();
    }

    public function conversationDetail($dataRequest){

        $id = $dataRequest['idUserEndPoint'];
        return $this->model
                    ->where(function ($q) use ($id) {
                        $q->where('own_id', Auth::id());
                        $q->where('client_id', $id);
                    })
                    ->orWhere(function ($q) use ($id) {
                        $q->where('own_id', $id);
                        $q->where('client_id', Auth::id());
                    })
                    ->with('getFiles')
                    ->get();
    }
}
