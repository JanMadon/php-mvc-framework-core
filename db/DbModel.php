<?php

namespace app\core\db;

use app\core\Aplication;
use app\core\Model;

abstract class DbModel extends Model
{
    abstract static public function tableName(): string;

    abstract public function attributes(): array;

    abstract static public function primaryKey(): string;
    public function save(): true
    {
        $tableName = $this->tableName();
        $attributes = $this->attributes();
        $params = array_map(fn($attr)=>":$attr", $attributes);
        $statement = self::prepare("INSERT INTO $tableName(".implode(',', $attributes).") 
                    VALUES(".implode(',', $params).")");

        foreach ($attributes as $attribute){
            $statement->bindValue(":$attribute", $this->{$attribute});
        }

        $statement->execute();

        return true;
    }

    static public function findOne($where) // []
    {
        /** metoda findOne jest wywołana na modelu User (dzecko DbModel)
         * chcemy się odnieść do metody statycznej z klasy User
         * dlatego jest odnomimy się do niej przez static:: a nie self::
         */
        $tableName = static::tableName(); // sta
        $attributes = array_keys($where);
        $sql = implode('AND', array_map(fn($attr) => "$attr = :$attr", $attributes));

        $statement = self::prepare("SELECT * FROM $tableName WHERE $sql");
        foreach ($where as $key => $item ){
            $statement->bindValue(":$key", $item);
        }
        $statement->execute();

        return $statement->fetchObject(static::class);
    }

    public static function prepare($sql)
    {

        return Aplication::$app->db->pdo->prepare($sql);
    }


}