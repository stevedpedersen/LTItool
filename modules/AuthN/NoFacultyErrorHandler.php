<?php

/**
 * Workstation Selection application error handler for 404 errors.
 * 
 * @author      Charles O'Sullivan (chsoney@sfsu.edu)
 * @copyright   Copyright &copy; San Francisco State University
 */
class LTITool_uthN_NoFacultyErrorHandler extends Classrooms_Master_ErrorHandler
{
    public static function getErrorClassList () { return array('LTITool_uthN_ExNoFaculty'); }
    
    protected function getStatusCode () { return 404; }
    protected function getStatusMessage () { return 'No Faculty'; }
    protected function getTemplateFile () { return 'error-nofaculty.html.tpl'; }

    protected function handleError ($error)
    {
        parent::handleError($error);
    }
}
