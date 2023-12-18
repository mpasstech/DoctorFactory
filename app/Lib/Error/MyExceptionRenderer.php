<?php
App::uses('ExceptionRenderer', 'Error');

class MyExceptionRenderer extends ExceptionRenderer {

    public function notFound($error) {
        //$this->controller->redirect(array('controller' => 'errors', 'action' => 'error404'));
    }

    protected function _outputMessage($template) {
        $this->controller->layout = 'error';
        parent::_outputMessage($template);
        //$this->controller->redirect(array('controller' => 'errors', 'action' => 'error404'));

    }


}