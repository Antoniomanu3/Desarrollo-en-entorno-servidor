<?php

class AccesoDatos
{

    private static $modelo = null;
    private $dbh = null;

    public static function getModelo()
    {
        if (self::$modelo == null) {
            self::$modelo = new AccesoDatos();
        }
        return self::$modelo;
    }

    // Constructor privado  Patron singleton

    private function __construct()
    {

        $this->dbh = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DATABASE);

        if ($this->dbh->connect_error) {
            die(" Error en la conexión " . $this->dbh->connect_errno);
        }
    }

    // Cierro la conexión anulando todos los objectos relacioanado con la conexión PDO (stmt)
    public static function closeModelo()
    {
        if (self::$modelo != null) {
            $obj = self::$modelo;
            // Cierro la base de datos
            $obj->dbh->close();
            self::$modelo = null; // Borro el objeto.
        }
    }

    public function numClientes(): int
    {
        $result = $this->dbh->query("SELECT id FROM Clientes");
        $num = $result->num_rows;
        return $num;
    }

    // SELECT Devuelvo la lista de Usuarios
    public function getClientes($primero, $cuantos, $orden = "id", $sortOrder = "asc"): array
    {

        $tuser = [];

        $stmt_usuarios = $this->dbh->prepare("select * from Clientes  order by $orden $sortOrder limit $primero,$cuantos");
        // Si falla termina
        if (!$stmt_usuarios) die(__FILE__ . ':' . __LINE__ . $this->dbh->error);
        // Ejecutar
        $stmt_usuarios->execute();
        // Obtengo los resultados
        $result = $stmt_usuarios->get_result();
        // Si hay resultado correctos
        if ($result) {
            // Obtengo cada fila de la respuesta como un objeto de tipo Usuario
            while ($user = $result->fetch_object('Cliente')) {
                $tuser[] = $user;
            }
        }
        // Devuelvo el array de objetos
        return $tuser;
    }

    public function getCliente(int $id)
    {
        $cli = false;

        $stmt_usuario = $this->dbh->prepare("select * from Clientes where id =?");
        if ($stmt_usuario == false) die($this->dbh->error);

        // Enlazo $login con el primer ? 
        $stmt_usuario->bind_param("i", $id);
        $stmt_usuario->execute();
        $result = $stmt_usuario->get_result();
        if ($result) {
            $cli = $result->fetch_object('Cliente');
        }

        return $cli;
    }

    public function getClienteSiguiente($id)
    {

        $cli = false;

        $stmt_usuario = $this->dbh->prepare("select * from Clientes where id >? limit 1");
        if ($stmt_usuario == false) die($this->dbh->error);

        $stmt_usuario->bind_param("i", $id);
        $stmt_usuario->execute();
        $result = $stmt_usuario->get_result();
        if ($result) {
            $cli = $result->fetch_object('Cliente');
        }

        return $cli;
    }

    public function getClienteAnterior($id)
    {

        $cli = false;

        $stmt_usuario = $this->dbh->prepare("select * from Clientes where id <? order by id DESC limit 1");
        if ($stmt_usuario == false) die($this->dbh->error);

        // Enlazo $login con el primer ? 
        $stmt_usuario->bind_param("i", $id);
        $stmt_usuario->execute();
        $result = $stmt_usuario->get_result();
        if ($result) {
            $cli = $result->fetch_object('Cliente');
        }

        return $cli;
    }

    public function getPrimerCliente()
    {

        $cli = false;

        $stmt_usuario = $this->dbh->prepare("select * from Clientes order by id limit 1");
        if ($stmt_usuario == false) die($this->dbh->error);

        $stmt_usuario->execute();
        $result = $stmt_usuario->get_result();
        if ($result) {
            $cli = $result->fetch_object('Cliente');
        }

        return $cli;
    }
    public function getUltimoCliente()
    {
        $cli = false;

        $stmt_usuario = $this->dbh->prepare("select * from Clientes order by id DESC limit 1");
        if ($stmt_usuario == false) die($this->dbh->error);

        $stmt_usuario->execute();
        $result = $stmt_usuario->get_result();
        if ($result) {
            $cli = $result->fetch_object('Cliente');
        }

        return $cli;
    }


    // UPDATE TODO
    public function modCliente($cli): bool
    {

        $stmt_moduser = $this->dbh->prepare("UPDATE Clientes set first_name=?,last_name=?,email=?," .
            "gender=?, ip_address=?,telefono=? WHERE id=?");
        if ($stmt_moduser == false) die($this->dbh->error);

        $stmt_moduser->bind_param(
            "ssssssi",
            $cli->first_name,
            $cli->last_name,
            $cli->email,
            $cli->gender,
            $cli->ip_address,
            $cli->telefono,
            $cli->id
        );
        $stmt_moduser->execute();
        return ($this->dbh->affected_rows == 1);
    }

    public function addCliente($cli): bool
    {

        // El id se define automáticamente por autoincremento.
        $stmt_creauser = $this->dbh->prepare(
            "INSERT INTO `Clientes`( `first_name`, `last_name`, `email`, `gender`, `ip_address`, `telefono`)" .
                "Values(?,?,?,?,?,?)"
        );
        if ($stmt_creauser == false) die($this->dbh->error);

        $stmt_creauser->bind_param(
            "ssssss",
            $cli->first_name,
            $cli->last_name,
            $cli->email,
            $cli->gender,
            $cli->ip_address,
            $cli->telefono
        );
        $stmt_creauser->execute();
        $resu = ($this->dbh->affected_rows == 1);
        return $resu;
    }

    public function borrarCliente(int $id): bool
    {
        $stmt_boruser = $this->dbh->prepare("delete from Clientes where id =?");
        if ($stmt_boruser == false) die($this->dbh->error);

        $stmt_boruser->bind_param("i", $id);
        $stmt_boruser->execute();
        $resu = ($this->dbh->affected_rows == 1);
        return $resu;
    }

    public function existeEmail(string $email): bool
    {
        $stmt_existe = $this->dbh->prepare("select * from Clientes where email =?");
        if (!$stmt_existe) die($this->dbh->error);

        $stmt_existe->bind_param("s", $email);
        $stmt_existe->execute();
        $result = $stmt_existe->get_result();
        return $result->num_rows == 1;
    }
}
