<?php
if (!defined('IN_EXBB')) die('Hack attempt!');

$this->LANG['AdminSmiles']		= 'Редактирования смайликов';
$this->LANG['SmileNoCats']		= "Не задано ни одной категории!<br /><a href=\"setsmiles.php?action=newcat\" title=\"Создать новую категорию?\">Создать новую категорию?</a>";
$this->LANG['SmilesNotSet']		= 'Список смайлов не задан';
$this->LANG['SmCode']			= 'Код смайла';
$this->LANG['SmileDesc']		= 'Описание смайла';
$this->LANG['CreateNewCat']		= 'Создать новую категорию';
$this->LANG['CatNotfound']		= 'Указанная категория не найдена';
$this->LANG['SmileFile']		= 'Файл смайла';
$this->LANG['SmileCodeNotSet']	= 'Не указан код смайлика';
$this->LANG['SmileDescNotSet']	= 'Не указано описание смайлика';
$this->LANG['SmFileNotFound']	= 'Не найден файл выбранного смайлика';
$this->LANG['SmileExists']		= 'Смайл с таким кодом уже существует';
$this->LANG['SmDirEmpty']		= 'В директории "%s" нет ни одного файла';
$this->LANG['NoDesc']			= 'Не указано';
$this->LANG['LogNewCat']		= 'создание новой категории смайлов';
$this->LANG['LogEditCat']		= 'редактирование категории смайлов';
$this->LANG['LogDelCat']		= 'удаление категории смайлов';
$this->LANG['LogSmAdd']			= 'добавление смайла';
$this->LANG['LogSmEdit']		= 'редактирование смайла';
$this->LANG['LogSmDel']			= 'удаление смайла';
$this->LANG['LogAddGroup']		= 'групповое добавление смайлов';

/*
	add group
*/
$this->LANG['SmGroupHelp']		= 'Выберите категорию, введите код и описание для каждого смайла и нажмите кнопку "Добавить смайлы"';
$this->LANG['AddGroupSmiles'] 	= 'Добавить смайлы';
$this->LANG['InCat']			= 'В категорию';
$this->LANG['TempDirNotExists']	= 'Не найдена временная директория смайлов для добавления!';
$this->LANG['NoSmilesInTemp']	= 'Во временной директории нет смайлов для добавления!';
$this->LANG['SmGroupAddedOk'] 	= 'Смайлы успешно добавлены в базу!';
$this->LANG['SmPartAddedOk'] 	= 'Из %d смайлов находящихся в папке "%s",  успешно добавлено только %d смайлов!<br>Возможные причины:
<ol>
<li>Неправильное имя файла смайла (имена смалов должны быть на латинице)</li>
<li>В списке смайлов уже есть смайл с выбранным кодом</li>
<li>Не найдена указанная категория</li>
<li>Не удалось скопировать файл из папки "%s" в папку "%s" (проверьте права на указанные папки)</li>
</ol>';

/*
	del smile
*/
$this->LANG['SmileDeletedOk']	= 'Смайл успешно удален!';


/*
	edit smile
*/
$this->LANG['SmileEditing']		= 'Редактирование смайлика в категории ';
$this->LANG['SmileIdNotExists']	= 'Смайл с указанным ID не найден!';
$this->LANG['SmileIdExists']	= 'Смайл с таким кодом уже существует!';
$this->LANG['SmileSavedOk']		= 'Смайл успешно отредактирован!';

/*
	add smile
*/
$this->LANG['AddSmileInCat']	= 'Добавление смайлика в категорию ';
$this->LANG['SmileAddedOk']		= 'Смайл успешно добавлен в категорию!';

/*
	del cat
*/
$this->LANG['CatDeletedOk']		= 'Указанная категория успешно удалена!';
/*
	add new cat
*/
$this->LANG['CatDescNotSet']	= 'Не указано новое название категории!';
$this->LANG['NewCatAddedOk']	= 'Новая категория смайлов успешно создана!';
$this->LANG['EnterNewCatName']	= 'Введите название новой категории:';
$this->LANG['NewCatAdding']		= 'Создание новой категории смайлов';

/*
	edit cat
*/
$this->LANG['NewCatNameNotSet'] = 'Не указано новое название категории!';
$this->LANG['CatRenamedOk']		= 'Категория успешно переименована!';
$this->LANG['CatNameTitle']		= 'Категория: <b>%s</b>';
$this->LANG['ChangeCatName']	= 'Изменить название категории:';
$this->LANG['SaveChange']		= 'Сохранить изменения';

/*
	show smiles
*/
$this->LANG['AdminSmilesDesc']	= 'Здесь вы можете создавать категории смайлов, добавлять новые, редактировать и удалять старые смайлы.';
$this->LANG['GoToCat']			= 'Перейти в категорию';
$this->LANG['Smile']			= 'Изображение';
$this->LANG['SmWhatDo']			= 'Действие';
$this->LANG['SmCat']			= 'Категория';
$this->LANG['SmAddNewInCat']	= 'Добавить смайл в категорию';
$this->LANG['AddTempGroup']		= 'Добавить смайлы из временной папки';
$this->LANG['SelectAction']		= 'Выберите действие ';
?>
