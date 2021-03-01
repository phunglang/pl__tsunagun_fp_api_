<?php
namespace App\Constants;

abstract class SettingNotifyConstant {
    const TYPE_MESSAGE = 'message';
	const TYPE_LIKE = 'like';
	const TYPE_ORDER = 'orther';

	public static function getType($value) { //translate
        switch ($value) {
            case self::TYPE_MESSAGE:
                return 0;
            case self::TYPE_LIKE:
                return 1;
            case self::TYPE_ORDER:
                return 2;
            default:
                null;
        }
    }
}