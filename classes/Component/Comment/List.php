<?php
/**
 * @author Sławomir Olchawa <slawooo@gmail.com>
 */

/**
 * Class Component_Comment_List
 */
class Component_Comment_List extends Tag_Block
{
    /**
     * @var Model_Comment
     */
    protected $_comments;

    /**
     * @param Model_Comment $comments
     */
    public function __construct(Model_Comment $comments)
    {
        parent::__construct();

        $this->_comments = $comments;
        Helper_Includer::addCSS('media/mod/comments/css/main.css');
    }

    /**
     * @return string
     */
    protected function _render()
    {
        $commentsData = $this->_comments->findAll();

        if ($commentsData->count() === 0)
        {
            $info = new Tag_Paragraph('Nie ma jeszcze komentarzy.');
            $info->addCSSClass('light');
            $this->add($info);
        }
        else
        {
            $table = new Tag_Table();
            $table->addCSSClass('comment_list');

            foreach ($commentsData as $comment)
            {
                $tableRow = new Tag_Table_Row();
                $table->add($tableRow);

                $tableCellLeft = new Tag_Table_Cell();
                $tableCellLeft->addCSSClass('slim');
                $tableCellRight = new Tag_Table_Cell();
                $tableCellRight->add($this->_getComment($comment));

                $tableRow->add($tableCellLeft);
                $tableRow->add($tableCellRight);

                if (!empty($comment->author->id))
                {
                    $author = new Component_SmartLink(Text::limit_chars($comment->author->name,16),
                        $comment->author->getURL());
                }
                else
                {
                    $author = new Tag_Block('Anonim');
                }

                $author->addCSSClass('author');

                $timeAdded = new Tag_Block(Helper_Format::friendlyTime($comment->created));
                $timeAdded->addCSSClass('time_added');

                $tableCellLeft->add($author);
                $tableCellLeft->add($timeAdded);

                if (Auth::instance()->logged_in('admin'))
                {
                    $formDelete = new Tag_Form_PostLink('admin/delete-comment', 'Usuń', 'id', $comment->id);
                    $tableCellAdmin = new Tag_Table_Cell();
                    $tableCellAdmin->add($formDelete);
                    $tableCellAdmin->addCSSClass('admin');
                    $tableRow->add($tableCellAdmin);
                }
            }

            $this->add($table);
        }

        return parent::_render();
    }

    /**
     * @param Model_Comment $comment
     * @return Component
     */
    protected function _getComment(Model_Comment $comment)
    {
        return new Tag_Block($comment->comment);
    }
}
