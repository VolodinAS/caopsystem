<?php
// ЗАМЕНА INSERTED_ID
const ID = 'INSERTED_ID';

const SHA512 = 'sha512';
const MD5 = 'md5';
const SHA256 = 'sha256';


// ВОЗВРАТЫ ФУНКЦИЙ
const RES_SUCCESS = 'success';
const RES_FAIL = 'fail';


// BOOTSTRAP типы цветовых схем
const BT_THEME_PRIMARY = 'primary';
const BT_THEME_SECONDARY = 'secondary';
const BT_THEME_SUCCESS = 'success';
const BT_THEME_DANGER = 'danger';
const BT_THEME_WARNING = 'warning';
const BT_THEME_INFO = 'info';
const BT_THEME_LIGHT = 'light';
const BT_THEME_DARK = 'dark';


// UTF-8
const UTF8 = 'UTF-8';


// BOOTSTRAP РАЗМЕРЫ МОДАЛЬНОГО ЭКРАНА
const CODE_NONE = '';
const CODE_XS = 'xs';
const CODE_SM = 'sm';
const CODE_MD = 'md';
const CODE_LG = 'lg';
const CODE_XL = 'xl';
const CODE_F = 'fluid';


// ДАТЫ ДЛЯ DATE()
const DM = 'd.m';
const DMY = 'd.m.Y';
const DMYHI = 'd.m.Y H:i';
const DMYHIS = 'd.m.Y H:i:s';
const HI = 'H:i';
const HIS = 'H:i:s';


// БУКВЕННЫЕ АТРИБУТЫ ДЛЯ DATE() В ВИДЕ КОНСТАНТ
/**
 * День месяца, 2 цифры с ведущим нулём	от 01 до 31
 */
const DATE_DAY00 = 'd';
/**
 * Текстовое представление дня недели, 3 символа	от Mon до Sun
 */
const DATE_WMND = 'D';
/**
 * День месяца без ведущего нуля	от 1 до 31
 */
const DATE_MON1 = 'j';
/**
 * Полное наименование дня недели	от Sunday до Saturday
 */
const DATE_WKFU = 'l';
/**
 * Порядковый номер дня недели в соответствии со стандартом ISO 8601	от 1 (понедельник) до 7 (воскресенье)
 */
const DATE_WKNM = 'N';
/**
 * Порядковый номер дня недели	от 0 (воскресенье) до 6 (суббота)
 */
const DATE_WENG = 'w';
/**
 * Порядковый номер дня в году (начиная с 0)	От 0 до 365
 */
const DATE_YDAY = 'z';
/**
 * Полное наименование месяца, например, January или March	от January до December
 */
const DATE_MONFU = 'F';
/**
 * Порядковый номер месяца с ведущим нулём	от 01 до 12
 */
const DATE_MON00 = 'm';
/**
 * Сокращённое наименование месяца, 3 символа	от Jan до Dec
 */
const DATE_MSHO = 'M';
/**
 * Порядковый номер месяца без ведущего нуля	от 1 до 12
 */
const DATE_MNH1 = 'n';
/**
 * Количество дней в указанном месяце	от 28 до 31
 */
const DATE_AMDM = 't';


// ДРУГИЕ РАЗНЫЕ КОНСТАНТЫ
/**
 * Разделитель для explode() областей исследования на УЗИ
 */
const SYS_SEP_UZI_RES_AREA_UZI = ';';
/**
 * Заменить строку для получения ID области
 */
const SYS_URA = 'uzi_research_areas_';

const EOL = (PHP_SAPI == 'cli') ? PHP_EOL : '<br />';

define("ROOT", $_SERVER['DOCUMENT_ROOT']);
const MYSQLI = '/engine/php/mysqli';
const IOFactory = ROOT . '/engine/php/tools/PHPExcel-1.8/Classes/PHPExcel/IOFactory.php';
const PHPExcel = ROOT . '/engine/php/tools/PHPExcel-1.8/Classes/PHPExcel.php';
const SimpleXLS = ROOT. '/engine/php/tools/SimpleXLS/SimpleXLS.php'; // 7.1

const TABLES_FILES_PATH = ROOT . '/engine/temp/tables/';
const TABLES_EXPORT = ROOT . '/engine/temp/tables/export/';
//const TABLES_EXPORT2 = ROOT . '/engine/temp/tables/';

const ENGINE_MYISAM = 'MyISAM';
const ENGINE_INNODB = 'InnoDB';


/**
 * Очищение журнала
 */
const LOG_TYPE_CLEAR_JOURNAL = 1;
/**
 * Удаление журнала
 */
const LOG_TYPE_REMOVE_JOURNAL = 2;
/**
 * Удаление выбранных пациентов
 */
const LOG_TYPE_REMOVE_PATIENTS = 3;
/**
 * Создание пациента
 */
const LOG_TYPE_CREATE_PATIENT = 4;
/**
 * Удаление пациента из БД
 */
const LOG_TYPE_DELETE_PATIENT = 5;

/**
 * СОЗДАНИЕ ВНЕШНИХ КЛЮЧЕЙ
 *
 * В ОСНОВНОЙ ТАБЛИЦЕ выбираем поле (внешний ключ), к которому будет ПРИСОБАЧИВАТЬСЯ другая таблица [caop_params > caop_params.param_type]
 * Затем выбираем ВНЕШНЮЮ таблицу, в которой СОДЕРЖАТСЯ ЗНАЧЕНИЯ для ВНЕШНЕГО КЛЮЧА [caop_params_types]
 * Затем выбраем поле, которое будет соответствовать значениям внешнего ключа [caop_params_types.param_type_id]
 */

const VERTICAL_ALIGN_TOP = 'vertical-align: top; !important;';
const STYLE_VERTICAL_ALIGN_TOP = ' style="' . VERTICAL_ALIGN_TOP . '"';