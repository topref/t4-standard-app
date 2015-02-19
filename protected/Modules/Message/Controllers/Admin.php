<?php

namespace App\Modules\Message\Controllers;


use App\Components\Admin\Controller;
use App\Modules\Message\Models\Message;
use T4\Mail\Sender;


class Admin
    extends Controller
{

    const PAGE_SIZE = 20;

    public function actionDefault($page = 1)
    {
        $this->data->itemsCount = Message::countAll();
        $this->data->pageSize = self::PAGE_SIZE;
        $this->data->activePage = $page;

        $this->data->items = Message::findAll([
            'order' => 'q_datetime DESC',
            'offset'=> ($page-1)*self::PAGE_SIZE,
            'limit'=> self::PAGE_SIZE
        ]);
    }


    public function actionAnswer($id)
    {
        $this->data->item = Message::findByPK($id);
    }

    public function actionView($id)
    {
        $this->data->id = $id;
    }

    public function actionSend($id, $email = null, $theme, $answer)
    {
        $message = Message::findByPK($id);
        $message->fill($this->app->request->post);
        $message->save();

        $mail = new Sender();
        $mail->sendMail('verablajennaya@mail.ru', $theme, $answer);
        $this->redirect('/admin/message');
    }

}