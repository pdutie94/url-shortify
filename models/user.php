<?php
class User {
    public $id;
    public $username;
    public $email;

    function __construct($id, $username, $email)
    {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
    }

    public static function get_user( $username ) {
        $sql  = 'SELECT * FROM users WHERE username=:username OR email=:username';
		$user = DB::fetch($sql, array( ':username' => $username));

        return $user;
    }

    public static function get_user_by_id( $user_id ) {
        $sql  = 'SELECT * FROM users WHERE id=:id';
		$user = DB::fetch($sql, array( ':id' => $user_id));

        return $user;
    }

    public static function get_current_user() {
        $sql  = 'SELECT * FROM users WHERE id=:id';
		$user = DB::fetch($sql, array( ':id' => $_SESSION['id']));

        return $user;
    }
}
