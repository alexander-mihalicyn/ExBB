<?php
if (!defined('IN_EXBB')) die('Hack attempt!');

$this->LANG['AdminRanks']		= 'Управление званиями';
$this->LANG['AdminRanksDesc']	= 'Здесь вы можете создавать новые звания, удалять и редактировать старые';
$this->LANG['RankTitle']		= 'Звание';
$this->LANG['RankMinimum']		= 'Минимум сообщений';
$this->LANG['CreateNewRank']	= 'Создать новое звание';
$this->LANG['RankNotFound']		= 'Выбранное звание не найдено!';
$this->LANG['LogAddNew']		= 'добавление нового звания';
$this->LANG['LogEdit']			= 'редактирование звания';
$this->LANG['RankDelete']		= 'удаление звания';

/*
	edit & add rank
*/
$this->LANG['ActionEdit']		= 'Редактирование звания';
$this->LANG['ActionEditDesc']	= 'Здесь вы можете отредактировать звание <b><u>%s</u></b>';
$this->LANG['ActionAdd']		= 'Новое звание';
$this->LANG['ActionAddDesc']	= 'Здесь вы можете создать новое звание';
$this->LANG['RankMinimumMes']	= 'Это звание будет показываться до достижения пользователем указанного количества сообщений';
$this->LANG['RankImage']		= 'Картинка к званию (имя файла, например rank3.gif)';
$this->LANG['RankImageMes']		= 'Здесь вы можете присвоить всем имеющим такое звание специальное изображение. Файл изображения предварительно должен быть загружен в папку "im/images".';
$this->LANG['RankNoRank']		= 'Не указано звание!';
$this->LANG['RankNoPost']		= 'Не указан минимум сообщений';
$this->LANG['RankNoImage']		= 'Не указана картинка к званию!';
$this->LANG['RankImgNotExists'] = 'Не найден файл картинки к званию!<br>Вы должны загрузить файл картинки звания в папку "im/images"';
$this->LANG['RankEditedOk']		= 'Звание успешно отредактировано!';
$this->LANG['RankAddedOk']		= 'Новое звание успешно добавлено!';
$this->LANG['RankMinExists']	= 'Звание с указанным названием или минимумом сообщений уже существует!';

/*
	delet rank
*/
$this->LANG['RankDeletedOk']	= 'Выбранное звание успешно удалено!';
?>
