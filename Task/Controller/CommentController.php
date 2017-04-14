<?php

namespace Task\Controller;

use Task\Model\CommentModel;

use \Task\Controller\ControllerAbstract as Controller;

class CommentController extends Controller
{
    public function addAction()
    {
        $comment = new CommentModel();
        if (count($_POST)) {
            if($_POST['parent_id']) {
               $result = $comment->insertChild([
                  'comment' => $_POST['comment'],
                  'author_id' => $_SESSION['user_id']
               ], $_POST['parent_id']);
               return $this->_view->render('comment/item.twig', [
                   'item' => $result,
               ]);
            }
            header('HTTP/1.1 400 bad request');
            echo "parent_id bad";
            die;
        }
    }

    public function getChildsAction()
    {
        $parentId = $_GET['parentId'];
        $model = new CommentModel();
        $comments = $model->getChildTree($parentId);
        return $this->_view->render('comment/list.twig', [
            'comments' => $comments
        ]);
    }
}