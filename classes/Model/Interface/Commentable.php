<?php
/**
 * @author Sławomir Olchawa <slawooo@gmail.com>
 */

/**
 * Interface Model_Interface_Commentable
 *
 * @property Model_Commentbox $commentbox
 */
interface Model_Interface_Commentable
{
    /**
     * @param string $text
     * @param Model_Abstract_User|null $author
     * @return Model_Comment
     */
    public function addComment($text, Model_Abstract_User $author = null);

    /**
     * @return Model_Comment
     */
    public function getComments();
}
