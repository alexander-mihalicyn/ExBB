<?php
if (!defined('IN_EXBB')) die('Hack attempt!');

$lang['convertation'] = 'Программа обновления (конвертации) ExBB 1.x -> ExBB 2.0';
$lang['welcomeToConvertation'] = '
	<p>Данная программа предназначена для обновления ExBB Forum Engine 1.x до ExBB 2.0.</p>
	<p><b>Перед началом операции обновления СДЕЛАЙТЕ РЕЗЕРВНУЮ КОПИЮ ВСЕХ ФАЙЛОВ ВАШЕГО ФОРУМА!</b></p>
	<p>Для начала обновления форума убедитесь что на все папки и файлы внутри директории <strong>data/</strong> установлены права (CHMOD) на запись. О том, как правильно установить права на запись, можно узнать на <a href="http://exbb.info/community">форуме поддержки</a> или у вашего хостинг-пройвайдера.</p>
	<p>При возникновении проблем, трудностей или вопросов при установке, вы можете обратиться на <a href="http://exbb.info/community">форум поддержки</a></p>
';

$lang['dataMovingText'] = '
	<p>Происходит процесс перемещения файлов форума в соответствие с требованиями новой версии.</p>
';

$lang['charsetConvertText'] = '
	<p>Происходит процесс преобразования кодировки файлов форума в соответствие с требованиями новой версии.</p>
	<p>Осталось преобразовать файлы:</p>
';

$lang['finishText'] = '
	<p>Данные Вашего форума сконвертированы и перенесены!</p>
';

$lang['yes'] = 'Да';
$lang['no'] = 'Нет';

$lang['checkPHPConfiguration'] = 'Проверка конфигурации PHP';
$lang['phpParameter'] = 'Параметр';
$lang['phpParameterCurrentValue'] = 'Текущее значение';
$lang['phpParameterOptimalValue'] = 'Рекомендуемое (минимальное) значение';

$lang['phpParameterVersion'] = 'Версия PHP';
$lang['phpParameterSQLite3'] = 'Модуль SQLite3';
$lang['phpParameterGzip'] = 'GZIP сжатие';

$lang['phpParameterSupported'] = 'Поддерживается';
$lang['phpParameterNotSupported'] = 'Не поддерживается';

$lang['checkFilesPermissions'] = 'Проверка доступности файлов';
$lang['fileIsExists'] = 'Существует';
$lang['fileIsWriteable'] = 'Доступен для записи';
$lang['fileIsReadable'] = 'Доступен для чтения';
$lang['filePath'] = 'Путь';

$lang['checkingErrors'] = 'Во время проверки требований были обнаружены ошибки. Для продолжения установки требуется исправить их и обновить страницу.';
$lang['checkingNotErrors'] = 'Ошибок обнаружено не было. Вы можете продолжить установку';
$lang['checkingWarnings'] = 'Во время проверки требований были обнаружены несоответствия оптимальным параметрам. Рекомендуется исправить их перед продолжением установки.';

$lang['forumSettings'] = 'Настройки форума';
$lang['adminAccountSettings'] = 'Настройки аккаунта администратора';
$lang['otherSettings'] = 'Другие настройки';

$lang['forumSettingUrlLabel'] = 'URL форума';
$lang['forumSettingUrlHelp'] = ' URL (должен начинаться с http://) адрес, где находится движок с форумом (например http://www.your_site.ru/forum)';
$lang['forumSettingChmodNewDirectoriesLabel'] = 'Права (CHMOD) на создаваемые папки';
$lang['forumSettingChmodNewFilesLabel'] = 'Права (CHMOD) на создаваемые файлы';
$lang['forumSettingChmodUploadsLabel'] = 'Права (CHMOD) на создаваемые файлы';
$lang['forumSettingTitle'] = 'Название форума';
$lang['forumSettingDescription'] = 'Описание форума';
$lang['forumSettingEmail'] = 'E-mail форума';
$lang['forumSettingInstallDemoData'] = 'Установить демо-данные';
$lang['forumSettingsUrlEmpty'] = 'Вы не ввели URL форума';
$lang['forumSettingsTitleEmpty'] = 'Вы не ввели название форума';
$lang['forumSettingsDescriptionEmpty'] = 'Вы не ввели описание форума';
$lang['forumSettingsEmailEmpty'] = 'Вы не ввели E-mail форума';
$lang['forumSettingsUrlInvalid'] = 'Вы ввели некорректный URL форума';
$lang['forumSettingsTitleInvalid'] = 'Вы не ввели некорректное название форума';
$lang['forumSettingsDescriptionInvalid'] = 'Вы ввели некорректное описание форума';
$lang['forumSettingsEmailInvalid'] = 'Вы не ввели некорректный E-mail форума';
$lang['forumSettingsChmodInvalid'] = 'Вы не ввели некорректное значение CHMOD';

$lang['adminAccountSettingsLogin'] = 'Логин';
$lang['adminAccountSettingsPassword'] = 'Пароль';
$lang['adminAccountSettingsConfirmPassword'] = 'Подтверждение пароля';
$lang['adminAccountSettingsEmail'] = 'E-mail';
$lang['adminAccountLoginEmpty'] = 'Вы не ввели логин';
$lang['adminAccountPasswordEmpty'] = 'Вы не ввели пароль';
$lang['adminAccountConfirmPasswordInvalid'] = 'Введённые пароли не совпадают';
$lang['adminAccountPasswordShort'] = 'Минимальная длина пароля - 5 символов';
$lang['adminAccountEmailEmpty'] = 'Вы не ввели E-mail';
$lang['adminAccountEmailInvalid'] = 'Вы ввели некорректный E-mail';
$lang['adminAccountLoginInvalid'] = 'Вы ввели некорректный логин';

$lang['stepConvertationWelcome'] = 'Добро пожаловать';
$lang['stepMovingDirectoriesAndFiles'] = 'Перемещение папок и файлов с данными';
$lang['stepCharsetConvertation'] = 'Преобразование кодировки файлов с данными';
$lang['stepFinish'] = 'Завершение конвертации';
$lang['installtionStepForumSettings'] = 'Начальная настройка';
$lang['installtionStepAdminAccountSettings'] = 'Создание аккаунта администратора';
$lang['installtionStepFinal'] = 'Завершение установки';
$lang['continueInstallation'] = 'Продолжить установку';
$lang['indexPage'] = 'Просмотр форума';
$lang['startConvertation'] = 'Начать/продолжить обновление';
$lang['finishInstallation'] = 'Завершить установку';
$lang['installationFinished'] = '
<p>Поздравляем!</p>
<p>Установка успешно завершена!</p>
<p>В случае возникновения вопросов вы можете обратиться на <a href="http://exbb.info/community">форум поддержики ExBB Forum Engine</a></p>
<p>Спасибо, что воспользовались нашим форумом!</p>
';

$lang['problemfilesdirslist'] = 'Возникли проблемы при перемещении следующих файлов и директорий:';
//$lang['filesdirslist'] = 'Перемещаемые файлы и директории:';
