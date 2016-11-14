<div class="type-descr">微信内容格式.  参考<a href="http://php.net/manual/en/function.sprintf.php" target="_new">sprintf</a> .</div>
<div class="form-field">
    <strong>标题:</strong>
    <div class="descr">发送消息标题格式.</div>
    <?php
        if($channel->getSubjectFormat())
            $subj = $channel->getSubjectFormat();
        else
            $subj = '服务离线';
        FormHelpers::createText('subject', $subj);
    ?>
    <div class="error"><?php FormHelpers::checkError('subject', $errors); ?></div>
    </div>
<div class="form-field">
    <strong>消息格式:</strong>
    <div class="descr">发送消息的格式.</div>
    <?php
        if($channel->getMessageFormat())
            $msg = $channel->getMessageFormat();
        else
            $msg = '%s:%d (%s) 离线.';
        FormHelpers::createTextArea('message', $msg, 'rows="5" cols="100"');
    ?>
    <div class="error"><?php FormHelpers::checkError('message', $errors); ?></div>
</div>
<div class="form-field">
    <strong>SCKEY:</strong>
    <div class="descr">Server酱SCKEY.</div>
    <?php FormHelpers::createText('sckey', $channel->getSCKey(), 'size="55"'); ?>
    <div class="error"><?php FormHelpers::checkError('sckey', $errors); ?></div>
</div>
