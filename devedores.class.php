<?php

include 'variaveisMongoDB.php';
include 'funcoesGlobais.class.php';

class devedores extends funcoesGlobais {

    /**
     * Função para inserção, edição e exclusão do deividas:
     * 
     * Está função é usada para incluir, editar e excluir dividas:
     * 
     * @return type
     */
    public function registraFormularioDividas() {

        //carrega o formulario em um array:
        $form = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        //Carrega o tipo do metodo:
        $method = filter_input(INPUT_SERVER, 'REQUEST_METHOD');

        // Verifica se algo foi postado
        //Captura os dados no formulário
        if ('POST' == $method && !empty($form)) {

            // Faz o loop dos dados do post
            foreach ($form as $key => $value) {

                // Configura os dados do post para a propriedade $form_dado
                $this->form_dado[$key] = $value;


                // Configura os dados do formulário
                foreach ($form as $key => $value) {
                    $this->form_dado[$key] = $value;
                }
//                    return;
            }
        } else {

            // Termina se nada foi enviado
            return;
        }

        $bulkWrite = new MongoDB\Driver\BulkWrite;

        if ($this->form_dado['tipoTela'] != 'exclusao') {
            //trata o valor do campo para gravar em formato americano. Ex.: R$ 1000,00 para 1000.00
            $this->form_dado['valor'] = str_replace('R$ ', '', $this->form_dado['valor']);
            $this->form_dado['valor'] = str_replace('.', '', $this->form_dado['valor']);
            $this->form_dado['valor'] = str_replace(',', '.', $this->form_dado['valor']);

            $doc = ['cliente' => $this->form_dado['cliente'], 'valor' => $this->form_dado['valor'], 'data' => $this->form_dado['data'], 'motivo' => $this->form_dado['motivo']];
        }

        //identificação se é edição, delete ou inserção:
        if ($this->form_dado['tipoTela'] == 'edicao') {
            $filter = ['_id' => new MongoDB\BSON\ObjectID($this->form_dado['idDivida'])];
            $update = ['$set' => $doc];
            $options = ['multi' => false, 'upsert' => false];
            $bulkWrite->update($filter, $update, $options);
            $complementoMensagem = 'alterados';
        } elseif ($this->form_dado['tipoTela'] == 'exclusao') {

            if (isset($this->form_dado['idDividaModal'])) {
                $filter = ['_id' => new MongoDB\BSON\ObjectID($this->form_dado['idDividaModal'])];
            } else {
                $filter = ['cliente' => $this->form_dado['idClienteModal']];
            }
            $bulkWrite->delete($filter);

            $complementoMensagem = 'deletados';
        } else {
            $bulkWrite->insert($doc);
            $complementoMensagem = 'registrados';
        }

        $GLOBALS["mongo"]->executeBulkWrite($GLOBALS["dataBaseMongoDB"] . '.' . $GLOBALS["colletionCliente"], $bulkWrite);

        echo "<script>alert('Dados " . $complementoMensagem . " com sucesso!')</script>";
    }

    /**
     * Função para consultar clientes na api jsonplaceholder
     *
     * @return type
     */
    public function consultaClientesAPI() {

        $arrContextOptions = array(
            "ssl" => array(
                "verify_peer" => false,
                "verify_peer_name" => false,
            ),
        );

        $clientes = file_get_contents("https://jsonplaceholder.typicode.com/users", false, stream_context_create($arrContextOptions));

        $clientes = json_decode($clientes, true);

        $clientes = array_column($clientes, 'name', 'id');

        return $clientes;
    }

    /**
     * Função para consultar dívidas no Banco de Dados MongoDB
     * 
     * Esta função é usada na lista de devedores e de dívidas e na tela de edição de dívidas.
     * 
     * @param type $agruparCliente
     * @param type $idCliente
     * @param type $idDivida
     * @return type
     */
    public function consultaDividasBD($agruparCliente = null, $idCliente = null, $idDivida = null) {

        if ($idCliente) {
            $filter = ['cliente' => $idCliente];
        } elseif ($idDivida) {
            $filter = ['_id' => new MongoDB\BSON\ObjectID($idDivida)];
        } else {
            $filter = [];
        }

        $options = [];

        $query = new \MongoDB\Driver\Query($filter, $options);
        $rows = $GLOBALS["mongo"]->executeQuery($GLOBALS["dataBaseMongoDB"] . '.' . $GLOBALS["colletionCliente"], $query);

        $listaClientesAPI = $this->consultaClientesAPI();

        //A variável $i é usada no caso quando for para lista de dívidas. Neste caso é feito um array para adicionar.
        $i = 0;
        foreach ($rows as $document) {
            $document = get_object_vars($document);

            //Caso a variável $agruparCliente esteja informada e já exista o cliente no array pela chave, o loop pula para o proximo.
            if ($agruparCliente && $dados[$document['cliente']]) {
                continue;
            }

            if ($idCliente) {
                $dados[$document['cliente']][$i] = $document;

                $dados[$document['cliente']][$i]['nomeCliente'] = $listaClientesAPI[$document['cliente']];

                setlocale(LC_MONETARY, "pt_BR", "ptb");

                $dados[$document['cliente']][$i]['valor'] = $this->money_format('%n', $dados[$document['cliente']][$i]['valor']);

                $i++;
            } else {
                $dados[$document['cliente']] = $document;

                $dados[$document['cliente']]['nomeCliente'] = $listaClientesAPI[$document['cliente']];

                setlocale(LC_MONETARY, "pt_BR", "ptb");
                $dados[$document['cliente']]['valor'] = $this->money_format('%n', $dados[$document['cliente']]['valor']);
            }
        }

        if (isset($dados)) {
            return $dados;
        }
    }

}
