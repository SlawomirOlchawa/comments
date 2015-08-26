<?php
/**
 * @author Sławomir Olchawa <slawooo@gmail.com>
 */

/**
 * Class Component_Comments
 */
class Component_Comments extends Container
{
    /**
     * @param Model_Abstract_Entity $entity
     */
    public function __construct(Model_Abstract_Entity $entity)
    {
        $comments = $entity->getComments();
        $comments->order_by('created', 'DESC')->limit(100);

        $this->add(new Tag_Anchor('comments'));
        $this->add(new Component_Comment_Form($entity));
        $this->add(new Component_Comment_List($comments));

        Helper_Includer::addCSS('media/mod/comments/css/main.css');
    }
}
