<?php
    function conexao(){
        return new mysqli("localhost", "root", "", "sinara");
    }
    
    function fecharConexao($conn){
        $conn->close();
    }
?>
