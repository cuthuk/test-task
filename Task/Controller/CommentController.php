<?php

namespace Task\Controller;

use Task\Model\CommentModel;

use \Task\Controller\ControllerAbstract as Controller;

class CommentController extends Controller
{
    public function createAction()
    {
        $comment = new CommentModel();
        if (count($_POST)) {
            if($_POST['parent_id']) {
               $result = $comment->insertChild([
                  'comment' => $_POST['comment'],
                  'author_id' => $_SESSION['user_id']
               ], $_POST['parent_id']);
               if ($result) {
                   return $this->_view->render('comment/item.twig', [
                       'item' => $result,
                   ]);
               } else {
                $error = "insert error";
               }
            } else {
                $error = "parent_id bad";
            }
        }
        header('HTTP/1.1 400 bad request');
        echo $error;
        die;
    }

    public function updateAction()
    {
        $comment = new CommentModel();
        if (count($_POST)) {
            if($_POST['id']) {
                $result = $comment->update([
                    'comment' => $_POST['comment'],
                ], ['id = ?' => $_POST['id']]);
                if ($result) {
                    return $this->_view->render('comment/item.twig', [
                        'item' => $result,
                    ]);
                } else {
                    $error = "update error";
                }
            } else {
                $error = "bad id";
            }
        }
        header('HTTP/1.1 400 bad request');
        echo $error;
        die;
    }

    public function deleteAction()
    {
        $comment = new CommentModel();
        if (count($_POST)) {
            if($_POST['id']) {
                $result = $comment->delete($_POST['id']);
                if ($result) {
                    return $this->_view->render('comment/item.twig', [
                        'item' => $result,
                    ]);
                } else {
                    $error = "delete error";
                }
            } else {
                $error = "bad id";
            }
        }
        header('HTTP/1.1 400 bad request');
        echo $error;
        die;
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