<?php
if (!defined('IN_EXBB')) die('Hack attempt!');

$this->LANG['UserAdmin']		= 'Управление пользователями';
$this->LANG['UserNotFound']		= 'Извините, такого пользователя не существует';
$this->LANG['UserDeleted']		= 'Пользователь был успешно удалён.';
$this->LANG['UserNotDeleted']	= 'Ошибка при удалении пользователя!';
$this->LANG['WrongEmail']		= 'Неправильный формат E-mail адреса!';
$this->LANG['LogRecountUsers']	= 'Пересчет пользователей';
$this->LANG['LogEditUser']		= 'Изменение пользователя';
$this->LANG['LogMassMail']		= 'Разослана рассылка ';
$this->LANG['LogCensor']		= 'Изменения списка автоцензора';
$this->LANG['ClearLogInFile']	= 'Очистка истории за %s';
$this->LANG['LogAdmin']			= 'админцентр';
$this->LANG['Moderation']		= 'модерация';

/*
	recount users
*/
$this->LANG['UserCountUpd']		= 'Счетчик пользователей обновлен.<br>В настоящее время на форуме зарегистрировано пользователей: ';

/*
	edit user
*/
$this->LANG['EditingName']		= 'Здесь вы можете изменить информацию о пользователе ';
$this->LANG['RegDate']			= 'Дата регистрации';
$this->LANG['LastVisitDate']	= 'Последний визит';
$this->LANG['NeverLogged']		= 'Никогда';
$this->LANG['UserTitle']		= 'Персональное звание';
$this->LANG['UserEmail']		= 'Адрес e-mail';
$this->LANG['NewName']			= 'Изменить имя пользователя';
$this->LANG['NewNameNotice']	= '(оставьте поле без изменений, чтобы сохранить имя)';
$this->LANG['NewPassword']		= 'Новый пароль';
$this->LANG['NewPassNotice']	= '(оставьте поле пустым, чтобы сохранить старый пароль)';
$this->LANG['Profile']			= 'Профиль';
$this->LANG['WWW']				= 'Домашняя страничка';
$this->LANG['VisitUserWWW'] 	= 'посетить сайт';
$this->LANG['AOL']				= 'Имя в AOL';
$this->LANG['ICQ']				= 'Номер в ICQ';
$this->LANG['From']				= 'Откуда';
$this->LANG['Interests']		= 'Интересы';
$this->LANG['Signature']		= 'Подпись';
$this->LANG['BoardOpt']			= 'Настройки для форума';
$this->LANG['SkinUsed']			= 'Используемый скин';
$this->LANG['CanUpload']		= 'Разрешить загрузку файлов?';
$this->LANG['CanUploadMes']		= 'Позволить пользователю прикреплять файлы к сообщению в форумах, в которых это разрешено.';
$this->LANG['Replies']			= 'Ответов';
$this->LANG['Avatar']			= 'Аватар';
$this->LANG['NoPrivateForums']	= 'Нет приватных форумов';
$this->LANG['PrivateForums']	= 'Приватные форумы';
$this->LANG['PrivateNotice']	= '(поставьте галочку, что бы разрешить доступ)';
$this->LANG['UserStatus']		= 'Статус пользователя';
$this->LANG['BannedUser']		= 'Заблокированный пользователь';
$this->LANG['User']				= 'Пользователь';
$this->LANG['SuperModer']		= 'Супер модератор';
$this->LANG['Admin']			= 'Администратор';
$this->LANG['DeletUser']		= 'Удалить этого пользователя?';
$this->LANG['DeletUserMes']		= 'Щелкните здесь для удаления этого пользователя. Операцию нельзя будет отменить.';
$this->LANG['PassNotChanged']	= "не менялся";
$this->LANG['AdminPassNotify'] 	= 'Изменение имени или пароля';
$this->LANG['UserUpdatedOk']	= 'Личные данные пользователя обновлены';
$this->LANG['EmailNewPassName'] = "Внимание! Это письмо сгенерировано роботом, на него отвечать не надо!
>---------------------------------------------------------------------
Автор: Администратор
Дата: %s
Текст сообщения:
>------------------------------------------
Администратор поменял Ваш пароль или имя на форуме
Ваше имя и пароль написано ниже:
Имя: %s
Пароль: %s
>------------------------------------------
%s/index.php";

/*
	visitslogs
*/
$this->LANG['VisitsLogs']		= 'История посещений и действий на форуме';
$this->LANG['VisitsLogsNotify']	= 'Здесь Вы можете просмотреть историю действия пользователей на форуме за каждый день';
$this->LANG['LogTitle']			= 'История посещений (%s) за ';
$this->LANG['ForumLogOff']		= 'отключена';
$this->LANG['ForumLogOn']		= 'включена';
$this->LANG['DelLog']			= 'Очистить историю посещений';
$this->LANG['DelLogDay']		= 'за этот день';
$this->LANG['DelAllLogs']		= 'за все дни';
$this->LANG['ShowLog']			= 'Показать';
$this->LANG['LogFileNotFound']	= 'Не найден файл истории за указанный день!';

/*
	massmail
*/
$this->LANG['EmailNotEmpty']	= 'Поля "Тема" и "Текст сообщения" обязательны к заполнению!';
$this->LANG['EmailAdminError']	= 'Ошибка чтения файла базы пользователей!';
$this->LANG['MassMailSended']	= 'Сообщение разослано на %d адресов!';
$this->LANG['AdminMassMail'] 	= 'Массовая рассылка';
$this->LANG['MassMailNotify'] 	= 'Здесь вы можете разослать E-mail всем участникам форума';
$this->LANG['MessageBody']		= 'Тело сообщения';
$this->LANG['MessageSubject']	= 'Тема сообщения';
$this->LANG['MessageText']		= 'Текст сообщения';
$this->LANG['MassMailText'] 	= "Это e-mail сообщение послано вам администратором сайта %s
( %s )
~~~~~~~~~~~~~~~~~~~~~~~~~~~~
%s";

/*
	censor
*/
$this->LANG['Censor']			= 'Автоцензор';
$this->LANG['CensorDesc']		= 'Здесь вы можете добавить, изменить или удалить слова, которые будут автоматически подвергаться цензуре на ваших форумах. <b><br>Пожалуйста, обратите внимание:</b> этот фильтр создан для предотвращения появления нежелательных выражений в сообщениях. Вам нужно выбрать какие слова заменять и чем.<br>Эта замена выполняется <b>во время отправки сообщения</b>, и когда пользователь редактировал сообщение или цитировал.<br> Однако, это означает, что замена действует постоянно. Как только Вы введете новые слова, все сообщения впоследствии будут обрабатываться  с учетом изменений фильтра.<br><br><b>Инструкция:</b> Просто введите \'плохое слово\' и слово на которое оно заменится через знак \'=\'. Убедитесь что каждый набор слов <b>находится на одной линии</b>';
$this->LANG['BadfilterOk']		= 'Список слов для автоцензора сохранён';
$this->LANG['BadfilterFail']	= 'Ошибка при сохранении файла! Список слов для автоцензора не сохранён.';

/*
	select user
*/
$this->LANG['NoSearchVars']		= 'Не заданы критерии для поиска!';
$this->LANG['UserAdminInfo']	= 'Здесь вы можете изменить информацию о пользователе и прочие специальные опции.';
$this->LANG['SelectUser']		= 'Выберите пользователя';
$this->LANG['FoundByName']		= 'По имени';
$this->LANG['FoundByEmail']		= 'По E-mail';
$this->LANG['FindUser']			= 'Найти пользователя';
?>