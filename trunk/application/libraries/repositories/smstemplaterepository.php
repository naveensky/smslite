<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hitanshu
 * Date: 25/5/13
 * Time: 4:42 PM
 * To change this template use File | Settings | File Templates.
 */

class SMSTemplateRepository
{

    public function insertNewTemplateRequest($templateName, $templateBody, $schoolId)
    {
        try {
            $requestedTemplate = new RequestedTemplate();
            $requestedTemplate->name = $templateName;
            $requestedTemplate->body = $templateBody;
            $requestedTemplate->schoolId = $schoolId;
            $requestedTemplate->status = 'pending';
            $requestedTemplate->save();
        } catch (Exception $e) {
            Log::exception($e);
            return false;
        }
        return true;
    }
}