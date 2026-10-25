<?php return array (
  '561f704073eb379a80be94af1252f514' => 
  array (
    'criteria' => 
    array (
      'name' => 'controlerrorlog',
    ),
    'object' => 
    array (
      'name' => 'controlerrorlog',
      'path' => '{core_path}components/controlerrorlog/',
      'assets_path' => '',
    ),
  ),
  '538715504b0cc3a0e077c8d175cef6d8' => 
  array (
    'criteria' => 
    array (
      'key' => 'controlerrorlog.last_lines',
    ),
    'object' => 
    array (
      'key' => 'controlerrorlog.last_lines',
      'value' => '15',
      'xtype' => 'numberfield',
      'namespace' => 'controlerrorlog',
      'area' => 'controlerrorlog.main',
      'editedon' => NULL,
    ),
  ),
  '21c603d63ae0e5e65f68ec1a43072436' => 
  array (
    'criteria' => 
    array (
      'key' => 'controlerrorlog.refresh_freq',
    ),
    'object' => 
    array (
      'key' => 'controlerrorlog.refresh_freq',
      'value' => '60',
      'xtype' => 'numberfield',
      'namespace' => 'controlerrorlog',
      'area' => 'controlerrorlog.main',
      'editedon' => NULL,
    ),
  ),
  '1b48642b36f0fbac21b77a60dfc88ff1' => 
  array (
    'criteria' => 
    array (
      'key' => 'controlerrorlog.auto_refresh',
    ),
    'object' => 
    array (
      'key' => 'controlerrorlog.auto_refresh',
      'value' => '1',
      'xtype' => 'combo-boolean',
      'namespace' => 'controlerrorlog',
      'area' => 'controlerrorlog.main',
      'editedon' => NULL,
    ),
  ),
  '5519650257891af08b33ca792535e79c' => 
  array (
    'criteria' => 
    array (
      'key' => 'controlerrorlog.control_frontend',
    ),
    'object' => 
    array (
      'key' => 'controlerrorlog.control_frontend',
      'value' => '',
      'xtype' => 'combo-boolean',
      'namespace' => 'controlerrorlog',
      'area' => 'controlerrorlog.main',
      'editedon' => NULL,
    ),
  ),
  '09fb90097d856cd11748f336909d8ee8' => 
  array (
    'criteria' => 
    array (
      'key' => 'controlerrorlog.admin_email',
    ),
    'object' => 
    array (
      'key' => 'controlerrorlog.admin_email',
      'value' => '',
      'xtype' => 'textfield',
      'namespace' => 'controlerrorlog',
      'area' => 'controlerrorlog.main',
      'editedon' => NULL,
    ),
  ),
  '708a08da29309a47f1a961dca3d81c5d' => 
  array (
    'criteria' => 
    array (
      'category' => 'controlErrorLog',
    ),
    'object' => 
    array (
      'id' => 7,
      'parent' => 0,
      'category' => 'controlErrorLog',
      'rank' => 0,
    ),
  ),
  '469cdb4967fa13395576a89f5f67782d' => 
  array (
    'criteria' => 
    array (
      'name' => 'controlErrorLog',
    ),
    'object' => 
    array (
      'id' => 5,
      'source' => 1,
      'property_preprocess' => 0,
      'name' => 'controlErrorLog',
      'description' => '',
      'editor_type' => 0,
      'category' => 7,
      'cache_type' => 0,
      'plugincode' => 'switch ($modx->event->name) {
    case \'OnManagerPageBeforeRender\':
        if ($modx->hasPermission(\'error_log_view\')) {
            $modx->controller->addLexiconTopic(\'controlerrorlog:default\');
            $modx->controller->addCss($modx->getOption(\'assets_url\').\'components/controlerrorlog/css/mgr/main.css\');
            $modx->controller->addJavascript($modx->getOption(\'assets_url\').\'components/controlerrorlog/js/mgr/cel.default.js\');

            $response = $modx->runProcessor(\'mgr/errorlog/get\', array(\'includeContent\'=>false), array(\'processors_path\' => $modx->getOption(\'core_path\') . \'components/controlerrorlog/processors/\'));
            $resObj = $response->getObject();
            $_html = "<script>	var cel_config = " . $modx->toJSON($resObj) . "; </script>";
            $modx->controller->addHtml($_html);
        }
        break;
    case \'OnHandleRequest\':
        $email = $modx->getOption(\'controlerrorlog.admin_email\');
        if ($modx->context->get(\'key\') == \'mgr\' || empty($email) || !$modx->getOption(\'controlerrorlog.control_frontend\')) return;

        $f = $modx->getOption(xPDO::OPT_CACHE_PATH) . \'logs/error.log\';
        if (file_exists($f)) {
            $casheHash = $modx->cacheManager->get(\'error_log\');
            $hash = md5_file($f);
            if (filesize($f) > 0 && !empty($casheHash)  &&  $casheHash != $hash) {
                $modx->lexicon->load(\'controlerrorlog:default\');
                /** @var modPHPMailer $mail */
                $mail = $modx->getService(\'mail\', \'mail.modPHPMailer\');
                $mail->setHTML(true);

                $mail->set(modMail::MAIL_SUBJECT, $modx->lexicon(\'error_log_email_subject\'));
                $mail->set(modMail::MAIL_BODY, $modx->lexicon(\'error_log_email_body\', array(\'siteName\' => $modx->config[\'site_name\'])));
                $mail->set(modMail::MAIL_SENDER, $modx->getOption(\'emailsender\'));
                $mail->set(modMail::MAIL_FROM, $modx->getOption(\'emailsender\'));
                $mail->set(modMail::MAIL_FROM_NAME, $modx->getOption(\'site_name\'));

                $mail->address(\'to\', $email);
                $mail->address(\'reply-to\', $modx->getOption(\'emailsender\'));

                if (!$mail->send()) {
                    print (\'An error occurred while trying to send the email: \'.$modx->mail->mailer->ErrorInfo);
                }
                $mail->reset();
            }
            if ($casheHash != $hash) {
                $modx->cacheManager->set(\'error_log\', $hash, 0);
            }
        }
        break;
}',
      'locked' => 0,
      'properties' => NULL,
      'disabled' => 0,
      'moduleguid' => '',
      'static' => 0,
      'static_file' => 'core/components/controlerrorlog/elements/plugins/plugin.controlerrorlog.php',
      'content' => 'switch ($modx->event->name) {
    case \'OnManagerPageBeforeRender\':
        if ($modx->hasPermission(\'error_log_view\')) {
            $modx->controller->addLexiconTopic(\'controlerrorlog:default\');
            $modx->controller->addCss($modx->getOption(\'assets_url\').\'components/controlerrorlog/css/mgr/main.css\');
            $modx->controller->addJavascript($modx->getOption(\'assets_url\').\'components/controlerrorlog/js/mgr/cel.default.js\');

            $response = $modx->runProcessor(\'mgr/errorlog/get\', array(\'includeContent\'=>false), array(\'processors_path\' => $modx->getOption(\'core_path\') . \'components/controlerrorlog/processors/\'));
            $resObj = $response->getObject();
            $_html = "<script>	var cel_config = " . $modx->toJSON($resObj) . "; </script>";
            $modx->controller->addHtml($_html);
        }
        break;
    case \'OnHandleRequest\':
        $email = $modx->getOption(\'controlerrorlog.admin_email\');
        if ($modx->context->get(\'key\') == \'mgr\' || empty($email) || !$modx->getOption(\'controlerrorlog.control_frontend\')) return;

        $f = $modx->getOption(xPDO::OPT_CACHE_PATH) . \'logs/error.log\';
        if (file_exists($f)) {
            $casheHash = $modx->cacheManager->get(\'error_log\');
            $hash = md5_file($f);
            if (filesize($f) > 0 && !empty($casheHash)  &&  $casheHash != $hash) {
                $modx->lexicon->load(\'controlerrorlog:default\');
                /** @var modPHPMailer $mail */
                $mail = $modx->getService(\'mail\', \'mail.modPHPMailer\');
                $mail->setHTML(true);

                $mail->set(modMail::MAIL_SUBJECT, $modx->lexicon(\'error_log_email_subject\'));
                $mail->set(modMail::MAIL_BODY, $modx->lexicon(\'error_log_email_body\', array(\'siteName\' => $modx->config[\'site_name\'])));
                $mail->set(modMail::MAIL_SENDER, $modx->getOption(\'emailsender\'));
                $mail->set(modMail::MAIL_FROM, $modx->getOption(\'emailsender\'));
                $mail->set(modMail::MAIL_FROM_NAME, $modx->getOption(\'site_name\'));

                $mail->address(\'to\', $email);
                $mail->address(\'reply-to\', $modx->getOption(\'emailsender\'));

                if (!$mail->send()) {
                    print (\'An error occurred while trying to send the email: \'.$modx->mail->mailer->ErrorInfo);
                }
                $mail->reset();
            }
            if ($casheHash != $hash) {
                $modx->cacheManager->set(\'error_log\', $hash, 0);
            }
        }
        break;
}',
    ),
  ),
  'ac12725cff02fb3888d92326502b6368' => 
  array (
    'criteria' => 
    array (
      'pluginid' => 5,
      'event' => 'OnManagerPageBeforeRender',
    ),
    'object' => 
    array (
      'pluginid' => 5,
      'event' => 'OnManagerPageBeforeRender',
      'priority' => 0,
      'propertyset' => 0,
    ),
  ),
);