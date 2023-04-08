<!DOCTYPE html>
<html lang="pt_br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <title>Cotação do dólar</title>
</head>

<body>
    <div class="container">
        <div class="row justify-content-md-center">
            <div class="col-sm-12 col-md-5 col-lg-5 shadow-lg p-4 m-lg-5 border rounded">
                <form action="index.php" method="POST" class="row">
                    <div class="col-md-12  mb-3">
                        <label for="real">Informe um valor em reais: </label>
                        <input type="number" class="form-control" id="real" name="real" step="0.01" value="<?= $_POST['real'] ?>" />
                    </div>
                    <div class="col-md-12 d-grid  mb-3">
                        <button type="submit" class="btn btn-success">Calcular</button>
                    </div>
                </form>
                <?php
                //Pegando a data 07 dias antes da data atual
                $inicial = date("m-d-Y", strtotime("-7 days"));
                //Pegando a data atual.
                $final = date("m-d-Y");
                //Selecionar se quer fazer calcular sobre a primeira ou ultima cotação da busca
                //Opções = ASC e DESC
                $orderBy = "desc";
                //Url da API de contação do dólar.
                $url = 'https://olinda.bcb.gov.br/olinda/servico/PTAX/versao/v1/odata/CotacaoDolarPeriodo(dataInicial=@dataInicial,dataFinalCotacao=@dataFinalCotacao)?@dataInicial=\'' . $inicial . '\'&@dataFinalCotacao=\'' . $final . '\'&$top=1&$orderby=dataHoraCotacao%20'.$orderBy.'&$format=json&$select=cotacaoCompra,dataHoraCotacao';
                $dados = json_decode(file_get_contents($url), true);
                //Pegando o valor do dólar no indice "cotacaoCompra"
                $cotacao = $dados["value"][0]["cotacaoCompra"];
                //Pegando o valor informado no formulário.
                $real = $_POST['real'];
                //Dividindo o valor do real pela cotação recente do dólar.
                $dolar = $real / $cotacao;
                //Criando  um padrão de internacionalização de moeda
                //Uma observação, para que as formatações abaixo funcione é necessário descomentar a extensão intl que está no arquivo php.ini, caso contrário vai ocorrer o erro.
                $padrao = numfmt_create("pt_BR", NumberFormatter::CURRENCY);
                if ($_POST) {
                    print "A cotação mais recente do dólar é: <strong> R$ ". number_format($cotacao,2, ',','.').'</strong><br/>';
                    //A função number_format_currency recebe 03 paramêtros que são: O padrão (Onde você informa o idioma de exibição da moeda e a biblioteca de moeda), o valor que será que será formatado e o simbolo da moeda (No caso o real é BRL).
                    print "Os <em><strong>". numfmt_format_currency($padrao, $real, "BRL") . "</strong></em> informados corresponde a <em><strong>" . numfmt_format_currency($padrao, $dolar, "USD")."</strong></em>";
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>