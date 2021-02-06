<?php

class Model
{
    /** 访问数据库的PDO对象 */
    protected $pdo;

    /**
     * 当前类的构造函数
     * 读取数据库配置信息文件，并初始化PDO对象
     */
    function __construct()
    {
        if (file_exists('my.ini')) {
            $arr = @parse_ini_file('my.ini');
        } else {
            exit('没有找到对应的数据库配置文件信息 ...');
        }
        $dsn = "{$GLOBALS['config']['type']}:dbname={$GLOBALS['config']['dbname']};host={$GLOBALS['config']['host']};port={$GLOBALS['config']['port']};charset={$GLOBALS['config']['charset']}";
        $user = $GLOBALS['config']['username'];
        $password = $GLOBALS['config']['password'];
        $this->pdo = new PDO($dsn, $user, $password);
    }

    /**
     * 销毁自己的操作类时，同时销毁被创建了的PDO对象
     */
    function __destruct()
    {
        $this->pdo = null;
    }

    /**
     * 执行DML操作语句
     * @param $sql     需要执行的SQL语句
     * @return int     返回执行语句后受到影响的行数
     */
    public function pdoExec($sql)
    {
        return $this->pdo->exec($sql);
    }

    /**
     * 返回结果为数组结构
     * @param $sql        需要执行的SQL语句
     * @param int $var 执行query函数的参数（可选值：PDO::FETCH_BOTH（默认）,PDO::FETCH_NUM,PDO::FETCH_ASSOC）
     * @return array      将结果转换为数组并返回，如果没有查询结果则返回一个空数组
     */
    public function arrayByPdoQuery($sql, $var = PDO::FETCH_BOTH)
    {
        $ps = $this->pdo->query($sql, $var);
        if ($ps)
            return $ps->fetchAll();
        return array();
    }

    /**
     * 获取查询结果并封装为一个对象数组
     * @param $sql                  需要执行的SQL语句
     * @param string $class_name 创建类的名称
     * @param array $ctor_args 此数组的元素被传递给对应类的构造函数
     * @return array                返回组装好的对象数组
     */
    public function objectByPdoQuery($sql, $class_name = 'stdClass', $ctor_args = array())
    {
        $ps = $this->pdo->query($sql);
        $arr = array();
        if ($ps) {
            while ($obj = $ps->fetchObject($class_name, $ctor_args)) {
                array_push($arr, $obj);
            }
        }
        return $arr;
    }

    /**
     * 使用PDO预编译语句执行查询操作并返回结果集合
     * @param $sql          需要执行的SQL语句（预编译语句写法）
     * @param array $arr 预编译语句需要添加的数值，数组结构
     * @return array        转换为数组结构的返回值结果
     */
    public function preTreatMent($sql, array $arr = array())
    {
        $ps = $this->pdo->prepare($sql);
        $ps->execute($arr);
        if ($ps)
            return $ps->fetchAll();
        return array();
    }

    /**
     * 使用PDO预编译语句执行查询操作并返回结果集合
     * @param $sql          需要执行的SQL语句（预编译语句写法）
     * @param array $arr 预编译语句需要添加的数值，数组结构
     * @return int          返回DML执行后受影响的行数
     */
    public function intByPdoPrepare($sql, array $arr = array())
    {
        $ps = $this->pdo->prepare($sql);
        $ps->execute($arr);
        return $ps->rowCount();
    }

    /**
     * 获取查询结果并封装为一个对象数组
     * @param $sql                  需要执行的SQL语句
     * @param array $arr 预编译语句需要添加的数据值，数组结构
     * @param string $class_name 创建类的名称
     * @param array $ctor_args 此数组的元素被传递给对应的构造函数
     * @return array                返回组装好的对象数组
     */
    public function objectByPdoPrepare($sql, array $arr = array(), $class_name = 'stdClass', $ctor_args = array())
    {
        $ps = $this->pdo->prepare($sql);
        $ps->execute($arr);
        $array = array();
        if ($ps) {
            while ($obj = $ps->fetchObject($class_name, $ctor_args)) {
                array_push($array, $obj);
            }
        }
        return $array;
    }
}