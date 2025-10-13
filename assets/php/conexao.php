<?php
    function conexao(){
        return new mysqli("localhost", "root", "1234", "repositorio");
    }
    
    function fecharConexao($conn){
        $conn->close();
    }