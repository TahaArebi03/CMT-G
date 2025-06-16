<?php
class VoteOption {
    const YES = 'yes';
    const NO = 'no';
    const ABSTAIN = 'abstain';

    public static function isValid($option): bool {
        return in_array($option, [self::YES, self::NO, self::ABSTAIN]);
    }

    public static function all(): array {
        return [self::YES, self::NO, self::ABSTAIN];
    }
}
?>