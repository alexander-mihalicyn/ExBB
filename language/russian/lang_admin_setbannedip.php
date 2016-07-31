<?php
if (!defined('IN_EXBB')) die('Hack attempt!');
$this->LANG['AdminBannedIp']		= 'Блокировка IP адресов';
$this->LANG['AddNewIp']				= 'Заблокировать новый IP';
$this->LANG['AddNewIpHelp']			= 'Вы можете использовать маску при вводе IP адреса.<br> Например так 127.0.* , в этом случае всеIP адреса начинающиеся с 127.0. будут заблокированы.';
$this->LANG['EnterNewIp']			= 'Введите IP адрес который хотите заблокировать';
$this->LANG['EnterNewIpDesc']		= 'Введите описание причины блокировки';
$this->LANG['BannedIpList']			= 'Список заблокированных IP адресов';
$this->LANG['IpAdress']				= 'IP адрес';
$this->LANG['DescTitle']			= 'Причина блокировки';
$this->LANG['EmptyBannedIp']		= 'Список IP адресов пуст!';
$this->LANG['LogNewIpBanned']		= 'заблокирован новый IP адрес';
$this->LANG['LogEditingIpBanned']	= 'редактирование IP адреса';
$this->LANG['LogDeleteIp']			= 'удаление IP адреса';
$this->LANG['IpUnbannedOk']			= 'IP адрес разблокирован и удален из базы заблокированных адресов!';
/*
	add new ip
*/
$this->LANG['IpNotEntered']			= 'Вы не ввели IP адрес';
$this->LANG['WrongCharsInIP']		= 'Недопустимые знаки в IP адресе';
$this->LANG['DescNotEntered']		= 'Вы не ввели описание причины';
$this->LANG['IpExists']				= 'Такой IP адрес уже заблокирован!';
$this->LANG['IpAddedOk']			= 'IP адрес %s добавлен в лист запрета';
/*
	edit ip
*/
$this->LANG['IpNotExists']			= 'Такого IP адреса нет в базе';
$this->LANG['EditIp']				= 'Редактирование IP адреса';
$this->LANG['IpChang']				= 'Изменить IP адрес';
$this->LANG['DescChang']			= 'Изменить описание причины бана';
$this->LANG['EditingSavedOk']		= 'Редактирование успешно сохранено!';