<!DOCTYPE html>
<html lang="pt-br">

    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="Site AV2">
        <meta name="author" content="Eyre Brasil">

        <title>Minha Estante Virtual</title>

        <!-- Bootstrap core CSS -->
        <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

        <!-- Custom fonts for this template -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800"
              rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Josefin+Slab:100,300,400,600,700,100italic,300italic,400italic,600italic,700italic"
              rel="stylesheet" type="text/css">

        <!-- Custom styles for this template -->
        <link href="css/business-casual.css" rel="stylesheet">

    </head>

    <body>

        <div class="tagline-upper text-center text-heading text-shadow text-white mt-5 d-none d-lg-block">Minha Estante Virtual</div>
        <div class="tagline-lower text-center text-expanded text-shadow text-uppercase text-white mb-5 d-none d-lg-block">Lasalle | SI | Prog Web II</div>

        <!-- Navigation -->
        <nav class="navbar navbar-expand-lg navbar-light bg-faded py-lg-4">
            <div class="container">
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav mx-auto">
                        <li class="nav-item px-lg-4">
                            <a class="nav-link text-uppercase text-expanded" href="index.php">Home
                                <span class="sr-only">(current)</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container text-heading">
            <div class="bg-faded p-4 my-4">

                <hr class="divider">
                <h2 class="text-center text-lg text-uppercase my-0">
                    <strong>Livro</strong>
                </h2>
                <hr class="divider">

                <?php
                /**
                 * Recebendo valores do form
                 */
                $titulo = isset($_POST["titulo"]) ? $_POST["titulo"] : "";
                $autor = isset($_POST["autor"]) ? $_POST["autor"] : "";
                $emprestado = isset($_POST["emprestado"]) ? $_POST["emprestado"] : "";
                $tag = isset($_POST["tag"]) ? $_POST["tag"] : "";
                $data = isset($_POST["data"]) ? $_POST["data"] : "";
                $status = isset($_POST["status"]) ? $_POST["status"] : "0";
                $sinopse = isset($_POST["sinopse"]) ? $_POST["sinopse"] : "";
                $acao = isset($_POST["acao"]) ? $_POST["acao"] : "";
                $acaoAux = $acao;
                $idLivro = 0;
                if (stristr($acaoAux, "editar")) {
                    $acaoAux = explode("-", $acaoAux);
                    $acao = $acaoAux[0];
                    $idLivro = $acaoAux[1];
                }
                echo "$acao   $idLivro";
                /**
                 * Setando conexao
                 */
                $host = "localhost";
                $user = "root";
                $pwd = "";
                $db = "progweb";
                $conn = mysqli_connect($host, $user, $pwd, $db);
                /**
                 * Execução do programa
                 */
                if (!$conn) {
                    die("Connection failed: " . mysqli_connect_error());
                } else {
                    switch ($acao) {
                        case "salvar":
                            salvar($conn, $titulo, $autor, $emprestado, $tag, $data, $status, $sinopse);
                            break;
                        case "editar":
                            preencher($conn, $idLivro, $titulo, $autor, $tag, $emprestado, $sinopse, $data, $status);
                            break;
                    }
                    mysqli_close($conn);
                }

                /**
                 * Funcoes
                 */
                function salvar($conn, $titulo, $autor, $emprestado, $tag, $data, $status, $sinopse) {
                    $query = "CALL SP_CADASTRAR ('$titulo','$autor','$status','$data','$emprestado','$sinopse','$tag')";

                    $count = mysqli_query($conn, $query);

                    if ($count > 0) {
                        echo "Registro inserido com sucesso.</br>";
                    } else {
                        echo "Não foi possível incluir registro: " . mysqli_error($conn) . "</br>";
                    }
                }

                function preencher($conn, $idLivro, &$tit, &$aut, &$tag, &$emp, &$sinop, &$data, &$status) {
                    $query = "  SELECT * FROM LIVRO WHERE IDLIV_LIV = $idLivro ";
                    $result = mysqli_query($conn, $query);
                    $count = mysqli_num_rows($result);
                    if ($count > 0) {
                        $row = mysqli_fetch_assoc($result);
                        $tit = $row["TITUL_LIV"];
                        $aut = $row["AUTOR_LIV"];
                        $emp = $row["EMPRE_LIV"];
                        $sinop = $row["SINOP_LIV"];
                        $data = $row["DTLEI_LIV"];
                        $status = $row["STLEI_LIV"];
                        
                        $query = "  SELECT * FROM TAG WHERE IDLIV_TAG = $idLivro ";
                        $result = mysqli_query($conn, $query);
                        $count = mysqli_num_rows($result);
                         if ($count > 0) {
                             $row = mysqli_fetch_assoc($result);
                             $listaTag = $row["DESCR_TAG"];
                             while ($row = mysqli_fetch_assoc($result)){
                                 $listaTag = $listaTag . "," . $row["DESCR_TAG"];
                             }
                             $tag = $listaTag;
                         }
                    } 
                }
                ?>  

                <form id="form-livro" action="#" method="post">
                    <div class="row">
                        <div class="form-group col-lg-6">
                            <label>Título:</label>
                            <input type="text" class="form-control" name="titulo" required="true" value="<?php echo $titulo; ?>">
                        </div>

                        <div class="form-group col-lg-6">
                            <label>Autor:</label>
                            <input type="text" class="form-control" name="autor" required="true" value="<?php echo $autor; ?>">
                        </div>

                        <div class="form-group col-lg-6">
                            <label>Emprestado para:</label>
                            <input type="text" class="form-control" name="emprestado" value="<?php echo $emprestado; ?>">
                        </div>

                        <div class="form-group col-lg-6">
                            <label>Tags:</label>
                            <input type="text" class="form-control" name="tag" value="<?php echo $tag; ?>">
                        </div>

                        <div class="form-group col-lg-6">
                            <label>Data de Leitura:</label>
                            <input type="date" class="form-control" name="data" value="<?php echo $data = date("d/m/Y");?>">
                        </div>

                        <div class="form-group col-lg-6">
                            <label>Status:</label>&nbsp;&nbsp;
                            <div>
                                <input type="radio" name="status" value="1" <?php echo $status == "1" ? "checked" : ""; ?>>
                                <label>Lido</label>&nbsp;
                                <input type="radio" name="status" value="0" <?php echo $status == "0" ? "checked" : ""; ?>>
                                <label>Vou ler</label>
                            </div>
                        </div>

                        <div class="clearfix"></div>

                        <div class="form-group col-lg-12">
                            <label>Sinopse:</label>
                            <textarea class="form-control" rows="6" form="form-livro" name="sinopse"></textarea>
                        </div>

                        <div class="form-group col-lg-12 text-right">
                            <button type="submit" class="btn btn-secondary" name="acao" value="salvar">Salvar</button>
                            <button type="button" class="btn btn-secondary" onclick="history.back()">Voltar</button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
        <!-- /.container -->

        <footer class="bg-faded text-center py-5">
            <div class="container">
                <p class="m-0">Copyright &copy; Eyre Brasil Montevecchi 2017</p>
            </div>
        </footer>

        <!-- Bootstrap core JavaScript -->
        <script src="vendor/jquery/jquery.min.js"></script>
        <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

        <!-- Zoom when clicked function for Google Map -->
        <script>
                                $('.map-container').click(function () {
                                    $(this).find('iframe').addClass('clicked')
                                }).mouseleave(function () {
                                    $(this).find('iframe').removeClass('clicked')
                                });
        </script>

    </body>

</html>