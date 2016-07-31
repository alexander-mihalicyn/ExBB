<?php
if (!defined('IN_EXBB')) die('Hack attempt!');

$this->LANG['PMClosed']			= 'Служба личных сообщений отключена!';
$this->LANG['Welcome2PM']		= ', добро пожаловать в Личный Ящик сообщений!';
$this->LANG['NewPMMessage']		= 'Сейчас для Вас есть <b>%d</b> сообщения(ий) во Входящих (Inbox)<br>Из них <b>%d</b> непрочитанных сообщений(ия)';
$this->LANG['NoticePM']			= '<b>Примечание:</b> Все сообщения, посланные Вам, могут быть прочитаны только Вами.
Напомните об этом другим пользователям, когда будете посылать свои сообщения.
Неправильное использование этой службы может привести к закрытию Вашего аккаунта(спам и т.п.)';
$this->LANG['MessageTitle']		= 'Тема сообщения';
$this->LANG['MessageStatus']	= 'Статус';
$this->LANG['MessageDate']		= 'Дата';
$this->LANG['ReadedSts']		= 'Прочитано';
$this->LANG['NotReadedSts']		= 'Непрочитано';
$this->LANG['MesNotReaded']		= 'Непрочитанное сообщение';
$this->LANG['MesReaded']		= 'Прочитанное сообщение';
$this->LANG['EmptyData']		= 'Нет ни одного сообщения!';
$this->LANG['MessNotExists']	= 'Выбранное сообщение не найдено!';

$lang['deletes']='Удалить?';


/*
	New PM creating
*/
$this->LANG['NewPMCreating']	= 'Создание нового Личного сообщения';
$this->LANG['FillFullForm']		= 'Вы должны полностью заполнить эту форму';
$this->LANG['ForUserName']		= 'Для пользователя';
$this->LANG['MessageText']		= 'Текст сообщения';
$this->LANG['ShowMail']			= 'Показать получателю Ваш E-mail?';
$this->LANG['OwnerNeeded']		= 'Необходимо указать получателя сообщения!';
$this->LANG['TitleNeeded']		= 'Необходимо указать тему сообщения!';
$this->LANG['MessageNeeded']	= 'Вы не ввели текст сообщения!';
$this->LANG['UserNotFound']		= 'Указанный пользователь не найден!';
$this->LANG['DoNotSendSelf']	= 'Вы не можете отправлять сообщения самому себе!';
$this->LANG['EmailNewPMTitle'] 	= 'Новое сообщение в Личном ящике';
$this->LANG['NewPMSendedOk'] 	= 'Новое сообщение, для пользователя %s, успешно отправлено!';
$this->LANG['DoubleAddedOk'] 	= 'Ваше сообщение уже отправлено!';
$this->LANG['FloodLimit']		= 'Контроль повторов запущен в конференции, Вам нужно подождать %d секунд, чтобы отправить новое сообщение!';
$this->LANG['NewPMNotify']		= 'Здравствуйте, %s.
Вы просили нас сообщать Вам о новых сообщениях в
Вашем личном ящике на форуме "%s"
%s
===========================================================

В Вашем ящике новое сообщение от пользователя %s

===========================================================
Тема: "%s"

Текст сообщения:

%s';

/*
	Delete
*/
$this->LANG['DeleteNotSelect']	= 'Не выбрано ни одного сообщения для удаления!';
$this->LANG['DeleteTitle']		= 'Удаление сообщений';
$this->LANG['SelDeleteOk']		= 'Выбранные сообщения успешно удалены!';

/*
	Inbox
*/
$this->LANG['InboxTitle']		= 'Входящие сообщения (Inbox)';
$this->LANG['Sender']			= 'Отправитель';
$this->LANG['Reply2Message']	= 'Ответить?';
$this->LANG['ReplyQuote']		= 'Цитировать';
$this->LANG['DelConfirm']		= 'Уверены, что хотите удалить сообщение от пользователя';
$this->LANG['MessageFrom']		= 'Сообщение от пользователя по имени %s послано вам  %s';
$this->LANG['SendEmail']		= 'Отправить e-mail';

/*
	Outbox
*/
$this->LANG['OutboxTitle']		= 'Исходящие сообщения (Outbox)';
$this->LANG['Owner']			= 'Получатель';
$this->LANG['MessageTo']		= 'Вы отправили это сообщение для';
$this->LANG['DelConfirmOut']	= 'Уверены, что хотите удалить сообщение отправленное пользователю';
?>