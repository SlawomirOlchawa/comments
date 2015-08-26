<?php
/**
 * @author Sławomir Olchawa <slawooo@gmail.com>
 */

/**
 * Class Component_Comment_Form
 */
class Component_Comment_Form extends Tag_Block
{
    /**
     * @var Model_Abstract_Entity
     */
    protected $_entity;

    /**
     * @param Model_Abstract_Entity $entity
     */
    public function __construct(Model_Abstract_Entity $entity)
    {
        parent::__construct();

        $this->_entity = $entity;
        Helper_Includer::addCSS('media/mod/comments/css/main.css');
    }

    /**
     * @return string
     */
    protected function _render()
    {
        $this->addCSSClass('comment_form');

        if (!Auth::instance()->logged_in())
        {
            $info = new Tag_Paragraph('Musisz być zalogowany, aby dodawać komentarze.');
            $info->addCSSClass('light');
            $this->add($info);
        }
        else
        {
            $form = new Tag_Form($this->_entity->getURL().'/dodaj-komentarz');
            $form->addCSSClass('comment_add');
            $textarea = new Tag_Form_Row_Textarea('Twój komentarz', 'comment');

            $form->add($textarea);
            $form->add(new Tag_Form_Row_Input('', 'submit', 'Dodaj komentarz', 'submit'));

            $clear = new Tag_Block();
            $clear->addCSSClass('clear');
            $form->add($clear);

            $this->add($form);

            $error = Session::instance()->get('comment-error');

            if (!empty($error))
            {
                $p = new Tag_Paragraph('Błąd!'.PHP_EOL.$error.'.');
                $p->addCSSClass('error');
                $this->add($p);
                $textarea->setHTML(Session::instance()->get('comment'));

                Session::instance()->delete('comment-error');
                Session::instance()->delete('comment');
            }
        }

        return parent::_render();
    }
}
