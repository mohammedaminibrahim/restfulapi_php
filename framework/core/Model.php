<?php
namespace framework\core;

use PDO;

abstract class Model
{
    protected $table;
    protected $primaryKey = 'id';
    protected $fillable = [];
    private $connection;

    public function __construct()
    {
        $arr = explode('\\',strtolower(get_class($this)) . 's');
        $this->table = end($arr);
        $this->connection = new PDO('mysql:host=localhost;dbname=restful_api', 'root', 'limitless@2020');
    }

    public function all()
    {
        $statement = $this->connection->prepare("select * from {$this->table}");
        $statement->execute();
        $results = $statement->fetchAll(PDO::FETCH_CLASS);
        return json_encode($results);
    }

    public function find($id)
    {
        $statement = $this->connection->prepare("select * from {$this->table} where {$this->primaryKey} = :id");
        $statement->execute(['id' => $id]);
        $result = $statement->fetchObject(static::class);
        return json_encode($result);
    }

    public function create($data)
    {
        $keys = array_keys($data);
        $stringOfKeys = implode(',', $keys);
        $stringOfKeysWithColon = implode(',:', $keys);
        $statement = $this->connection->prepare("insert into {$this->table} ({$stringOfKeys}) values (:{$stringOfKeysWithColon})");
        $statement->execute($data);

        return json_encode($data);
    }

    public function update($data)
    {
        $keys = array_keys($data);
        $string = '';
        foreach ($keys as $key) {
            $string .= "$key = :$key,";
        }
        $string = rtrim($string, ',');
        $data['id'] = $this->id;

        $statement = $this->connection->prepare("update {$this->table} set {$string} where {$this->primaryKey} = :id");
        $statement->execute($data);
    }

    public function delete()
    {
        $statement = $this->connection->prepare("delete from {$this->table} where {$this->primaryKey} = :id");
        $statement->execute(['id' => $this->id]);
    }

    public function save()
    {
        if (isset($this->id)) {
            $this->update($this->getFillableData());
        } else {
            $this->create($this->getFillableData());
        }
    }

    public function getFillableData()
    {
        $data = [];
        foreach ($this->fillable as $fillable) {
            $data[$fillable] = $this->{$fillable};
        }
        return $data;
    }

    public function __set($name, $value)
    {
        $this->{$name} = $value;
    }

    public function __get($name)
    {
        return $this->{$name};
    }

    public static function __callStatic($name, $arguments)
    {
        $instance = new static;
        return $instance->{$name}(...$arguments);
    }



}