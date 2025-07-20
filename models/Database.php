<?php

class Database {
    public static function getConnection() {
        return $GLOBALS['db'];
    }
}
