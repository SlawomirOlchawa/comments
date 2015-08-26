<?php
/**
 * @author Sławomir Olchawa <slawooo@gmail.com>
 */

/**
 * Class Model_Comment
 *
 * @property int $id
 * @property string $comment
 * @property datetime $created
 * @property Model_Abstract_User $author
 * @property Model_Commentbox $commentbox
 */
class Model_Comment extends Model_Abstract_Record
{
    /**
     * @var string
     */
    protected $_table_name = 'comments';

    /**
     * @var array
     */
    protected $_belongs_to = array
    (
        'author' => array
        (
            'model' => 'User',
            'foreign_key' => 'author_id'
        ),
        'commentbox' => array
        (
            'model' => 'Commentbox',
            'foreign_key' => 'commentbox_id'
        ),
    );

    /**
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();

        $rules['comment'] = array
        (
            array('not_empty'),
            array('max_length', array(':value', 5000)),
        );

        return $rules;
    }

    /**
     * @return array
     */
    public function filters()
    {
        $filters = parent::filters();

        $filters['comment'] = $this->_defaultFilters;
        $filters['comment'][] = array('Text::limit_chars', array(':value', 4999));

        return $filters;
    }

    /**
     * @return Model_Abstract_Entity
     */
    public function getOwner()
    {
        $commentBox = $this->commentbox;

        if (!$commentBox->loaded())
        {
            $commentBox->find();
        }

        return $commentBox->owner;
    }

    /**
     * @param Model_Abstract_Entity $entity
     * @return Model_Comment
     */
    public static function getComments(Model_Abstract_Entity $entity)
    {
        return Model_Comment::_getCommentbox($entity)->comments;
    }

    /**
     * @param Model_Abstract_Entity $entity
     * @param string $text
     * @param Model_Abstract_User|null $author
     * @return Model_Comment
     */
    public static function addComment(Model_Abstract_Entity $entity, $text, Model_Abstract_User $author = null)
    {
        $comment = static::_initComment($entity, $text, $author);
        $comment->save();

        return $comment;
    }

    /**
     * @return Model_Comment
     */
    public static function getLatestComments()
    {
        $comments = new Model_Comment();
        $latestComments = $comments->order_by('created', 'DESC');

        return $latestComments;
    }

    /**
     * @param Model_Abstract_Entity $entity
     * @return Model_Abstract_Commentbox
     */
    protected static function _getCommentbox(Model_Abstract_Entity $entity)
    {
        if ($entity->commentbox->id == null)
        {
            // maybe using old cache (before commentbox was created) so we need to
            // reload live data from database to ensure not creating duplicated commentbox
            $entity->reload();

            if ($entity->commentbox->id == null)
            {
                // class Model_Commentbox (extending Model_Abstract_Commentbox) must be defined
                // in app and should contain "$_has_one" with entities which can be commented
                $commentbox = new Model_Commentbox();
                $commentbox->save();
                $entity->commentbox = $commentbox;
                $entity->save();
            }
        }

        return $entity->commentbox;
    }

    /**
     * @param Model_Abstract_Entity $entity
     * @param string $text
     * @param Model_Abstract_User|null $author
     * @return Model_Comment
     */
    protected static function _initComment(Model_Abstract_Entity $entity, $text, Model_Abstract_User $author = null)
    {
        /** @var Model_Comment $comment */
        $comment = new static();
        $comment->comment = $text;

        if (!empty($author))
        {
            $comment->author = $author;
        }

        $comment->commentbox = Model_Comment::_getCommentbox($entity);

        return $comment;
    }
}
