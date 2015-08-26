<?php
/**
 * @author Sławomir Olchawa <slawooo@gmail.com>
 */

/**
 * Class Model_User
 *
 * @property int $id
 * @property Model_Comment $comments_given
 * @property Model_Commentbox $commentbox
 */
class Model_User extends Model_Abstract_User
{
    /**
     * @var string
     */
    protected $_table_name = 'users';

    /**
     * @var array
     */
    protected $_belongs_to = array
    (
        'commentbox' => array
        (
            'model' => 'Commentbox',
            'foreign_key' => 'commentbox_id'
        ),
    );

    /**
     * @var array
     */
    protected $_has_many = array
    (
        'user_tokens' => array('model' => 'User_Token'),
        'roles'       => array('model' => 'Role', 'through' => 'roles_users'),

        // comments given by this user
        'comments_given' => array
        (
            'model' => 'Comment',
            'foreign_key' => 'author_id',
        ),
    );

    /**
     * @return ORM
     */
    public function delete()
    {
        $this->commentbox->delete();

        return parent::delete();
    }
}

