<?php
/**
 * Feedback Controller
 *
 * PHP version 5
 *
 * Copyright (C) The National Library of Finland 2015-2017.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License version 2,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * PHP version 5
 *
 * @category VuFind
 * @package  Controller
 * @author   Josiah Knoll <jk1135@ship.edu>
 * @author   Ere Maijala <ere.maijala@helsinki.fi>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://vufind.org   Main Site
 */
namespace Finna\Controller;
use Zend\Mail as Mail;

/**
 * Feedback Controller
 *
 * @category VuFind
 * @package  Controller
 * @author   Josiah Knoll <jk1135@ship.edu>
 * @author   Ere Maijala <ere.maijala@helsinki.fi>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://vufind.org   Main Site
 */
class FeedbackController extends \VuFind\Controller\FeedbackController
{
    use \Finna\Form\DynamicFormTrait;

    /**
     * Display Feedback home form.
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function homeAction()
    {
        return $this->forwardTo('Feedback', 'Form');
    }

    /**
     * Receives submitted form data and sends an email.
     * Form configuration is specified in form<form-id>.ini
     *
     * @return void
     */
    public function emailAction()
    {
        $view = $this->createViewModel();

        $formId = $this->params()->fromPost(
            'form-id', $this->params()->fromQuery('form-id')
        );
        $view->useRecaptcha = $this->recaptcha()->active($formId);
        $view->formId = $formId;
        $captcha = $this->params()->fromPost('captcha');

        // Support the old captcha mechanism for now
        if ($captcha == $this->translate('feedback_captcha_answer')) {
            $view->useRecaptcha = false;
        }

        // Process form submission:
        if ($this->formWasSubmitted('submit', $view->useRecaptcha)) {
            $formConfig = $this->serviceLocator->get('VuFind\Config')
                ->get("form{$formId}");

            if (!$emailSettings = $this->getFormEmailSettings($formConfig)) {
                throw new \Exception(
                    'Feedback error: '
                    . " missing form email configuration (form $formId)"
                );
            }

            // Check submitted fields
            foreach ($this->getFormElements($formConfig) as $key => $val) {
                if (empty($val['required'])) {
                    continue;
                }
                $submitted = $this->params()->fromPost(
                    $key, $this->params()->fromQuery($key)
                );
                if ($submitted === null) {
                    throw new \Exception(
                        "Feedback error: missing required field: $key (form $formId)"
                    );
                }
            }

            $config = $this->serviceLocator->get('VuFind\Config')
                ->get('config');
            $emailConfig = $formConfig['General']['email'];

            $subject = !empty($emailConfig['subject'])
                ? $emailConfig['subject'] : 'Your Library';
            $subject = $this->translate($subject);
            
            $siteTitle = $config->Site->title;
            if (strlen($siteTitle) > 50) {
                $siteTitle = substr($siteTitle, 0, 50);
            }
            $subject .= " ($siteTitle)";

            if (!$recipientElement = $this->getFormRecipientElement($formConfig)) {
                $recipientEmail = $config->Site->email;
            }
            
            $recipientName = !empty($emailConfig['recipient-name'])
                ? $emailConfig['recipient-name'] : 'Your Library';

            $senderEmail = !empty($emailConfig['sender-email'])
                ? $emailConfig['sender-email'] : 'noreply@vufind.org';
            $senderName = !empty($emailConfig['sender-name'])
                ? $emailConfig['sender-name'] : 'VuFind Feedback';

            $message = '';
            foreach ($this->getFormElements($formConfig) as $key => $val) {
                if ($key == 'General' || !empty($val['disable'])) {
                    continue;
                }

                $submitted = $this->params()->fromPost(
                    $key, $this->params()->fromQuery($key)
                );

                if ($recipientElement && $key == $recipientElement) {
                    // Override form recipient address
                    $optionIds = array_map(
                        function ($option) {
                            list($id, $label) = explode('|', $option);
                            return $id;
                        }, $val['options']->toArray()
                    );
                    $address
                        = $this->getFormOptionFromHash($submitted, $optionIds);
                    if ($address) {
                        $recipientEmail = $address;
                    }
                    continue;
                }

                if (!empty($val['title'])) {
                    // NOTE: must use double quotes here
                    $message .= "\r\n";
                    $message .= $this->translate($val['title']);
                    $message .= "\r\n";
                }
                $message
                    .= $this->translate(
                        !empty($val['label']) ? $val['label'] : $key
                    ) . ': ';
                $submitted = $this->params()->fromPost(
                    $key, $this->params()->fromQuery($key)
                );
                if ($submitted !== null) {
                    $message .= $submitted;
                }
                // NOTE: must use double quotes here
                $message .= "\r\n";
            }

            if ($recipientEmail == null) {
                throw new \Exception(
                    'Feedback error: recipient not set in form '
                    . 'configuration or config.ini'
                );
            }

            $validator = new \Zend\Validator\EmailAddress();
            if (!$validator->isValid($recipientEmail)) {
                throw new \Exception(
                    "Feedback error: invalid recipient email: $recipientEmail"
                );
            }
            

            // This sets up the email to be sent
            $mail = new Mail\Message();
            $mail->setEncoding('UTF-8');
            $mail->setBody($message);
            $mail->setFrom($senderEmail, $senderName);
            if (!empty($emailConfig['reply-to-email'])
                && !empty($emailConfig['reply-to-name'])
            ) {
                $replyEmailField = $emailConfig['reply-to-email'];
                $replyEmail = $this->params()->fromPost(
                    $replyEmailField, $this->params()->fromQuery($replyEmailField)
                );

                $replyNameField = $emailConfig['reply-to-name'];
                $replyName = $this->params()->fromPost(
                    $replyNameField, $this->params()->fromQuery($replyNameField)
                );
                if ($replyEmail && $replyName) {
                    $mail->setReplyTo($replyEmail, $replyName);
                }
            }
            $mail->addTo($recipientEmail, $recipientName);
            $mail->setSubject($subject);
            $headers = $mail->getHeaders();
            $headers->removeHeader('Content-Type');
            $headers->addHeaderLine('Content-Type', 'text/plain; charset=UTF-8');

            try {
                $this->serviceLocator->get('VuFind\Mailer')->getTransport()
                    ->send($mail);
                $params = [];
                if (!empty($formConfig['General']['response'])) {
                    $view->response = $formConfig['General']['response'];
                }
                $view->setTemplate('feedback/response');
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage('feedback_error');
            }
        }
        return $view;
    }

    /**
     * Display dynamic form.
     *
     * @return void
     */
    public function formAction()
    {
        $formId = $this->params()->fromRoute('id', $this->params()->fromQuery('id'));
        if (!$formId) {
            $formId = 'FeedbackSite';
        }

        $view = $this->createViewModel();
        $view->formId = $formId;
        $view->useRecaptcha = $this->recaptcha()->active($formId);
        $view->setTemplate('feedback/form.phtml');

        $configReader = $this->serviceLocator->get('VuFind\Config');
        if (!$config = $this->getFormConfig($formId, $configReader)) {
            $this->flashMessenger()->addMessage(
                "Missing configuration for form '$formId'", 'error'
            );
        }

        return $view;
    }
}
