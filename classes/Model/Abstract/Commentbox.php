<?php
/**
 * @author Sławomir Olchawa <slawooo@gmail.com>
 */

/**
 * Class Model_Commentbox
 *
 * @property int $id
 * @property Model_Comment $comments
 * @property Model_Abstract_Entity $owner
 */
abstract class Model_Abstract_Commentbox extends Model_Abstract_Record
{
    /**
     * @var string
     */
    protected $_table_name = 'commentboxes';

    /**
     * @var array
     */
    protected $_has_many = array
    (
        'comments' => array
        (
            'model' => 'Comment',
            'foreign_key' => 'commentbox_id'
        )
    );

    /**
     * @param string $key
     * @return mixed
     */
    public function get($key)
    {
        if ($key === 'owner')
        {
            foreach ($this->has_one() as $parent=>$details)
            {
                if ($this->$parent->id != null)
                {
                    $key = $parent;
                }
            }

            if ($key === 'owner')
            {
                return null;
            }
        }

        return parent::get($key);
    }

    /**
     * @return ORM
     */
    public function delete()
    {
        foreach ($this->comments->findAll() as $comment)
        {
            /** @var Model_Comment $comment */
            $comment->delete();
        }

        return parent::delete();
    }
}
