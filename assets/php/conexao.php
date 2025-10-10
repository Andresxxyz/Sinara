<?php
    function conexao(){
        return new mysqli("localhost", "root", "", "repositorio");
    }
    
    function fecharConexao($conn){
        $conn->close();
    }