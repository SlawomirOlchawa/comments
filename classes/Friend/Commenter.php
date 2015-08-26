<?php
/**
 * @author Sławomir Olchawa <slawooo@gmail.com>
 */

/**
 * Class Friend_Commenter
 */
class Friend_Commenter extends Friend_Abstract_Entity
{
    /**
     * Post comment to entity and redirect back
     */
    public function postComment()
    {
        $entity = $this->getEntity();
        $authInstance = Auth::instance();
        $comment = $this->_controller->request->post('comment');

        if ($authInstance->logged_in())
        {
            try
            {
                $user = new Model_User($authInstance->get_user());
                $entity->addComment($comment, $user);
            }
            catch (Exception $e)
            {
                $error = $this->_controller->getErrorMessage($e);

                Session::instance()->set('comment-error', $error);
                Session::instance()->set('comment', $comment);
            }
        }

        $this->_controller->redirect($entity->getURL().'#comments');
    }

    /**
     * Delete comment and redirect back to entity
     */
    public function deleteComment()
    {
        $id = $this->_controller->request->post('id');
        $comment = new Model_Comment($id);

        if ($comment->loaded())
        {
            $token = $this->_controller->request->post('token');
            $entity = $comment->getOwner();

            if (!empty($token) AND (Helper_Token::valid($token)))
            {
                $comment->delete();
            }

            $this->_controller->redirect($entity->getURL().'#comments');
        }

        exit();
    }
}
