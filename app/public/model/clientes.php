<?php

namespace ModulePHP;

use ModulePHP\Database\DB as DB;
use ModulePHP\Database\Statement\Select;
use ModulePHP\Database\Statement\Insert;
use ModulePHP\MVC;
use ModulePHP\Calendar;
use ModulePHP\Database\Con;

use Exception;

class Clientes
{

    public function __construct()
    {
    }

    public static function create($nome, $email, $contato1, $nascimento)
    {
        $con = new Con();

        $con->begin_transaction();

        try {
            $userInsert = "INSERT INTO Users (email, password, userlevel_id) VALUES (?, ?, 1)";
            $stmt = $con->prepare($userInsert);
            $password = password_hash($nascimento, PASSWORD_DEFAULT);
            $stmt->bind_param("ss", $email, $password);
            $stmt->execute();
            $userId = $stmt->insert_id;

            // Gera o username conforme a regra especificada
            $username = date("Ymd") . str_pad(substr($userId, -4), 4, "0", STR_PAD_LEFT);

            // Atualiza o username na tabela Users
            $stmt = $con->prepare("UPDATE Users SET username = ? WHERE id = ?");
            $stmt->bind_param("si", $username, $userId);
            $stmt->execute();

            // Insere na tabela Clientes
            $clienteInsert = "INSERT INTO Clientes (id, nome, contato1, datanascimento, pagamento_status) VALUES (?, ?, ?, ?, false)";
            $stmt = $con->prepare($clienteInsert);
            $stmt->bind_param("isss", $userId, $nome, $contato1, $nascimento);
            $stmt->execute();

            // Se tudo estiver ok, commit na transação
            $con->commit();
            return ['success' => true, 'username' => $username, 'cliente_id' => $userId];
        } catch (Exception $e) {
            // Se ocorrer algum erro, faz rollback
            $con->rollback();

            // Verifica o tipo de erro e personaliza a mensagem
            $errorMessage = $e->getMessage();
            if (strpos($errorMessage, 'Duplicate entry') !== false) {
                #$errorMessage = 'O e-mail fornecido já está em uso.';
            }

            // Retornar a mensagem de erro personalizada
            return ['success' => false, 'message' => $errorMessage];
        }
    }

    public static function savePagamento_link($cliente_id, $pagamento_link)
    {
        // Obtém a conexão com o banco de dados através da classe Con
        $con = new Con();

        $con->begin_transaction();

        try {
            // Prepara a query de atualização
            $stmt = $con->prepare("UPDATE Clientes SET pagamento_link = ? WHERE id = ?");
            $stmt->bind_param("si", $pagamento_link, $cliente_id);

            // Executa a query
            $stmt->execute();

            // Verifica se a atualização foi bem-sucedida
            if ($stmt->affected_rows > 0) {
                // Commit na transação
                $con->commit();
                $result = ["success" => true, "message" => "Link de pagamento atualizado com sucesso"];
            } else {
                throw new Exception("Nenhum cliente encontrado com o ID fornecido ou nenhuma alteração necessária.");
            }
        } catch (Exception $e) {
            // Rollback na transação em caso de erro
            $con->rollback();
            $result = ["success" => false, "message" => "Erro ao atualizar o link de pagamento: " . $e->getMessage()];
        } finally {
            // Fecha a conexão
            $con->close();
        }

        return $result;
    }

    public static function changePagamento_status($cliente_id, $status = false)
    {
        // Obtém a conexão com o banco de dados através da classe Con
        $con = new Con();

        $con->begin_transaction();

        try {
            // Prepara a query de atualização
            $stmt = $con->prepare("UPDATE Clientes SET pagamento_status = ? WHERE id = ?");
            $stmt->bind_param("ii", $status, $cliente_id);

            // Executa a query
            $stmt->execute();

            // Verifica se a atualização foi bem-sucedida
            if ($stmt->affected_rows > 0) {
                // Commit na transação
                $con->commit();
                $result = ["success" => true, "message" => "Pagamento atualizado"];
            } else {
                throw new Exception("Nenhum cliente encontrado com o ID fornecido ou nenhuma alteração necessária.");
            }
        } catch (Exception $e) {
            // Rollback na transação em caso de erro
            $con->rollback();
            $result = ["success" => false, "message" => "Erro ao atualizar o status de pagamento: " . $e->getMessage()];
        } finally {
            // Fecha a conexão
            $con->close();
            return $result;
        }
    }
}