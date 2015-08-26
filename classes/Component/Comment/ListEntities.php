<?php
/**
 * @author Sławomir Olchawa <slawooo@gmail.com>
 */

/**
 * Class Component_Comment_ListEntities
 */
class Component_Comment_ListEntities extends Component_Comment_List
{
    /**
     * @param Model_Comment $comment
     * @return Component
     */
    protected function _getComment(Model_Comment $comment)
    {
        /** @var Model_Abstract_Entity $owner */
        $owner = $comment->getOwner();

        $hyperlink = new Component_SmartLink($owner->name, $owner->getURL());
        $type = new Tag_Block($owner->getPluralName());
        $type->addCSSClass('commented-entity-type');
        $content = new Tag_Block(Text::limit_chars(Helper_Format::removeNewLineChars($comment->comment), 220));

        $result = new Tag_Block();
        $result->addCSSClass('comment-content');
        $result->addCSSClass('overflow-visible');
        $result->add($hyperlink);
        $result->add($type);
        $result->add($content);

        return $result;
    }
}
